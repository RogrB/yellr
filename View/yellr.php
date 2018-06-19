<?php
    session_start();
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
    <script type="text/javascript">
    $(function() {
        getYell();
        getInfo();
        getLoginInfo();     
    });        
       
    function like(yell) {
        var url = "../API/setLike.php";
        var send = { yellNR : yell };

        $.post(url,send,function(data) {
            if(data === "Feil bruker") {
                console.log("Please log in to like a yell");
            }
            else if (data === "Feil") {
                console.log("Failed to register like");
            }
            else {
                // Like registrert i DB
                getYell();
            }
        })
        .fail(function(data) {
            console.log("Failed API call to like yell");
            console.log(data);
        });
    }        
    
    function unlike(yell) {
        var url = "../API/unLike.php";
        var send = { yellNR : yell };

        $.post(url,send,function(data) {
            if(data === "Feil bruker") {
                console.log("Please log in to unlike a yell");
            }
            else if (data === "Feil") {
                console.log("Failed to register unlike");
            }
            else {
                // Like registrert i DB
                getYell();
            }
        })
        .fail(function(data) {
            console.log("Failed API call to unlike yell");
            console.log(data);
        });
    }        

    function getYell() {
        var username = "<?php echo $_GET['user']; ?>";
        var url = "../API/getYells.php";
        var send = { userID : username };

        $.post(url,send,function(yell) {
            //var yell = JSON.parse(resultat);
            var utdata = "";
            if (yell.userID !== null) {
                // SKRIV UT FORMAT
                for (var i = 0; i < yell.length; i++) {
                    utdata += "<a href='yellr.php?user=" + username + "'>" + username + " </a>";
                    utdata += yell[i].date;
                    if (yell[i].deletesjekk) {
                        utdata += "&nbsp;&nbsp;&nbsp;&nbsp;";
                        utdata += "<i class='glyphicon glyphicon-trash' alt='Delete'  style='cursor: pointer;' ";
                        utdata += "onclick=\"javascript:return confirm('Are you sure you want to delete this yell?'), deleteYell(" + yell[i].yellNR + ");\"></i>";                        
                    }
                    utdata += "<br><br>";
                    utdata += yell[i].yell;
                    utdata += "<br><br>";
                    if (yell[i].likesjekk) {
                        utdata += "<i class='glyphicon glyphicon-heart' style='color: red; cursor: pointer;' alt='Unlike' onclick='unlike(" + yell[i].yellNR + ");'> </i> ";
                    }
                    else {
                        utdata += "<i class='glyphicon glyphicon-heart' style='cursor: pointer;' alt='Like' onclick='like(" + yell[i].yellNR + ");'> </i> ";
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
        })
        .fail(function(data) {
            console.log("Failed API call to get yells");
            console.log(data);
            $("#yells").html("Failed API call to get yells");
        });   
    }
        
    function deleteYell(yellNR) {
        var url = "../API/deleteYell.php";
        var send = { yell : yellNR };
        $.post(url,send,function(data) {
            if(data === "Feil") {
                return "Feil";
            }
            else {
                getYell();
            }
        })
            .fail(function() {
                console.log("Failed API call to delete yell");
        });        
    }
    
    function getInfo() {
        var url = "../API/getUserInfo.php";
        var send = { user : "<?php echo $_GET['user']; ?>" };
        var yellInfo = "";
        $.post(url,send,function(data) {
            if(data === "Error") {
                console.log("Failed getting user info");
            }
            else {
                yellInfo += "<h2>" + data.username + "</h2>";
                yellInfo += "<br>" + data.description + "<br><br><br>";
                yellInfo += "<i class='glyphicon glyphicon-map-marker'> </i> " + data.location;
                yellInfo += "<br><br>Joined: " + data.joindate;
                yellInfo += "<br><br>" + data.email + "<br><br>";
                yellInfo += "<br><br>";             
            }
            var userID = "<?php echo (isset($_SESSION['userID']) ? $_SESSION['userID'] : null) ?>";
            if (typeof userID !== 'undefined') {
                if (userID === data.userID) {
                    yellInfo += "<a href='updateinfo.php' class='btn btn-primary'>Update Info</a>";
                    yellInfo += "<br><br>";
                    yellInfo += "<a href='user.php' class='btn btn-primary'>Create a Yell!</a>";                
                }
            }
            $("#yellInfo").html(yellInfo);
            $("#profilePic").html('<img src=' + data.profilepicture + ' alt="Profile Picture" id="profilePicture" class="img-circle" height="150" >');
            $('#userBGR').css("background-image", "url(" + data.userbg + ")");  
        })
            .fail(function() {
                console.log("Failed API call to get user info");
        });           
    }
    
    function getLoginInfo() {
        var url = "../API/getLoginInfo.php";
        $.getJSON(url,function(data) {
            var logInfo = "";
            if(data === "Feil") {
                console.log("Not logged in");
            }
            else {
                logInfo += "<table><tr><td>Logged in as <a href='user.php'>" + data + "</a>";
                logInfo += "</td><td>&nbsp;&nbsp;&nbsp;&nbsp;</td>";
                logInfo += "<td style='vertical-align:top'>";
                logInfo += "<button class='btn btn-warning' id='logOut' onclick='logOut();'>Log Out</button></td></tr></table>";
            }
            $("#logOutDiv").html(logInfo);
        })
            .fail(function() {
                console.log("Failed API call to get login info");
        });        
    }
    
    function logOut() {
        var url = "../API/logOut.php";
        $.getJSON(url,function(data) {
            if(data === "Failed logout") {
                console.log("Failed logout");
                //$(location).attr('href', '../index.php');    
            }
            else {
                console.log("Logged out");
                $(location).attr('href', '../index.php');                    
            }
        })
        .fail(function(data) {
            console.log("Failed API call to logout");
            console.log(data);
        });
    }
    </script>    
      
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
            <div class='logout' id="logOutDiv">
                
            </div>
        </div>
    </div>  
        

    <div class="userBG" id="userBGR">
        
    </div>
    <div class="profilePictureDiv" id="profilePic">
        
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
            <div class="yellInfo" id="yellInfo"><br>

            </div>
            <div class="yells" id="yells">

            </div>
            <div class="floatContent">
                Lorem ipsum osv osv osv .
                
            </div>
        </div>
    </div>

</body>
</html>

