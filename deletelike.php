<?php
    $db = new mysqli("localhost", "root", "", "test");
    if ($db->connect_error) {
        die("Kunne ikke koble til db: " . $db->connect - error);
    }
    $yellID = $_GET['likeID'];
    $userID = $_GET['userID'];
    $sql = "delete from likes where yellNR = '$yellID' AND likedby = '$userID';";
    $resultat = $db->query($sql);
    if (!$resultat) {
        echo false;
    }
    else {
        if ($db->affected_rows == 0) {
            echo false;
        }
        else {
            echo true;
        }
    }
    $db->close();    

?>