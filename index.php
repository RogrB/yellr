<?php
    session_start();
    if (isset($_POST['register'])) {
        // MÅ INPUTVALIDERES OG SJEKKE AT BRUKERNAVN IKKE FINNES FRA FØR
        // SET COOKIE FOR 'REMEMBER ME'
        // Redirecte til feilmeldingsside hvis loginfail
        $db = new mysqli("localhost", "root", "", "test");
        if ($db->connect_error) {
            die("Failed connection to database: " . $db->connect - error);
        }
        $db->autocommit(FALSE);
        $username = $db->real_escape_string($_POST['regusername']);
        $email = $db->real_escape_string($_POST['regemail']);
        $password = $db->real_escape_string($_POST['regpassword']);

        $salt = uniqid(mt_rand(), true);
        $hashpassord = crypt($password, '$6$' . $salt);
        $date = date("d/m/Y");
        $sql = "Insert INTO user (userID,username,email,password,joindate)";
        $sql .= "Values (DEFAULT,'$username','$email','$hashpassord','$date');";
        $ok = true;
        $resultat = $db->query($sql);
        if (!$resultat) {
            $ok = false;
        }
        else {
            if ($db->affected_rows == 0) {
                $ok = false;
            }
            else {
                $id = $db->insert_id;
                $profilepicture = "image/defaultprofile.jpg";
                $userbg = "image/bg01.jpeg";
                $sql2 = "Insert INTO userinfo (userID,description,location,profilepicture,userbg)";
                $sql2 .= "Values ('$id',DEFAULT,DEFAULT,'$profilepicture','$userbg');";
                $resultat2 = $db->query($sql2);
                if (!$resultat2) {
                    $ok = false;
                }
                else {
                    if ($db->affected_rows == 0) {
                        $ok = false;
                    }
                }
            }
            if ($ok) {
                // Alt ok
                $db->commit();
                $_SESSION['loggedin'] = true;
                $_SESSION['username'] = $username;
                header("Location: user.php?user=$username&newuser=true");
                exit();                
            }
            else {
                $db->rollback();
                echo "Could not write to database";
                error_log("Feil i insettning til database - Bruker reg", 3, "logg.txt");
            }
        }
    }

    if (isset($_POST['login'])) {
        $db = new mysqli("localhost", "root", "", "test");
        if ($db->connect_error) {
            die("Failed connection to DB: " . $db->connect - error);
        }
        $sjekkpassord = $db->real_escape_string($_POST['password']);
        $username = $db->real_escape_string($_POST['username']);
        $sql = "select password, userID from user where username = '" . $username . "';";
        $resultat = $db->query($sql);
        if (!$resultat) {
            echo "Error " . $db->error;
        }
        else {
            $_SESSION['loggedin'] = false;
            if ($db->affected_rows >= 1) {
                $radobjekt = $resultat->fetch_object();
                $passordhash = $radobjekt->password;
                $userID = $radobjekt->userID;
                if (crypt($sjekkpassord, $passordhash) == $passordhash) {
                    $ok = true;
                }
                if ($ok) {
                    // Passord OK
                    $_SESSION['loggedin'] = true;
                    $_SESSION['username'] = $username;
                    $_SESSION['userID'] = $userID;
                    header("Location: user.php?user=$username");
                    exit();
                } else {
                    // Passord Feil
                    echo "Wrong Password";
                }
            }
            else {
                echo "Couldn't find user $username";
            }
        }
        $db->close();
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
                        <a href="index.php"><img src="image/yellrlogo.png" width="20" height="20"></a>&nbsp;&nbsp;
                    </td>
                    <td>
                        <p><a href="index.php">Home</a> &nbsp;&nbsp;&nbsp;&nbsp; <a href="about.php">About</a></p>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <div class="content">
        <div class='content'>
            <div class='innercontent'>
                <table>
                    <tr>
                        <td>
                            <img src='image/yellrlogo.png' height="288" width="265">&nbsp;&nbsp;&nbsp;&nbsp;
                        </td>
                        <td>
                            <h1 class='titleh1'>Welcome to Yellr.</h1>
                            <br>
                            <p id='titlep'>Connect with your friends - and other<br>
                                fascinating people. Get in-the-moment updates<br>
                                on the things that interests you. And watch <br>
                            events unfold, in real time, from every angle.</p>
                        </td>
                        <td>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        </td>
                        <td><br><br>
                            <div class='formdiv1'>
                                <table>
                                    <form action='' method='POST'>
                                        <tr>
                                            <td>
                                                <input type='text' name='username' id='username' class="form-control" placeholder="Username">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <input type='password' name='password' id='password' class="form-control" placeholder="Password">
                                            </td>
                                            <td>
                                                &nbsp;&nbsp;<input type='submit' name='login' value='Log in' class="btn btn-primary">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div id='remember'><input type='checkbox' name='remember' value='true' checked="checked"> Remember me</div>
                                            </td>
                                        </tr>
                                    </form>
                                </table>
                            </div>
                            <br>
                            <div class='formdiv2'>
                                <form action='' method='POST'>
                                    <input type='text' name='regusername' id='regusername' placeholder="Username" class="form-control">
                                    <input type='text' name='regemail' id='regemail' placeholder="Email" class="form-control">
                                    <input type='password' name='regpassword' id='regpassword' placeholder="Password" class="form-control">
                                    <center><input type='submit' name='register' value='Sign up for Yellr' class="btn btn-primary"></center>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                        </td>
                        <td>
                            <?php
                                // LATEST YELL
                                $db = new mysqli("localhost", "root", "", "test");
                                if ($db->connect_error) {
                                    die("Failed connection to Database: " . $db->connect - error);
                                }             
                                $sql = "SELECT * FROM yell, user WHERE yell.userID = user.userID ORDER BY yellNR DESC LIMIT 1";                                
                                $resultat = $db->query($sql);
                                if (!$resultat) {
                                    echo "Error " . $db->error;
                                }
                                else {
                                    $radObjekt = $resultat->fetch_object();
                                }                                
                                echo "<h1 class='titleh1'>Latest Yell:</h1>";
                                echo "<div id='latestyell'>";
                                echo "<p class='whitetext'>" . $radObjekt->yell . "</p>";
                                echo "By <a class='whitetext' href='yellr.php?user=" . $radObjekt->username . "'>" . $radObjekt->username . "</a> " . $radObjekt->date . "";
                                echo "</div>";
                            ?>
                                
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan='4' style='text-align: center'><br><br>
                            <p class='disctext'>Yellr is totally not a ripoff of twitter or anything.. Really not at all.</p>
                        </td>
                    </tr>
                </table>
            </div>           
        </div>
    </div> 
    

</body>
</html>

