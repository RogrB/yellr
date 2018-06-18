<?php
session_start();

if (isset($_SESSION['loggedin'])) {
    unset($_SESSION['loggedin']);
    unset($_SESSION['username']);
    unset($_SESSION['userID']);
    echo json_encode("OK");
}
else {
    echo json_encode("Failed logout");
}
