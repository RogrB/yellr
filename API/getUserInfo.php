<?php
include_once '../BLL/userLogikk.php';
header('Content-Type: application/json');

$userLogic = new userLogic();
$user = $_POST['user'];

$ok = $userLogic->getInfo($user);
echo json_encode($ok);

