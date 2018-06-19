<?php
session_start();
include_once '../Model/domeneModell.php';
include_once '../BLL/userLogikk.php';
header('Content-Type: application/json');

if(!isset($_SESSION['userID'])) {
    $sessionID = 0;
}
else {
    $sessionID = $_SESSION['userID'];
}

$username = $_POST['userID'];
$userLogic = new userLogic();

$userID = $userLogic->getUserID($username);

$ok = $userLogic->getYells($userID, $sessionID);
echo json_encode($ok);

