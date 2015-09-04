<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ecole\model\dao;

use ecole\model\User;
use ecole\model\dao\DBOperation;
/**
 * Description of UserManager
 *
 * @author Samy
 */
class UserManager {
    private static function convertToObject($aUser) {
        $oUser = new User($aUser['id'], $aUser['login'], $aUser['password'], $aUser['salt']);

        return $oUser;
    }
    
    public static function getPassword($password,$salt){
        return sha1($password, $salt);
    }
    
    public static function connect(User $oUser){
        $sQuery = "SELECT * FROM users WHERE login='{$oUser->getLogin()}' limit 1";
        
        $sResult = DBOperation::getOne($sQuery);
        
    if($sResult != false && $sResult[0]['password']==sha1($sResult[0]["salt"]).sha1($oUser->getPassword())){
            $_SESSION['role'] = $sResult[0]["role"];
        }else{
            unset($_SESSION['role']);
            return false;
        }
        return true;
    }
    
    public static function logout(){
        unset($_SESSION['role']);
    }
    
}
