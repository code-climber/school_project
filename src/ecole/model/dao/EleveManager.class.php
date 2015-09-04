<?php

namespace ecole\model\dao;
use ecole\model\Eleve;
use ecole\model\dao\DBOperation;

/**
 * Description of EleveManager
 *
 * @author Samy
 */
class EleveManager {
    
    private static function convertToObject($aEleve){ 
        die("zaz");
        $oEleve = new Eleve();
        
        $oEleve->setId($aEleve['id']);
        $oEleve->setNom($aEleve['nom']);
        $oEleve->setPrenom($aEleve['prenom']);
        $oEleve->setSexe($aEleve['sexe']);
        $oEleve->setAge($aEleve['age']);
        $oEleve->setClasse($aEleve['classe_id']);
        $oEleve->setClasseId($aEleve['classe_id']);
        
        if(!empty($aEleve["classe"])){
            $oEleve->setClasse($aEleve["classe"]);
        }else{
            $oEleve->setClasse(array());
        }
        return $oEleve;
    }
    
    public static function getAllKids(){
        
        $sQuery='SELECT *,e.id FROM eleves AS e JOIN classes ON classes.id=e.classe_id;';
        $aEleves = array();
        
        foreach(DBOperation::getAll($sQuery) as $aEleve){
           //récupération du nom de la classe
            $aClasse = array();
            $aClasse[] = $aEleve["name"];
            
            //ajout de la classe au tableau d'élèves en vue de l'hydratation
            $aEleve["classe"] = $aClasse;
            
            //hydratation
            $aEleves[] = self::convertToObject($aEleve);
            
        }
        
        return $aEleves;
    }
    
    public static function getOneKid($kidId){
        $sQuery='SELECT *,e.id FROM eleves AS e JOIN classes AS c ON c.id=e.classe_id ';
        $sQuery .= 'WHERE e.id = '.$kidId;
        
        $aKid = array();

        foreach(DBOperation::getAll($sQuery) as $kid){
           
            $aKid['id'] = $kid['id'];
            $aKid['prenom'] = $kid['prenom'];
            $aKid['nom'] = $kid['nom'];
            $aKid['age'] = $kid['age'];
            $aKid['sexe'] = $kid['sexe'];
            $aKid['classe_id'] = $kid['classe_id'];
            
            $aClasses = array();
            $aClasses[] = $kid['name'];
            
            $aKid['classe'] = $aClasses;
        }
        
        return self::convertToObject($aKid);
    }
    
    public static function getChosenKids($eleveName){
        $eleveName = substr($eleveName, 1);
        $sQuery='SELECT *,e.id FROM eleves AS e JOIN classes ON classes.id=e.classe_id ';
        $sQuery .= "WHERE e.prenom LIKE '%$eleveName' OR e.nom LIKE '%".$eleveName.';';

        $aChosenEleves = array();
        foreach(DBOperation::getAll($sQuery) as $aEleve){
            $aClasse = array();
            $aClasse[] = $aEleve['name'];
            
            $aChosenEleves['classe'] = $aClasse;
            $aChosenEleves[] = self::convertToObject($aEleve);
        }
        return $aChosenEleves;
    }
    
    public static function addEleve(Eleve $oEleve){
        $nom = $oEleve->getNom();
        $prenom = $oEleve->getPrenom();
        $age = $oEleve->getAge();
        $sexe = $oEleve->getSexe();
        $classeId = $oEleve->getClasseId();

        $sQuery = 'INSERT INTO eleves VALUES ';
        $sQuery .= "('','$prenom', '$nom', '$sexe', '$age', $classeId);";
        
        $bSuccess = DBOperation::exec($sQuery);
        if(!$bSuccess){
            return false;
        }
        return true;
    }
    
    public static function updateEleve(Eleve $oEleve){
 
        $idEleve = $oEleve->getId();
        $nom = addslashes($oEleve->getNom());
        $prenom = addslashes($oEleve->getPrenom());
        $age = addslashes($oEleve->getAge());
        $sexe = addslashes($oEleve->getSexe());
        $classe = $oEleve->getClasseId();
        
        $sQuery = "UPDATE eleves SET ";
        $sQuery .= "nom = '$nom', prenom = '$prenom', age = '$age', sexe = '$sexe', classe_id = '$classe' ";
        $sQuery .= 'WHERE id = '.$idEleve;
        
        $bSuccess = DBOperation::exec($sQuery);
        if(!$bSuccess){
            return false;
        }else{
            return true;
        }
        
        return;
    }
    
    public static function deleteEleve(Eleve $oEleve){
        
        $sQuery = 'DELETE FROM eleves WHERE id= '.$oEleve->getId(); 
        $bSuccess = DBOperation::exec($sQuery);
        if(!$bSuccess){return false;}
    }
}
