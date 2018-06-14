<?php
include_once '../DAL/userDatabase.php';
//include_once '../DAL/userDatabaseStub.php';

class userLogic {
    private $db;
    
    function __construct($innDb=null) {
        if($innDb==null) {
            $this->db = new userDB(); 
        }
        else {
            $this->db=$innDb;
        }
    }
    
    function getLatestYell() {
        $yell = $this->db->getLatestYell();
        return $yell;
    }
    
    function registerUser($user) {
        $ok = $this->db->registerUser($user);
        return $ok;
    }
    
    function login($user) {
        $ok = $this->db->login($user);
        return $ok;
    }
    
}
