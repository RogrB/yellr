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
    
    function getInfo($username) {
        $ok = $this->db->getInfo($username);
        return $ok;
    }
    
    function updateInfo($info) {
        $ok = $this->db->updateInfo($info);
        return $ok;
    }
    
    function createYell($user, $yell) {
        $ok = $this->db->createYell($user, $yell);
        return $ok;
    }
    
    function getYells($user, $sessionID) {
        $ok = $this->db->getYells($user, $sessionID);
        return $ok;
    }
    
    function getUserID($username) {
        $ok = $this->db->getUserID($username);
        return $ok;
    }
    
    function setLike($yell, $user) {
        $ok = $this->db->setLike($yell, $user);
        return $ok;
    }
    
    function unLike($yell, $user) {
        $ok = $this->db->unLike($yell, $user);
        return $ok;
    }    
    
    function deleteYell($yell) {
        $ok = $this->db->deleteYell($yell);
        return $ok;
    }  
    
    function getYell($yellNR) {
        $ok = $this->db->getYell($yellNR);
        return $ok;
    }
    
    function follow($user, $follower) {
        $ok = $this->db->follow($user, $follower);
        return $ok;
    }
    
    function unFollow($user, $follower) {
        $ok = $this->db->unFollow($user, $follower);
        return $ok;
    }
    
    function getFeed($userID) {
        $ok = $this->db->getFeed($userID);
        return $ok;
    }
    
    function getStats($userID) {
        $ok = $this->db->getStats($userID);
        return $ok;
    }
    
    function checkIfUsernameExists($username) {
        $ok = $this->db->checkIfUsernameExists($username);
        return $ok;
    }
    
}
