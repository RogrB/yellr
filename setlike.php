<?php
    $db = new mysqli("localhost", "root", "", "test");
    if ($db->connect_error) {
        echo "Failed connection to database";
    }

    $likeID = $_GET['likeID'];
    $userID = $_GET['userID'];
    $sql = "INSERT INTO likes (yellNR,likedby) VALUES ('$likeID','$userID');";
    $resultat = $db->query($sql);
    if (!$resultat) {
        echo "Error " . $db->error;
    }
    else {
        echo true;
    }
    $db->close();

?>