<?php
    session_start();
    if (!empty($_GET['user'])) {
        if (isset($_SESSION['username'])) {
            $db = new mysqli("localhost", "root", "", "test");
            if ($db->connect_error) {
                die("Failed connection to Database: " . $db->connect - error);
            }
            $username = $db->real_escape_string($_GET['user']);
            if ($_SESSION['username'] !== $username) {
                echo "Access Denied. If this is your account <a href='../index.php'>Login to access this page</a>";
                die();                
            }
            else {
                $sql = "SELECT * FROM user, userinfo WHERE username = '" . $username ."' AND user.userID = userinfo.userID;";
                $resultat = $db->query($sql);
                if (!$resultat) {
                    echo "Error " . $db->error;
                }
                else {
                    $radObjekt = $resultat->fetch_object();
                    $userID = $radObjekt->userID;
                }                
            }
        }
    }
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="jquery-3.2.0.js"></script>
    <script src="js/bootstrap.min.js"></script>    
    
    <title>Yellr. It's what's happening.</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel='stylesheet' type='text/css' href='yellr.css'>
    <script src="yellr.js" type="text/javascript"></script>
</head>
<body>
    <div class="header">
        <div class="headerlinks">
            <table>
                <tr>
                    <td style="vertical-align: top">
                        <a href="../index.php"><img src="image/yellrlogo.png" width="20" height="20"></a>&nbsp;&nbsp;
                    </td>
                    <td>
                        <p><a href="../index.php">Home</a> &nbsp;&nbsp;&nbsp;&nbsp; <a href="about.php">About</a></p>
                    </td>
                </tr>
            </table>
        </div>
    </div>    
    <br><br><br>
    <div class='updateinfo'>
        <h2><?php echo $radObjekt->username;  ?></h2>
        <p>Joined on <?php echo $radObjekt->joindate;  ?></p>
        <form action="" method="POST" name='userinfo' id='userinfo'>
            <table>
                <tr>
                    <td>
                        <p>Description:</p>
                    </td>
                    <td>
                        <input type='text' name='description' id='description' class="form-control" value="<?php echo $radObjekt->description;  ?>">
                    </td>
                </tr>
                <tr>
                    <td>
                        <p>Location:</p>
                    </td>
                    <td>
                        <input type='text' name='location' id='location' class="form-control" value="<?php echo $radObjekt->location;  ?>">
                    </td>
                </tr>
                <tr>
                    <td>
                        <p>Email:</p>
                    </td>
                    <td>
                        <input type='text' name='email' id='email' class="form-control" value="<?php echo $radObjekt->email;  ?>">
                    </td>
                </tr>            
                <tr>
                    <td>
                        Profile Picture:
                    </td>
                    <td>
                        <input type="file" name="filstreng">
                    </td>
                </tr>
                <tr>
                    <td>
                        <p>Background Image:</p>
                    </td>
                    <td>
                        <input type="file" name="filstreng2">
                    </td>
                </tr>
                <tr>
                    <td colspan="2"><br>
                        <center><input type='submit' name='update' value='Update' class="btn btn-primary"></center>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    
   <?php
    if (isset($_POST['update'])) {
        $db = new mysqli("localhost", "root", "", "test");
        $db->autocommit(false);
        $description = $db->real_escape_string($_POST['description']);
        $location = $db->real_escape_string($_POST['location']);
        $email= $db->real_escape_string($_POST['email']);
        
        $bilde1 = false;
        $bilde2 = false;
        if ( ! empty($_FILES)) {
            if ($_FILES["filstreng"]["size"] != 0) {
                // Opplasting av profilbilde
                $temp_fil = $_FILES['filstreng']['tmp_name'];
                $filnavn = $_FILES['filstreng']['name'];
                $helt_filnavn = $db->real_escape_string("image/upload/" . $filnavn);
                // Sjekk om filen faktisk er bilde, og ikke falsk bilde
                $check = getimagesize($_FILES["filstreng"]["tmp_name"]);
                if (!$check) {
                    echo "Fil er ikke ett bilde, eller ingen bildefil valgt - Filopplasting avbrutt<br />";
                }
                else {
                    // Bildet er OK - Prøver å laste opp filen
                    $filsjekk = move_uploaded_file($temp_fil, $helt_filnavn);
                    if (!$filsjekk) {
                        echo "Kunne ikke lagre bildefilen - Eller ingen bildefil valgt<br />";
                        error_log("Kunne ikke lagre bildefilen - Eller ingen bildefil valgt", 3, "logg.txt");
                    }
                    else {
                        $bilde1 = true;
                    }
                }
            }
            if ($_FILES["filstreng2"]["size"] != 0) {
                // Opplasting av bakgrunnsbilde
                $temp_fil2 = $_FILES['filstreng']['tmp_name'];
                $filnavn2 = $_FILES['filstreng']['name'];
                $helt_filnavn2 = $db->real_escape_string("image/upload/" . $filnavn2);
                // Sjekk om filen faktisk er bilde, og ikke falsk bilde
                $check2 = getimagesize($_FILES["filstreng"]["tmp_name"]);
                if (!$check2) {
                    echo "Fil er ikke ett bilde, eller ingen bildefil valgt - Filopplasting avbrutt<br />";
                }
                else {
                    // Bildet er OK - Prøver å laste opp filen
                    $filsjekk2 = move_uploaded_file($temp_fil2, $helt_filnavn2);
                    if (!$filsjekk2) {
                        echo "Kunne ikke lagre bildefilen - Eller ingen bildefil valgt<br />";
                        error_log("Kunne ikke lagre bildefilen - Eller ingen bildefil valgt", 3, "logg.txt");
                    }
                    else {
                        $bilde2 = true;
                    }
                }
            }
        }
        // FORSKJELLIGE SQL AVHENGIG AV OM TING BLE OPPDATERT ELLER IKKE
        if ($bilde1 && $bilde2) {
            $sql = "UPDATE userinfo SET description = '" . $description . "', location = '" . $location . "', profilepicture = '" . $helt_filnavn . "', userbg = '" . $helt_filnavn2 . "' ";
            $sql .= "WHERE userID = '" . $radObjekt->userID . "';";
        }
        else if ($bilde1 && !$bilde2) {
            $sql = "UPDATE userinfo SET description = '" . $description . "', location = '" . $location . "', profilepicture = '" . $helt_filnavn . "' ";
            $sql .= "WHERE userID = '" . $radObjekt->userID . "';";            
        }
        else if (!$bilde1 && $bilde2) {
            $sql = "UPDATE userinfo SET description = '" . $description . "', location = '" . $location . "', userbg = '" . $helt_filnavn2 . "' ";
            $sql .= "WHERE userID = '" . $radObjekt->userID . "';";            
        }
        else {
            $sql = "UPDATE userinfo SET description = '" . $description . "', location = '" . $location . "' ";
            $sql .= "WHERE userID = '" . $radObjekt->userID . "';";            
        }
        
        echo $sql;
        $resultat = $db->query($sql);
        $ok = true;
        if (!$resultat) {
            $ok = false;
        }
        else {
            if ($db->affected_rows == 0) {
                $ok = false;
            }
            if ($ok) {
                // Alt ok
                $db->commit();
                header("Location: user.php?user=$username");
                exit();                
            }
            else {
                $db->rollback();
                echo "Could not write to database";
                error_log("Feil i insettning til database - Updateinfo", 3, "logg.txt");
            }            
        }
    }

   ?>
    
</body>
</html>
