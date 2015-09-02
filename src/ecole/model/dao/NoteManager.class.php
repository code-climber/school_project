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
        
        if(!empty($aNote['notes_by_matiere'])){
            
            $oNote->setNote($aNote['notes_by_matiere']);
            
        }else{
            $oNote->setNote(array());
        }
        
        $oNote->setEleve_id($aNote['eleve_id']);
        $oNote->setMatiere_id($aNote['matiere_id']);
        if(!empty($aNote['matiere'])){
            $oNote->setMatiere($aNote['matiere']);
        }else{
            $oNote->setMatiere("");
        }
        
        return $oNote;
    }
    
    public static function getAllNotesByEleve($idEleve){
        $sQuery = "SELECT * FROM note AS n JOIN matieres AS m ON m.id = n.matiere_id ";
        $sQuery .= "WHERE n.eleve_id=".$idEleve." GROUP BY n.matiere_id";

        $aNotes = array();
        
        foreach(DBOperation::getAll($sQuery) as $aNote){
            $aNotesByMatiere = array();
            $iCurrentMatiereId = $aNote['matiere_id'];
            $sQueryByMatiere = 'SELECT note FROM note WHERE matiere_id='.$iCurrentMatiereId." AND eleve_id = ".$idEleve;
            
            foreach(DBOperation::getOne($sQueryByMatiere) as $aNoteByMatiere){
                $aNotesByMatiere[] = $aNoteByMatiere;
            }
//            var_dump($aNotesByMatiere);die();
            $matiere = $aNote['libelle'];
            $aNote['matiere'] = $matiere;
            $aNote['notes_by_matiere'] = $aNotesByMatiere;
            
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
