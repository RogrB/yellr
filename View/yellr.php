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
        $('#reYellModal').modal({ show: false});
        $('#successModal').modal({ show: false});
        checkUsers();
        getStats();
        
        $("#reYellForm").submit(function(evt) {
            evt.preventDefault();
            var url = "../API/createYell.php";
            var sendYell = "<div class=\'media\'>";
            sendYell += '<div class=\"media-left\">';
            sendYell += "<img src=\'" + $("#profile").attr('src');
            sendYell += "\' class=\'media-object img-circle\' style=\'width:50px\'></div>";
            sendYell += '<div class=\"media-body\">';
            sendYell += "<h5 class=\'media-heading\'>reYelled from <a href=\'yellr.php?user=" + $("#reYellUser").text() + "\'> " + $("#reYellUser").text() + "</a>:</h5>";
            sendYell += "<p>" + $("#reYellTarget").text() + "<br>";
            sendYell += "<i><span style=\'font-size: 12px\'>";
            sendYell += "<br>" + $("#dateTarget").text() + "</span></i></p></div>";
            sendYell += "<br><br>";
            sendYell += $("#reYell").val();
            sendYell += "</div>";
            var send = {
                yell : sendYell
            };
            console.log(send);

            $.post(url,send,function(data) {
                if(data === "Feil") {
                    console.log("Failed to reYell");
                }
                else if (data === "Feil insetting") {
                    console.log("Failed to insert to DB");
                }
                else {
                    $('#reYellModal').modal('hide');
                    $('#successModal').modal('show');      
                }
            })
                .fail(function(data) {
                    console.log("Failed API call to reYell");
                    console.log(data);
                });

        });

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
                getStats();
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
                getStats();
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
            if (yell.userID !== null && yell !== "Feil bruker") {
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
                    
                    utdata += "<div class='media'><div class='media-left'>";
                    utdata += "<img src='" + yell[i].profilepicture + "' class='media-object img-circle' style='width:50px'></div>";
                    utdata += "<div class='media-body'>";
                    utdata += yell[i].yell;
                    utdata += "</div><br>";
                    if (yell[i].likesjekk) {
                        utdata += "<i class='glyphicon glyphicon-heart' style='color: red; cursor: pointer;' alt='Unlike' onclick='unlike(" + yell[i].yellNR + ");'> </i> ";
                    }
                    else {
                        utdata += "<i class='glyphicon glyphicon-heart' style='cursor: pointer;' alt='Like' onclick='like(" + yell[i].yellNR + ");'> </i> ";
                    }
                    utdata += yell[i].likes;
                    utdata += "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                    utdata += "<i class='glyphicon glyphicon-retweet' style='cursor: pointer;' alt='Reyells' onclick='stageReYell(" + yell[i].yellNR + ");'></i> ";
                    utdata += yell[i].reyell;
                    utdata += "<hr>";
                }
            }
            else if (yell === "Feil bruker") {
                utdata = "Nothing to show";
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
    
    function stageReYell(yell) {
        var url = "../API/getYell.php";
        var send = { yellNR : yell };
        $.post(url,send,function(data) {
            if(data === "Feil bruker") {
                console.log("Please log in to reYell");   
            }
            else if (data === "Error") {
                console.log("Could not reYell");
            }
            else {
                $("#reYellUser").html(data.username);
                $("#reYellTarget").html(data.yell);
                $("#dateTarget").html("Originally posted " + data.date);
                $("#profile").attr("src",data.profilepicture);
                $('#reYellModal').modal('show');      
            }
        })
        .fail(function(data) {
            console.log("Failed API call to get yell to reYell");
            console.log(data);
        });
    }
    
    function checkUsers() {
        var username = "<?php echo (isset($_SESSION['username']) ? $_SESSION['username'] : 'null') ?>";        
        if (username !== null && username !== "<?php echo $_GET['user'] ?>") {
            $("#followBtn").html('<button class="btn btn-primary" onclick="follow();" id="followButton">Follow</button>');
        }
    }
    
    function follow() {
        var url = "../API/follow.php";
        var usr = "<?php echo $_GET['user'] ?>";
        var send = { user : usr };

        $.post(url,send,function(data) {
            if(data === "Feil bruker") {
                console.log("Please log in to follow");
            }
            else if (data === "Feil") {
                console.log("Failed to register follow");
            }
            else {
                $("#followBtn").html('<button class="btn btn-primary" onclick="unFollow();" id="followButton">Un Follow</button>');
                getStats();
            }
        })
        .fail(function(data) {
            console.log("Failed API call to follow");
            console.log(data);
        });        
    }
    
    function unFollow() {
        var url = "../API/unFollow.php";
        var usr = "<?php echo $_GET['user'] ?>";
        var send = { user : usr };

        $.post(url,send,function(data) {
            if(data === "Feil bruker") {
                console.log("Please log in to unfollow");
            }
            else if (data === "Feil") {
                console.log("Failed to register unfollow");
            }
            else {
                $("#followBtn").html('<button class="btn btn-primary" onclick="follow();" id="followButton">Follow</button>');
                getStats();
            }
        })
        .fail(function(data) {
            console.log("Failed API call to unfollow");
            console.log(data);
        });        
    }
    
    function getStats() {
        var url = "../API/getStats.php";
        var usr = "<?php echo $_GET['user'] ?>";
        var send = { user : usr };

        $.post(url,send,function(data) {
            if(data === "Feil") {
                console.log("Failed to retrieve stats");
            }
            else {
                var stats = "<center><table style='text-align: center; padding: 15px; top: -15px; vertical-align: top;'><tr><td>&nbsp;&nbsp;Yells:&nbsp;&nbsp;<h4>" + data.yells;
                stats += "</h4></td><td>&nbsp;&nbsp;Following:&nbsp;&nbsp;<h4>" + data.following;
                stats += "</h4></td><td>&nbsp;&nbsp;Followers:&nbsp;&nbsp;<h4>" + data.followers;
                stats += "</h4></td><td>&nbsp;&nbsp;Likes:&nbsp;&nbsp;<h4>" + data.likes;
                stats += "</h4></td></tr></table></center>";
                $("#yellStatsDiv").html(stats);
            }
        })
        .fail(function(data) {
            console.log("Failed API call to get stats");
            console.log(data);
        });                
    }
    
    
    </script>    
    
    <div id="reYellModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">reYell from <span id="reYellUser"></span>&nbsp;&nbsp;&nbsp;
                  <img id="profile" style="width:50px" class="img-circle"></h4>
            </div>
            <div class="modal-body">
                <p><span id="reYellTarget"></span><br><br>
                    <i><span id="dateTarget" style="font-size: 12px"></span></i></p>
            </div>
            <div class="modal-footer">
                <form action='' method="POST" id="reYellForm">
                    <textarea class="form-control" placeholder="Add a comment?" rows='8' cols="50" name='reYell' id='reYell'></textarea><br>
                    <center><input type='submit' name='submit' value='reYell!' id='reYellBtn' class='btn btn-primary'>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button></center>
                </form>                
            </div>
          </div>

        </div>
    </div>
    
    <div id="successModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-body">
                <div class="alert alert-success">
                  <strong>reYell posted!</strong> Your reYell has been successfully posted.
                </div>                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" onclick="getYell();">Close</button></center>             
            </div>
          </div>

        </div>
    </div>    
    
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
        <div class="yellStats" id="yellStatsDiv">
            
        </div>
        <div class="rightfollow" id="followBtn">
            
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

