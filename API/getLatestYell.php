<?php
include_once '../BLL/userLogikk.php';

$user = new userLogic();
$yell = $user->getLatestYell();
echo json_encode($yell);


