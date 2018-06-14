<?php
session_start();
include_once '../Model/domeneModell.php';
include_once '../BLL/userLogikk.php';
header('Content-Type: application/json');


$userLogic = new userLogic();
$user = new user();
$user->username = $_POST['username'];
$user->email = $_POST['email'];
$user->password = $_POST['password'];

$ok = $userLogic->registerUser($user);
if ($ok == "OK") {
    $_SESSION['loggedin'] = true;
    $_SESSION['username'] = $user->username;
    echo json_encode("OK");
}
else {
    unset($_SESSION['loggedin']);
    unset($_SESSION['username']);
    echo json_encode("Feil");
}
