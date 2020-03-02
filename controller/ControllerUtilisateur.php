<?php

$array = array('model', 'ModelUtilisateur.php');
require_once File::build_path($array); // chargement du modèle
require_once File::build_path(array('lib','Security.php'));
require_once File::build_path(array('lib','Session.php'));

class ControllerUtilisateur {

    public static function readAll() {
        $controller = 'utilisateur';
        $view = 'list';
        $pagetitle='Liste des utilisateurs';
        $tab_u = ModelUtilisateur::selectAll();
        require File::build_path(array('view','utilisateur','view.php'));
    }

    public static function read() {
        $login = myGet('login');
        $u = ModelUtilisateur::select($id);
        if ($u == NULL) {
            $controller = 'utilisateur';
            $view = 'error';
            $pagetitle='Erreur';
            require File::build_path(array('view','utilisateur','view.php'));
        }
        else {
            $controller = 'utilisateur';
            $view = 'detail';
            $pagetitle='Detail utilisateur';
            require File::build_path(array('view','utilisateur','view.php'));
        }
    }

    public static function create() {
        $test = 'required';
        $test2 = 'created';
        $uId = '';
        $uLogin = '';
        $uNom = '';
        $uPrenom = '';
        $uEmail = '';
        $uMdp = '';
        $uMdpVerif = '';
        $controller = 'utilisateur';
        $view = 'update';
        $pagetitle='Créer utilisateur';
        require File::build_path(array('view','utilisateur','view.php'));
    }

    public static function created() {
        $login = myGet('login');
        $nom = myGet('nom');
        $prenom = myGet('prenom');
        $email = myGet('email');
        $mdp = myGet('mdp');
        $mdpVerif = myGet('mdpValid');
        if(ModelUtilisateur::checkLoginEmail($login,$email)) {
            if ((strcmp($mdp, $mdpVerif) == 0)&&(filter_var($email,FILTER_VALIDATE_EMAIL))) {
            	$security = new Security();
                $nonce = $security->generateRandomHex();
            	$mdp_chiffre = $security->chiffrer($mdp);
            	$utilisateur = new ModelUtilisateur($login,$nom,$prenom,$email,$mdp_chiffre,$nonce);
            	$test = $utilisateur->save();
                $mail = '<p>Bonjour, cliquer sur le lien <a href="http://webinfo.iutmontp.univ-montp2.fr/~lhermeniert/PHP/TD8/index.php?action=validate&login=' . $login . '&nonce='. $nonce .'&controller=utilisateur"> présent</a> pour valider votre inscription, merci</p>';
                //mail('bob@yopmail.com', 'Validation', $mail);
            	$controller = 'utilisateur';
            	$view = 'created';
            	$pagetitle='Compte utilisateur créée';
            	require File::build_path(array('view','utilisateur','view.php'));
            }
            else {
            	ControllerUtilisateur::create();
            }
        }
        else {
        	echo "login ou mail déjà existant";
            ControllerUtilisateur::create();
            
        }   
    }

    public static function delete2() {
        $u = ModelUtilisateur::select(myGet('id'));
        $uLogin = htmlspecialchars($u->getLogin());
        if ((Session::is_user($uLogin))||(Session::is_admin())) {
            ModelUtilisateur::delete(myGet('id'));
            $controller = 'utilisateur';
            $view = 'deleted';
            $pagetitle='utilisateur supprimée';
            require File::build_path(array('view','utilisateur','view.php'));
        }
        else {
            ModelUtilisateur::connect();
        }
    }

    public static function update() {
        $u = ModelUtilisateur::select(myGet('login'));
        $uLogin = htmlspecialchars($u->getLogin());
        if ((Session::is_user($uLogin))||(Session::is_admin())) {
            $controller = 'utilisateur';
            $view = 'update';
            $pagetitle='Update utilisateur';
            require File::build_path(array('view','utilisateur','view.php'));
        }
        else {
            ModelUtilisateur::connect();
        }
    }

    public static function updated() {
        $mdp = myGet('mdp');
        $mdpVerif = myGet('mdpValid');
        if (strcmp($mdp, $mdpVerif) == 0) {
            $security = new Security();
            $mdp_chiffre = $security->chiffrer($mdp);
            $data = array(
                'login' => myGet('login'),
                'nom' => myGet('nom'),
                'prenom' => myGet('prenom'),
                'check_admin' => myGet('check_admin')
            );
            if (Session::is_user(myGet('login'))||Session::is_admin()) {
                ModelUtilisateur::update($data);
                $controller = 'utilisateur';
                $view = 'updated';
                $pagetitle='Utilisateur update';
                require File::build_path(array('view','utilisateur','view.php'));
            }
            else {
                ControllerUtilisateur::update();
            }
        }
        else {
            ControllerUtilisateur::update();
        }
        
        
    }

