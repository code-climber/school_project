<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ecole\model\dao;
use ecole\model\Note;
use ecole\model\dao\DBOperation;

/**
 * Description of NoteManager
 *
 * @author Samy
 */
class NoteManager {
    
    private static function convertToObject($aNote){
        
        $oNote = new Note();
        $oNote->setId($aNote['id']);
        $oNote->setNote($aNote['note']);
        $oNote->setEleve_id($aNote['eleve_id']);
        $oNote->setMatiere_id($aNote['matiere_id']);
        if(!empty($aNote['matiere'])){
            $oNote->setAMatieres($aNote['matiere']);
        }else{
            $oNote->setAMatieres(array());
        }
        return $oNote;
    }
    
    public static function getAllNotesByEleve($idEleve){
        $sQuery = "SELECT * FROM note AS n JOIN matieres AS m ON m.id = n.matiere_id ";
        $sQuery .= "WHERE n.eleve_id=".$idEleve.";";
        
        $aNotes = array();
        
        foreach(DBOperation::getAll($sQuery) as $aNote){
            $aMatieres = array();
            $aMatieres[]= $aNote['libelle'];
            $aNote['matiere'] = $aMatieres;
            $aTotalNotes = array();
            $aTotalNotes[]=$aNote['note'];
            $aNote['note']=$aTotalNotes;
            $aNotes[]=self::convertToObject($aNote);
            
        }
        
        return $aNotes;
    }
    
    public static function addNote(Note $oNote){
        $note = $oNote->getNote();
        $eleveId = $oNote->getEleve_id();
        $matiereId = $oNote->getMatiere_id();
        
        $sQuery = "INSERT INTO note VALUES ('','$note','$eleveId','$matiereId')";
        
        
        $bSuccess = DBOperation::exec($sQuery);
        
        if(!$bSuccess){
            return false;
        }else{
            return true;
        }
        
        return;
    }
}
