<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ecole\model\dao;
use ecole\model\Classroom;
/**
 * Description of ClasseManager
 *
 * @author Samy
 */
class ClasseManager {
    private static function convertToObject($aClasse){
        $oClasse = new Classroom();
        $oClasse->setId($aClasse['id']);
        $oClasse->setNomClasse($aClasse['name']);
        $oClasse->setNbSalle($aClasse['location']);
        return $oClasse;
    }
    
    
    //cette fonction renvoie un tableau de noms de classes qui servira à
    //récupérer le nom de la classe dans l'objet élève.
    public static function getAllClasses(){
        $sQuery = "SELECT * FROM classes;";
        $aClasses = array();
        foreach(DBOperation::getAll($sQuery) as $aClasse){
            $aClasses[$aClasse['id']] = $aClasse['name'];
        }
        return $aClasses;
    }
    
    public static function getAllClassesObject(){
        $sQuery = "SELECT * FROM classes;";
        $aClasses = array();
        foreach(DBOperation::getAll($sQuery) as $aClasse){
            $aClasses[] = self::convertToObject($aClasse);
        }
        return $aClasses;
    }
}
