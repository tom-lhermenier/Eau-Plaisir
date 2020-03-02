<?php
require_once File::build_path(array('model','Model.php'));
class ModelEau extends Model{


  /*----- Les attributs -----*/

   
  private $id;
  private $nom;
  private $type;
  private $prix;
  private $description;
  private $qtt;
  private $lien_image;
  protected static $object = 'eau';
  protected static $object2 = 'eau';
  protected static $primary='id';
  


  /*----- Les getters -----*/
     

  public function getId() {
    return $this->id;  
  }

  public function getNom(){
    return $this->nom;
  }

  public function getType() {
    return $this->type;
  }

  public function getPrix() {
    return $this->prix;
  }

  public function getDescription() {
    return $this->description;
  }

  public function getQtt() {
    return $this->qtt;
  }

  public function getLienImage() {
    return $this->lien_image;
  }


  /*----- Les setters -----*/
     

  public function setId($id2) {
    $this->id = $id2;
  }

  public function setNom($nom2) {
    $this->nom = $nom2;
  }

  public function setType($type2) {
    $this->type = $type2;
  }

  public function setPrix($prix2) {  
    $this->prix = $prix2;
  }

  public function setDescription($description2) {  
    $this->description = $description2;
  }

  public function setQtt($qtt2) {  
    $this->qtt = $qtt2;
  }

  public function setLienImage($lien_image2) {
    $this->lien_image = $lien_image2;
  }


  /*----- Constructeurs -----*/
      

  public function __construct($i = NULL, $n = NULL, $t = NULL, $p = NULL, $d = NULL, $q = NULL, $l = NULL) {
    if (!is_null($i) && !is_null($n) && !is_null($t) && !is_null($p) && !is_null($d) && !is_null($q) && !is_null($l)) {
      $this->id = $i;
      $this->nom = $n;
      $this->type = $t;
      $this->prix = $p;
      $this->description = $d;
      $this->qtt = $q;
      $this->lien_image = $l;
    }
  }
}

