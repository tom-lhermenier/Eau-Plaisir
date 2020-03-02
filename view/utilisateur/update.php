<?php
if (strcmp('readonly',$test ) == 0) {
  $u = ModelUtilisateur::select(myGet('login'));
  $uLogin = htmlspecialchars($u->getLogin());
  $uNom = htmlspecialchars($u->getNom());
  $uPrenom = htmlspecialchars($u->getPrenom());
  $uEmail = htmlspecialchars($u->getEmail());
}
    echo '<div id="form_create">
    <form method="post" action="index.php">
        <fieldset>
          <legend>'; 
          if (Session::is_admin()) {
            echo 'Create / Update';
          }
          else {
            echo 'S\'inscire';
          }
           echo '</legend>
          <p>
            <label for="login_id">Login</label> 
            <input type="text" placeholder="Ex : firminoe" name="login" id="login_id" value="';
            if(myGet('login')) {
              echo myGet('login') . '"';
            }
            else {
              echo $uLogin . '"';
            }
            echo 'required/>
          </p>
          <p>
            <label for="nom_id">Nom</label> 
            <input type="text" placeholder="Ex : firmino" name="nom" id="nom_id" value="';
            if(myGet('nom')) {
              echo myGet('nom') . '"';
            }
            else {
              echo $uNom . '"';
            }
            echo 'required/>
          </p>
          <p>
            <label for="prenom_id">Prenom</label> 
            <input type="text" placeholder="Ex : enzo" name="prenom" id="prenom_id" value="';
            if(myGet('prenom')) {
              echo myGet('prenom') . '"';
            }
            else {
              echo $uPrenom . '"';
            }
            echo 'required/>
          </p>
          <p>
            <label for="email_id">Email</label> 
            <input type="text" placeholder="Ex : exemple@exemple.com" name="email" id="email_id" value="';
            if(myGet('email')) {
              echo myGet('email') . '"';
            }
            else {
              echo $uEmail . '"';
            }
            echo 'required/>
          </p>
          <p>
            <label for="mdp_id">Mot de passe</label> 
            <input type="password" name="mdp" id="mdp_id" value="';
            if(isset($_GET['mdp'])) {
              echo $_GET['mdp'] . '"';
            }
            else{
              echo '"';
            }
            echo 'required/>
          </p>
          <p>
            <label for="mdpvalid_id">Vérifier mot de passe</label> 
            <input type="password" name="mdpValid" id="mdpvalid_id" value="';
            if(myGet('mdpValid')) {
              echo myGet('mdpValid');
            }
            else{
              echo '"';
            }
            echo 'required/>
          </p>';
          

          if (Session::is_admin()) {
          echo '<p>
              <label for="check_admin">Admin ?</label> :
            <input type="checkbox" name="check_admin" id="check_admin"';
            if (ModelUtilisateur::admin($uLogin)) { 
              echo 'checked="checked"';
            } 
            else{
              echo '"';
            }
            echo 'required/>
            </p>';
          }
          echo '<p>
            <input type="submit" value="S\'inscrire" />
            <input type="hidden" name="action" value="'. $test2 .'">
            <input type="hidden" name="controller" value="utilisateur">
          </p>
          <div id="div_inscrire"> <a id="inscrire" href="http://webinfo.iutmontp.univ-montp2.fr/~firminoe/eCommerce/index.php?action=connect&controller=utilisateur"> Se connecter </a> </div>
        </fieldset> 
      </form>
      </div>';
  

?>  