    public static function connect() {
    	if(isset($_COOKIE['remindLogin'])&&isset($_COOKIE['remindMdp'])) {
    		ControllerUtilisateur::connected();
    	}
    	else {
        	$controller = 'utilisateur';
        	$view = 'connect';
        	$pagetitle='Connexion';
        	require File::build_path(array('view','utilisateur','view.php'));
    	}
    }

    public static function connected() {
    	if (isset($_COOKIE['remindLogin'])&&isset($_COOKIE['remindMdp'])) {
    		$login = $_COOKIE['remindLogin'];
    		$mdp_chiffre = $_COOKIE['remindMdp'];
    	}
    	else {
        	$login = myGet('login');
        	$mdp = myGet('mdp');
        	$security = new Security();
        	$mdp_chiffre = $security->chiffrer($mdp);
    	}
        $utilisateur = ModelUtilisateur::select($login);
        if(!empty($utilisateur)) {
            $nonce = $utilisateur->getNonce();
            $controller = 'utilisateur';
            $view = 'connected';
            $pagetitle='Connecté';
            if (ModelUtilisateur::testNonce($login,$nonce)) {
                if (ModelUtilisateur::checkPassword($login,$mdp_chiffre)) {
                    $_SESSION['login'] = $login;
                    if(ModelUtilisateur::admin($login)) {
                        $_SESSION['admin'] = true;
                    }
                    else {
                        $_SESSION['admin'] = false;
                    }
                    if(myGet('remind')) {
                        if(strcmp(myGet('remind'),'on') == 0) {
                    	   setcookie('remindLogin',$login,time()+60);
                    	   setcookie('remindMdp',$mdp_chiffre,time()+60);
                        }
                        else {
                    	   if(isset($_COOKIE['remindLogin'])&&isset($_COOKIE['remindMdp'])&&strcmp(myGet('remind'),'on') == 0) {
                    		  setcookie('remindLogin','',time()-1);
                    		  setcookie('remindMdp','',time()-1);
                    	   } 
                        }
                    }
                    require File::build_path(array('view','utilisateur','view.php'));
                }
                else {
                    echo 'Mauvais login ou mot de passe';
                    ControllerUtilisateur::connect();
                }
            }
            else {
                ControllerUtilisateur::connect();
            }
        }
        else {
            echo 'utilisateur inexistant';
            ControllerUtilisateur::connect();

        }
    }

    public static function deconnect() {
        session_unset();
        session_destroy();
        setcookie(session_name(),'',time()-1);
        ControllerEau::readAll();
    }

    public static function validate() {
        $login = myGet('login');
        $nonce = myGet('nonce');
        if (ModelUtilisateur::select($login)&&ModelUtilisateur::testNonce($login,$nonce)) {
            ModelUtilisateur::updateNonce($login,$nonce);
            ControllerEau::readAll(); //page qui dit de confirmer les mails
        }
    }

    public static function passwordChange() {
        $controller = 'utilisateur';
        $view = 'passwordChange';
        $pagetitle='Changer de mot de passe';
        require File::build_path(array('view','utilisateur','view.php'));
    }

    public static function passwordChanged() { 
        $security = new Security();
        $mdp_chiffre = $security->chiffrer(myGet('mdp'));
        if(Session::is_user(myGet('login'))) {
            if (ModelUtilisateur::checkPassword(myGet('login'),$mdp_chiffre)) {
                if(strcmp(myGet('newmdp'), myGet('vnewmdp')) == 0) {
                    ModelUtilisateur::changePassword(myGet('login'),$security->chiffrer(myGet('newmdp')));
                    ControllerUtilisateur::afficherCompte();
                }
                else {
                    ControllerUtilisateur::passwordChange();
                }
            } 
            else {
                ControllerUtilisateur::passwordChange();
            }
        }
        else {
            ControllerUtilisateur::passwordChange();
        }
    }

    public static function afficherPanier() {
        $controller = 'eau';
        $view = 'panier';
        $pagetitle='Panier';
        require File::build_path(array('view','eau','view.php'));
    }

    public static function afficherCompte() {
        $controller = 'utilisateur';
        $view = 'compte';
        $pagetitle = 'Votre compte';
        require File::build_path(array('view','utilisateur','view.php'));
    }

    public static function connexionAuto() {
        if(isset($_COOKIE['remindLogin'])&&isset($_COOKIE['remindMdp'])) {
            setcookie('remindLogin','',time()-1);
            setcookie('remindMdp','',time()-1);
        } 
        ControllerUtilisateur::afficherCompte();
    }
}