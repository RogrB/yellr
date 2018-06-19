<?php
session_start();
header('Content-Type: application/json');

if(!isset($_SESSION['userID'])) {
    echo json_encode("Feil");
}
else {
    echo json_encode($_SESSION['userID']);
}
