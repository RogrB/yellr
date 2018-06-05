<?php
    session_start();
    if (!$_SESSION['loggedin']) {
        echo "You're not logged in. ";
        echo "<a href='index.php'>Login</a>";
        die();
    }
    if (!empty($_GET['user'])) {
        if (isset($_SESSION['username'])) {
            $db = new mysqli("localhost", "root", "", "test");
            $username = $db->real_escape_string($_GET['user']);
            if ($db->connect_error) {
                die("Failed connection to Database: " . $db->connect - error);
            }            
            if ($_SESSION['username'] !== $username) {
                echo "Access Denied. If this is your account <a href='index.php'>Login to access this page</a>";
                die();                
            }
            else {
                $sql = "SELECT * FROM user, userinfo WHERE user.username = '" . $username . "' AND user.userID = userinfo.userID;";
                $resultat = $db->query($sql);
                if (!$resultat) {
                    echo "Error " . $db->error;
                }
                else {
                    $radObjekt = $resultat->fetch_object();
                } 
            }
        }        
    }
    
    if (isset($_POST['logout'])) {
        unset($_SESSION['username']);
        unset($_SESSION['loggedin']);
        unset($_SESSION['userID']);
        header("Location: index.php");
        exit();  
    }

    if (isset($_POST['submit'])) {
        $db = new mysqli("localhost", "root", "", "test");
        if ($db->connect_error) {
            die("Failed connection to database: " . $db->connect - error);
        }
        $db->autocommit(FALSE);
        $yell = $db->real_escape_string($_POST['yell']);
        $userID = $radObjekt->userID;
        $date = date("d/m/Y");
        $likes = 0;
        $reyell = 0;
        $sql = "Insert INTO yell (userID,yellNR,yell,date,likes,reyell)";
        $sql .= " Values ('$userID',DEFAULT,'$yell','$date','$likes','$reyell');";
        $ok = true;
        $resultat = $db->query($sql);
        if (!$resultat) {
            $ok = false;
        }
        else {
            if ($db->affected_rows == 0) {
                $ok = false;
            }
        }
        if ($ok) {
            // ALT OK
            $db->commit();
            header("Location: yellr.php?user=$username");
            exit();                
        }
        else {
            $db->rollback();
            echo "Could not write to database";
            error_log("Feil i insettning til database - Yell", 3, "logg.txt");            
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
    <div class="header">
        <div class='headerwrap'>
            <div class="headerlinks">
                <table>
                    <tr>
                        <td style="vertical-align: top">
                            <a href="index.php"><img src="image/yellrlogo.png" width="20" height="20"></a>&nbsp;&nbsp;
                        </td>
                        <td>
                            <p><a href="index.php">Home</a> &nbsp;&nbsp;&nbsp;&nbsp; <a href="about.php">About</a></p>
                        </td>
                    </tr>
                </table>
            </div>
            <div class='logout'>
                <table>
                    <tr>
                        <td style="vertical-align: bottom">
                            <p>Logged in as <a href="<?php echo 'yellr.php?user=' . $username;  ?>"><?php echo $_SESSION['username']; ?></a></p>
                        </td>
                        <td>
                            &nbsp;&nbsp;&nbsp;&nbsp;
                        </td>
                        <td style="vertical-align:top">
                            <form action="" method="POST"><input type="submit" name="logout" value="Log Out" class="btn btn-warning"></form>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>  
    
    
    <div class='wrapper'>
        <div class='userinfo'>
            <?php
            echo "</h2>";
            echo "<br>";
            echo $radObjekt->description;
            echo "<br><br><br>";
            echo "<i class='glyphicon glyphicon-map-marker'> </i> ";
            echo $radObjekt->location;
            echo "<br><br>";
            echo "Joined: ";
            echo $radObjekt->joindate;
            echo "<br><br>";
            echo $radObjekt->email;
            echo "<br><br><br>";
            echo "<center><a href='updateinfo.php?user=$username' class='btn btn-primary'>Update Info</a></center>";
            echo "<br><br>";
            ?>
        </div>
        <div class='usercontent'>
            <?php
            echo '<img src="' . $radObjekt->profilepicture . '" alt="Profile Picture" id="profilePicture" class="img-circle" height="150" >';
            echo "<table><tr><td><h1><a href='yellr.php?user=$username'><i>$username </i></a></h1></td><td>&nbsp;&nbsp;&nbsp;&nbsp;<a href='yellr.php?user=$username'> My yells</a></td></tr></table>";
            ?>
            <br>
            <form action='' method="POST">
                <textarea class="form-control" placeholder="Yell!" rows='8' cols="50" name='yell' id='yell'></textarea><br>
                <center><input type='submit' name='submit' value='Yell!' id='yellbtn' class='btn btn-primary'></center>
            </form>
        </div>
    </div>
 

</body>
</html>

