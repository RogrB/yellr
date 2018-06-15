<?php
session_start();
header('Content-Type: application/json');
include_once '../BLL/userLogikk.php';

$userlogikk = new userLogic();
if(!isset($_SESSION['loggedin'])) {
    echo json_encode("Feil");
    die();
}
else {
    if ($_SESSION['username'] === $_POST['username']) {
        $user = $userlogikk->getInfo($_SESSION['username']);
        echo json_encode($user);
    }
    else {
        echo json_encode("Wrong user");
    }
}
