<?php
    $db = new mysqli("localhost", "root", "", "test");
    if ($db->connect_error) {
        die("Kunne ikke koble til db: " . $db->connect - error);
    }
    $yellID = $_GET['yellID'];
    $userID = $_GET['userID'];
    $sql = "select * from likes where yellNR = '$yellID' AND likedby = '$userID';";
    $resultat = $db->query($sql);
    if (!$resultat) {
        echo "Error " . $db->error;
    }
    else {
        if ($db->affected_rows == 0) {
            echo false;
        } else {
            echo true;
        }
    }
    $db->close();
?>