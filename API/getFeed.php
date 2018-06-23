<?php
session_start();
include_once '../Model/domeneModell.php';
include_once '../BLL/userLogikk.php';
header('Content-Type: application/json');

if(!isset($_SESSION['userID'])) {
    echo json_encode("Feil bruker");
    die();
}

$userID = $_SESSION['userID'];

$userLogic = new userLogic();

$ok = $userLogic->getFeed($userID);
echo json_encode($ok);



