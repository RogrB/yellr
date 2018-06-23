<?php
session_start();
include_once '../Model/domeneModell.php';
include_once '../BLL/userLogikk.php';
header('Content-Type: application/json');

if(!isset($_SESSION['userID'])) {
    echo json_encode("Feil bruker");
    die();
}
$follower = $_SESSION['userID'];
$username = $_POST['user'];
$userLogic = new userLogic();

$userID = $userLogic->getUserID($username);

$ok = $userLogic->follow($userID, $follower);
echo json_encode($ok);
