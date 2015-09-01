<?php

namespace ecole\model;

/**
 * Description of Eleve
 *
 * @author Samy
 */
class Eleve {
    
    private $id;
    private $nom;
    private $prenom;
    private $sexe;
    private $age;   
    private $aClasse = array();
    private $classeId;
    
    public function setId($id){
        $this->id = $id;
    }
    
    public function getId(){
        return $this->id;
    }
    
    public function setNom($nom){
        $this->nom = $nom;
    }
    
    public function getNom(){
        return $this->nom;
    }
    
    public function setPrenom($prenom){
        $this->prenom = $prenom;
    }
    
    public function getPrenom(){
        return $this->prenom;
    }
    
    public function setSexe($sexe){
        $this->sexe = $sexe;
    }
    
    public function getSexe(){
        return $this->sexe;
    }
    
    public function setAge($age){
        $this->age = $age;
    }
    
    public function getAge(){
        return $this->age;
    }
    
    public function setClasse($aClasse){
        $this->aClasse = $aClasse;
        return $this;
    }
    
    public function getClasse(){
        return $this->aClasse;
    }
    
    public function setClasseId($classeId){
        $this->classeId = $classeId;
    }
    
    public function getClasseId(){
        return $this->classeId;
    }
}
