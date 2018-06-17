<?php
include_once '../Model/domeneModell.php';

class userDB {
    private $db;
    
    function __construct() {
        $this->db=new mysqli("localhost","root","","test");
        $this->db->set_charset("utf8");
    }
    
    function getLatestYell() {
        $sql = "SELECT yell,username,date FROM yell, user WHERE yell.userID = user.userID ORDER BY yellNR DESC LIMIT 1";                                
        $resultat = $this->db->query($sql);
        if (!$resultat) {
            return "Error";
        }
        else {
            $objekt = $resultat->fetch_object();
            return $objekt;
        }
    }
    
    function registerUser($user) {
        $salt = uniqid(mt_rand(), true);
        $hashpassord = crypt($user->password, '$6$' . $salt);
        $date = date("d/m/Y");
        $sql = "Insert INTO user (userID,username,email,password,joindate)";
        $sql .= "Values (DEFAULT,'$user->username','$user->email','$hashpassord','$date');";
        $resultat = $this->db->query($sql);
        if (!$resultat) {
            return "Feil";
        }
        else {
            if ($this->db->affected_rows == 0) {
                return "Feil";
            }
            else {
                return ($this->registerUserInfo($this->db->insert_id) ? $this->db->insert_id : "Feil");
            }
        }     
    }
    
    function registerUserInfo($id) {
        $profilepicture = "image/defaultprofile.jpg";
        $userbg = "image/bg01.jpeg";
        $sql2 = "Insert INTO userinfo (userID,description,location,profilepicture,userbg)";
        $sql2 .= "Values ('$id',DEFAULT,DEFAULT,'$profilepicture','$userbg');";
        $resultat2 = $this->db->query($sql2);
        if (!$resultat2) {
            return false;
        }
        else {
            if ($this->db->affected_rows == 0) {
                return false;
            }
            else {
                return true;
            }
        }
    }
    
    function login($user) {
        $sql = "select password, userID from user where username = '" . $user->username . "';";
        $resultat = $this->db->query($sql);
        if (!$resultat) {
            return "DB error";
        }
        else {
            if ($this->db->affected_rows >= 1) {
                $radobjekt = $resultat->fetch_object();
                if ($this->sjekkPassord($user->password, $radobjekt->password)) {
                    return $radobjekt->userID;
                }
                else {
                    return "Wrong password";
                }
            }
            else {
                return "No such user";
            }
        }   
    }
    
    function sjekkPassord($sjekkpassord, $passordhash) {
        if (crypt($sjekkpassord, $passordhash) == $passordhash) {
            return true;
        }        
        else {
            return false;
        }
    }
    
    function getInfo($username) {
        $sql = "SELECT * FROM user, userinfo WHERE username = '" . $username ."' AND user.userID = userinfo.userID;";
        $resultat = $this->db->query($sql);
        if (!$resultat) {
            echo "Error";
        }
        else {
            $Objekt = $resultat->fetch_object();
            return $Objekt;
        }                    
    }
    
    function updateInfo($info) {
        $sql = "UPDATE userinfo SET description = '" . $info->description . "', location = '" . $info->location . "'";
        if (isset($info->profilepicture)) {
            $sql .= ", profilepicture = '" . $info->profilepicture . "'";
        }
        if (isset($info->userbg)) {
            $sql .= ", userbg = '" . $info->userbg . "'";
        }
        $sql .= " WHERE userID = '" . $info->userID . "';";             
        
        $resultat = $this->db->query($sql);
        if (!$resultat) {
            return "Feil";
        }
        else {
            if ($this->db->affected_rows == 0) {
                return "Feil insetting";
            }
            else {
                return "OK";
            }         
        }        
    }
    
    
    /*
    function hentAlleKunder()
    {
        $sql = "Select * from Kunde Left Join Poststed On Kunde.postnr = Poststed.postnr ";
        $resultat = $this->db->query($sql);
        $kunder = array();
        while($rad = $resultat->fetch_object())
        {
            $kunder[]=$rad;
        }
        return $kunder;
    }    */
}
/*
echo "test";
$user = new user();
$userDb = new userDB();
$user->username = "test";
$user->email = "test1";
$user->password = "test2";
echo $userDb->registerUser($user);
*/