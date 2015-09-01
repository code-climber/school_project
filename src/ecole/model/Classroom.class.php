<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ecole\model;

/**
 * Description of Classroom
 *
 * @author Samy
 */
class Classroom {
    private $id;
    private $nom_classe;
    private $nb_salle;
    
    public function setId($id){
        $this->id = $id;
    }
    
    public function getId(){
        return $this->id;
    }
    
    public function setNomClasse($nom_classe){
        $this->nom_classe = $nom_classe;
    }
    
    public function getNomClasse(){
        return $this->nom_classe;
    }
    
    public function setNbSalle($nb_salle){
        $this->nb_salle = $nb_salle;
    }
    
    public function getNbSalle(){
        return $this->nb_salle;
    }
    
}
