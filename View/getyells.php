<?php
    class yell {

        public $userID;
        public $yellNR;
        public $yell;
        public $date;
        public $likes;
        public $reyell;
        public $deletesjekk;
        public $likesjekk;

    }

    $db = new mysqli("localhost", "root", "", "test");
    if ($db->connect_error) {
        echo "Failed connection to database";
        die();
    }
    $userID = $_GET['userID'];    
    $deletesjekk = null;
    $likesjekk = null;
    
    if (isset($_GET['likesjekk'])) {
        $likesjekk = $_GET['likesjekk'];
    }
    if (isset($_GET['deletesjekk'])) {
        $deletesjekk = $_GET['deletesjekk'];
    }
    
    $sql = "SELECT * FROM yell WHERE userID = '" . $userID . "' ORDER BY yellNR desc;";
    $resultat = $db->query($sql);
    if (!$resultat) {
        echo "Couldn't find yell";
        die();
    }
    else {
        if ($db->affected_rows == 0) {
            echo "Could not find any yells";
            die();
        }
        else {
            $jsondata = array();
            $antallrader = $db->affected_rows;
            for ($i = 0; $i < $antallrader; $i++) {
                // starter loop og setter nye variabler fra sql query
                $yell = new yell();
                $yellrad = $resultat->fetch_object();
                $yell->userID = $yellrad->userID;
                $yell->yellNR = $yellrad->yellNR;
                $yell->yell = $yellrad->yell;
                $yell->date = $yellrad->date;
                
                // Sjekker antall likes en yell har fått
                $sql3 = "select count(*) AS antall from likes where yellNR = '" . $yellrad->yellNR . "';";
                $resultat3 = $db->query($sql3);
                if (!$resultat3) {
                    $yell->likes = null;
                }
                else {
                    $objekt = $resultat3->fetch_object();
                    $yell->likes = $objekt->antall;
                }  
                $yell->reyell = $yellrad->reyell;
                $yell->deletesjekk = $deletesjekk;

                // Sjekker om den spesifikke yellen er liket av brukeren
                $sql2 = "select * from likes where yellNR = '$yellrad->yellNR' AND likedby = '$likesjekk';";
                $resultat2 = $db->query($sql2);
                if (!$resultat2) {
                    $yell->likesjekk = null;
                }
                else {
                    if ($db->affected_rows == 0) {
                        $yell->likesjekk = false;
                    } else {
                        $yell->likesjekk = true;
                    }
                }                
                
                // Legger til i array
                $jsondata[] = $yell;
            }

            echo json_encode($jsondata);
        }
    }
    $db->close();
?>