<?php
session_start();
include_once '../Model/domeneModell.php';
include_once '../BLL/userLogikk.php';
header('Content-Type: application/json');

if (!isset($_SESSION['userID'])) {
    echo json_encode("Feil id");
    die();
}
$userLogic = new userLogic();
$info = new description();
$info->userID = $_SESSION['userID'];
$info->description = $_POST['description'];
$info->location = $_POST['location'];

if (!empty($_FILES)) {
    if (isset($_FILES['profilepicture']) && $_FILES["profilepicture"]["size"] != 0) {
        $temp_fil = $_FILES['profilepicture']['tmp_name'];
        $filnavn = $_FILES['profilepicture']['name'];
        $helt_filnavn = "../view/image/upload/" . $_SESSION['username'] . "/" . $filnavn;
        
        // Sjekk om filen faktisk er bilde, og ikke falsk bilde
        $check = getimagesize($_FILES["profilepicture"]["tmp_name"]);
        if (!$check) {
            echo json_encode("Feil bilde profil");
        }
        else {
            // Bildet er OK - Prøver å laste opp filen
            if (handleImage($temp_fil, $helt_filnavn, $filnavn, $info)) {
                $info->profilepicture = "image/upload/" . $_SESSION['username'] . "/" . $filnavn;
            }
        }
    }
    if (isset($_FILES['userbg']) && $_FILES["userbg"]["size"] != 0) {
        $temp_fil = $_FILES['userbg']['tmp_name'];
        $filnavn = $_FILES['userbg']['name'];
        $helt_filnavn = "../view/image/upload/" . $_SESSION['username'] . "/" . $filnavn;
        
        // Sjekk om filen faktisk er bilde, og ikke falsk bilde
        $check = getimagesize($_FILES["userbg"]["tmp_name"]);
        if (!$check) {
            echo json_encode("Feil bilde bg");
        }
        else {
            // Bildet er OK - Prøver å laste opp filen
            if (handleImage($temp_fil, $helt_filnavn, $filnavn, $info)) {
                $info->userbg = "image/upload/" . $_SESSION['username'] . "/" . $filnavn;
            }
        }
    }
}

$ok = $userLogic->updateInfo($info);
echo json_encode($ok);

function handleImage($tmp, $helnavn, $filnavn, $info) {
    if (!file_exists('../view/image/upload/' . $_SESSION['username'])) {
        mkdir('../view/image/upload/' . $_SESSION['username'], 0777, true);
    }
    $filsjekk = move_uploaded_file($tmp, $helnavn);
    if (!$filsjekk) {
        return false;
    }
    else {
        return true;
    }    
}
