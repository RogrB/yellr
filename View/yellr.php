<?php
    session_start();
    if (!empty($_GET['user'])) {
        $db = new mysqli("localhost", "root", "", "test");
        if ($db->connect_error) {
            die("Failed connection to Database: " . $db->connect - error);
        }
        $username = $db->real_escape_string($_GET['user']);
        $sql = "SELECT * FROM user, userinfo WHERE username = '" . $username ."' AND user.userID = userinfo.userID;";
        $resultat = $db->query($sql);
        
        if (!$resultat) {
            // FOR Å STOPPE SIDELOGIN HVIS BRUKER IKKE FINNES I DB - FUNKER IKKE
            echo "Can't find user in database";
            die();
        }
        else {
            $radObjekt = $resultat->fetch_object();
            $userID = $radObjekt->userID;
        }
        // SJEKKER OM BRUKER EIER SIDEN - FOR Å KUNNE SLETTE
        $deletesjekk = false;
        $likesjekk = "";
        if (isset($_SESSION['username'])) {
            if ($_SESSION['username'] === $username) {
                $deletesjekk = true;
            }
            if (isset($_SESSION['userID'])) {
                $likesjekk = $_SESSION['userID'];
            }
        }
    }
    else {
        echo "Undefined user";
        die();
    }
    if (isset($_POST['logout'])) {
        unset($_SESSION['username']);
        unset($_SESSION['loggedin']);
        unset($_SESSION['userID']);
        header("Location: ../index.php");
        exit();  
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
<body onload="reGet();">
    <?php
    // DELETE YELL - NÅR BRUKER TRYKKER PÅ SLETT YELL KNAPP
        if (!empty($_GET['deleteyell'])) {
            if ($_SESSION['username'] === $username) {   
                $deleteyell = filter_var($_GET['deleteyell'], FILTER_SANITIZE_STRING);
                $sql2 = "delete from yell where yellNR = '" . $deleteyell . "';";
                $resultat3 = $db->query($sql2);
                if (!$resultat3) {
                    echo "Error " . $db->error;
                    error_log("Erorr " . $db->error, 3, "logg.txt");
                }                 
            }
            else {            
                echo "You are trying to delete a Yell that is not connected to your account.";
                die(); 
            }
        }
    ?>       
    <div class="header">
        <div class='headerwrap'>
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
            <div class='logout'>
                <?php
                    if (isset($_SESSION['username'])) {
                        echo "<table>";
                        echo "<tr>";
                        echo '<td>';
                        echo "Logged in as <a href='user.php?user=" . $_SESSION['username'] . "'>" . $_SESSION['username'] . "</a>";
                        echo "</td>";
                        echo "<td>";
                        echo "&nbsp;&nbsp;&nbsp;&nbsp;";
                        echo "</td>";
                        echo '<td style="vertical-align:top">';
                        echo '<form action="" method="POST"><input type="submit" name="logout" value="Log Out" class="btn btn-warning"></form>';
                        echo "</td>";
                        echo "</tr>";
                        echo "</table>";
                    }
                ?>
            </div>
        </div>
    </div>  
        

    <div class="userBG">
        
    </div>
    <div class="profilePictureDiv">
        <img src="<?php echo $radObjekt->profilepicture; ?>" alt="Profile Picture" id="profilePicture" class="img-circle" height="150" >
    </div>
    <div class="stickyheader">
        <div class="yellStats">
            asdf
        </div>
        <div class="rightfollow">
            <button class="btn btn-primary">Follow</button>
        </div>
    </div>
    
    <div class="yellBG">
        <div class="wrapper">
            <div class="yellInfo"><br>
                <h2><?php echo $radObjekt->username;
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
                        echo "<br><br>";
                        // Update Info - Hvis det er brukerens side
                        if (isset($_SESSION['username'])) {
                            if ($_SESSION['username'] == $username) {
                                echo "<a href='updateinfo.php?user=$username' class='btn btn-primary'>Update Info</a>";
                                echo "<br><br>";
                                echo "<a href='user.php?user=$username' class='btn btn-primary'>Create a Yell!</a>";
                            }
                        }
                    ?>
            </div>
            <div class="yells" id="yells">

            </div>
            <div class="floatContent">
                Lorem ipsum osv osv osv .
                
            </div>
        </div>
    </div>
    <script type="text/javascript">
    function like(yell, user) {
        var yell = yell;
        var user = user;
        var getUrl = "setlike.php?likeID=" + yell + "&userID=" + user;
        $.ajax({
        url:getUrl,
        success:function(resultat) {
            if (resultat) {
                // LIKE REGISTRERT I DB
                
            }
            }
        });        
    }
    
    function unlike(yell, user) {
        var yell = yell;
        var user = user;
        var getUrl = "deletelike.php?likeID=" + yell + "&userID=" + user;
        $.ajax({
        url:getUrl,
        success:function(resultat) {
            if (resultat) {
                // LIKE SLETTET FRA DB
                
            }
            }
        });        
    }

    function getYell(user, deletesjekk, likesjekk) {
        var getUrl = "getyells.php?userID=" + user + "&deletesjekk=" + deletesjekk + "&likesjekk=" + likesjekk;
        $.ajax({
        url:getUrl,
        success:function(resultat) {
            var yell = JSON.parse(resultat);
            var utdata = "";
            if (yell.userID !== null) {
                // SKRIV UT FORMAT
                for (var i = 0; i < yell.length; i++) {
                    utdata += "<a href='yellr.php?user=<?php echo $username; ?>'><?php echo $username ?>  </a>";
                    utdata += yell[i].date;
                    if (yell[i].deletesjekk) {
                        utdata += "&nbsp;&nbsp;&nbsp;&nbsp;";
                        utdata += "<a href='yellr.php?user=<?php echo $username; ?>&deleteyell=" + yell[i].yellNR + "' onclick=\"javascript:return confirm('Are you sure you want to delete this yell?');\"><i class='glyphicon glyphicon-trash' alt='Delete'></i></a>";                        
                    }
                    utdata += "<br><br>";
                    utdata += yell[i].yell;
                    utdata += "<br><br>";
                    if (yell[i].likesjekk) {
                        utdata += "<i class='glyphicon glyphicon-heart' style='color: red; cursor: pointer;' alt='Unlike' onclick='unlike(" + yell[i].yellNR + ", <?php echo $likesjekk; ?>), reGet();'> </i> ";
                    }
                    else {
                        utdata += "<i class='glyphicon glyphicon-heart' style='cursor: pointer;' alt='Like' onclick='like(" + yell[i].yellNR + ", <?php echo $likesjekk; ?>), reGet();'> </i> ";
                    }
                    utdata += yell[i].likes;
                    utdata += "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                    utdata += "<i class='glyphicon glyphicon-retweet' style='cursor: pointer;' alt='Reyells'></i> ";
                    utdata += yell[i].reyell;
                    utdata += "<hr>";
                }
            }
            else {
                utdata = "Could not find user";
            }
            $("#yells").html(utdata);
            }
        });          
    }
    
    function reGet() {
        getYell('<?php echo $userID; ?>','<?php if ($deletesjekk) { echo true; } else { echo false; }  ?>','<?php echo $likesjekk;  ?>');
    }
    </script>
</body>
</html>

