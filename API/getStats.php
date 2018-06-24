<?php
include_once '../Model/domeneModell.php';
include_once '../BLL/userLogikk.php';
header('Content-Type: application/json');

$user = $_POST['user'];
$userLogic = new userLogic();

$userID = $userLogic->getUserID($user);


$ok = $userLogic->getStats($userID);
echo json_encode($ok);

