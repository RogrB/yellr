<?php
session_start();
include_once '../BLL/userLogikk.php';
header('Content-Type: application/json');

if(!isset($_SESSION['username'])) {
    echo json_encode("Feil");
}
else {
    echo json_encode($_SESSION['username']);
}
