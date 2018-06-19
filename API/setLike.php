<?php
session_start();
include_once '../Model/domeneModell.php';
include_once '../BLL/userLogikk.php';
header('Content-Type: application/json');

if(!isset($_SESSION['userID'])) {
    echo json_encode("Feil bruker");
    die();
}
$user = $_SESSION['userID'];
$userLogic = new userLogic();

$yell = $_POST['yellNR'];

$ok = $userLogic->setLike($yell, $user);
echo json_encode($ok);


