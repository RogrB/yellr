<?php
session_start();
include_once '../Model/domeneModell.php';
include_once '../BLL/userLogikk.php';
header('Content-Type: application/json');

if(!isset($_SESSION['userID'])) {
    echo json_encode("userID error");
    die();
}
$user = $_SESSION['userID'];
$userLogic = new userLogic();

$yell = $_POST['yell'];

$ok = $userLogic->createYell($user, $yell);
echo json_encode($ok);
