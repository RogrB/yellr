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
                $id = $this->db->insert_id;
                return ($this->registerUserInfo($id) ? $id : "Feil");
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
            return "Error";
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
    
    function createYell($user, $yell) {
        $date = date("d/m/Y");
        $likes = 0;
        $reyell = 0;
        $sql = "Insert INTO yell (userID,yellNR,yell,date,likes,reyell)";
        $sql .= " Values ('$user',DEFAULT,'" . mysqli_real_escape_string($this->db, $yell) . "','$date','$likes','$reyell');";
        $resultat = $this->db->query($sql);
        if (!$resultat) {
            return $this->db->error;
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
    
    function getYells($userID, $sessionID) {
        $sql = "SELECT * FROM yell,userinfo WHERE userinfo.userID = yell.userID AND yell.userID = '" . $userID . "' ORDER BY yellNR desc;";
        $resultat = $this->db->query($sql);
        if (!$resultat) {
            return "Feil";
        }
        else {
            if ($this->db->affected_rows == 0) {
                return "Feil bruker";
            }
            else {
                $antallrader = $this->db->affected_rows;
                $output = $this->arrangeYells($resultat, $antallrader, $sessionID);
                return $output;
            }       
        }
    }
    
    function arrangeYells($object, $antallrader, $sessionID) {
        $jsondata = array();
        
        for ($i = 0; $i < $antallrader; $i++) {
            $yell = new yell();
            $yellrad = $object->fetch_object();
            $yell->userID = $yellrad->userID;
            $yell->yellNR = $yellrad->yellNR;
            $yell->yell = $yellrad->yell;
            $yell->date = $yellrad->date;

            $yell->likes = $this->getLikes($yell->yellNR);

            $yell->reyell = $yellrad->reyell;
            $yell->likesjekk = $this->sjekkLike($yell->yellNR, $sessionID);
            $yell->deletesjekk = ($yellrad->userID == $sessionID ? true : false);
            $yell->profilepicture = $yellrad->profilepicture;

            $jsondata[] = $yell;  
        }
        return $jsondata;
    }
    
    function getLikes($yellID) {
        $sql = "select count(*) AS antall from likes where yellNR = '" . $yellID . "';";
        $resultat = $this->db->query($sql);
        if (!$resultat) {
            return 0;
        }
        else {
            $objekt = $resultat->fetch_object();
            return $objekt->antall;
        }          
    }
    
    function sjekkLike($yellNR, $userID) {
        // Sjekker om den spesifikke yellen er liket av brukeren
        $sql = "select * from likes where yellNR = '$yellNR' AND likedby = '$userID';";
        $resultat = $this->db->query($sql);
        if (!$resultat) {
            return false;
        }
        else {
            if ($this->db->affected_rows == 0) {
                return false;
            } else {
                return true;
            }
        }         
    }
    
    function getUserID($username) {
        $sql = "select userID from user where username = '$username';";
        $resultat = $this->db->query($sql);
        if (!$resultat) {
            return "Feil";
        }
        else {
            if ($this->db->affected_rows == 0) {
                return "Feil inset";
            } else {
                $objekt = $resultat->fetch_object();
                return $objekt->userID;
            }
        }          
    }
    
    function setLike($yell, $user) {
        $sql = "INSERT INTO likes (yellNR,likedby) VALUES ('$yell','$user');";
        $resultat = $this->db->query($sql);
        if (!$resultat) {
            return "Feil";
        }
        else {
            return "OK";
        }     
    }
    
    function unLike($yell, $user) {
        $sql = "delete from likes where yellNR = '$yell' AND likedby = '$user';";
        $resultat = $this->db->query($sql);
        if (!$resultat) {
            return "Feil";
        }
        else {
            return "OK";
        }     
    }
    
    function deleteYell($yell) {
        $sql = "delete from yell where yellNR = '" . $yell . "';";
        $resultat = $this->db->query($sql);
        if (!$resultat) {
            return "Feil";
        }                 
        else {
            return "OK";
        }
    }
    
    function getYell($yellNR) {
        $sql = "SELECT yell,username,date,profilepicture FROM yell, user, userinfo WHERE yell.userID = user.userID AND user.userID = userinfo.userID AND yellNR = '" . $yellNR . "';";
        $resultat = $this->db->query($sql);
        if (!$resultat) {
            return "Error";
        }
        else {
            $objekt = $resultat->fetch_object();
            return $objekt;
        }
    }    
    
}
