<?php
session_start();
include_once '../Model/domeneModell.php';
include_once '../BLL/userLogikk.php';
header('Content-Type: application/json');

if(!isset($_SESSION['userID'])) {
    echo json_encode("Feil bruker");
    die();
}

$yellNR = $_POST['yellNR'];
$userLogic = new userLogic();

$ok = $userLogic->getYell($yellNR);
echo json_encode($ok);

