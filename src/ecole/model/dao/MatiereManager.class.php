<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ecole\model\dao;
use ecole\model\dao\DBOperation;
use ecole\model\Matiere;
/**
 * Description of Matiere
 *
 * @author Samy
 */
class MatiereManager {
    private static function convertToObject($aMatiere){
        $oMatiere = new Matiere();
        $oMatiere->setId($aMatiere['id']);
        $oMatiere->setLibelle($aMatiere['libelle']);
        return $oMatiere;
    }
    
    public static function getAllMatieres(){
        $sQuery = "SELECT * FROM matieres;";
        $aMatieres = array();
        foreach(DBOperation::getAll($sQuery) as $aMatiere){
            $aMatieres[]=self::convertToObject($aMatiere);
        }
        return $aMatieres;
                
    }
}
