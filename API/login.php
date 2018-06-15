<?php
session_start();
include_once '../Model/domeneModell.php';
include_once '../BLL/userLogikk.php';
header('Content-Type: application/json');

$userLogic = new userLogic();
$user = new user();
$user->username = $_POST['username'];
$user->password = $_POST['password'];

$ok = $userLogic->login($user);
if ($ok == "DB error") {
    $this->clearSession();
    echo json_encode("DB error");    
}
else if ($ok == "Wrong password") {
    $this->clearSession();
    echo json_encode("Wrong password");
}
else if ($ok == "No such user") {
    $this->clearSession();
    echo json_encode("No such user");    
}
else {
    $_SESSION['loggedin'] = true;
    $_SESSION['username'] = $user->username;
    $_SESSION['userID'] = (int)$ok;
    echo json_encode("OK");
}

function clearSession() {
    unset($_SESSION['loggedin']);
    unset($_SESSION['username']);
    unset($_SESSION['userID']);    
}