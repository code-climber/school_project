<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ecole\model;

/**
 * Description of Note
 *
 * @author Samy
 */
class Note {
    private $id;
    private $note = array();
    private $eleve_id;
    private $matiere_id;
    private $aMatieres = array();
    
    function getId() {
        return $this->id;
    }

    function getNote() {
        return $this->note;
    }

    function getEleve_id() {
        return $this->eleve_id;
    }

    function getMatiere_id() {
        return $this->matiere_id;
    }

    function getAMatieres() {
        return $this->aMatieres;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setNote($note) {
        $this->note = $note;
    }

    function setEleve_id($eleve_id) {
        $this->eleve_id = $eleve_id;
    }

    function setMatiere_id($matiere_id) {
        $this->matiere_id = $matiere_id;
    }

    function setAMatieres($aMatieres) {
        $this->aMatieres = $aMatieres;
    }




}
