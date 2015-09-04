<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace ecole\model;

/**
 * Description of User
 *
 * @author Samy
 */
class User {
    protected $id;
    protected $login;
    protected $password;
    protected $salt;
    
    public function __construct($id,$login,$password,$salt){
        $this->setId($id)
             ->setLogin($login)
             ->setPassword($password)
             ->setSalt($salt);       
    }
    
    function getId() {
        return $this->id;
    }

    function getLogin() {
        return $this->login;
    }

    function getPassword() {
        return $this->password;
    }

    function getSalt() {
        return $this->salt;
    }

    function setId($id) {
        $this->id = $id;
        return $this;
    }

    function setLogin($login) {
        $this->login = $login;
        return $this;
    }

    function setPassword($password) {
        $this->password = $password;
        return $this;
    }

    function setSalt($salt) {
        $this->salt = $salt;
        return $this;
    }


}
