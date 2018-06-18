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
        "use strict"; 
        var uname = <?php echo json_encode($_SESSION['username']) ?> ;
        $(function() {
            var url = "../API/checkLogin.php";
            var send = { username : uname };
            var userContent = "";
            $.post(url,send,function(data) {
                if(data === "Feil") {
                    console.log("Access denied, please log in");
                    $(location).attr('href', '../index.php');  
                }
                else if(data === "Wrong user") {
                    console.log("Access denied, wrong user");
                    $(location).attr('href', '../index.php');
                }
                else {
                    //console.log("Verified user");
                    $("#descriptionInput").html(data.description);
                    $("#locationInput").html(data.location);
                    $("#emailInput").html(data.email); 
                    $("#joinInput").html(data.joindate);
                    
                    var updateLink = "<a href='updateinfo.php?user=" + data.username + "' class='btn btn-primary'>Update Info</a>";
                    $("#updateLinkInput").html(updateLink);
                    
                    userContent += '<img src="' + data.profilepicture + '" alt="Profile Picture" id="profilePicture" class="img-circle" height="150" >';
                    userContent += "<table><tr><td><h1><a href='yellr.php?user=" + data.username + "'><i>" + data.username + "</i></a></h1></td><td>&nbsp;&nbsp;&nbsp;&nbsp;<a href='yellr.php?user=" + data.username + "'> My yells</a></td></tr></table>";
                    $("#userContentInput").html(userContent);
                    
                    var loginContent = "<a href='yellr.php?user=" + data.username + "'>" + data.username + "</a>";
                    $("#loginInput").html(loginContent);
                }           
                
            })
                .fail(function(jqxhr, status, exception) {
                //alert('Exception:' + exception + "\n Status: " + status + " \n jqxhr: " + jqxhr);
                console.log("Failed API call to verify login " + jqxhr);
            });
            
            $("#logOut").click(function() {
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

            });       
            
        $("#createYell").submit(function(evt) {
            evt.preventDefault();
            var url = "../API/createYell.php";
            var send = {
                yell        : $("#yell").val()
            };
            //console.log($("#yell").val());
            
            $.post(url,send,function(data) {
                if(data === "Feil") {
                    console.log("Failed to create yell");
                }
                else if (data === "Feil insetting") {
                    console.log("Failed to insert to DB");
                }
                else {
                    $(location).attr('href', 'yellr.php?user=' + uname);  
                }
            })
                .fail(function(data) {
                    console.log("Failed API call to create yell");
                    console.log(data);
                });
                
        });                
            
        });
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
            <div class='logout'>
                <table>
                    <tr>
                        <td style="vertical-align: bottom">
                            <p>Logged in as <span id="loginInput"></span></p>
                        </td>
                        <td>
                            &nbsp;&nbsp;&nbsp;&nbsp;
                        </td>
                        <td style="vertical-align:top">
                            <button id="logOut" class="btn btn-warning">Log Out</button>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>  
    
    
    <div class='wrapper'>
        <div class='userinfo'>

            <br>
            <span id="descriptionInput"></span>
            <br><br><br>
            <i class='glyphicon glyphicon-map-marker'> </i>
            <span id="locationInput"></span>
            <br><br>
            <p>Joined: <span id="joinInput"></span></p>
            <br><br>
            <span id="emailInput"></span>
            <br><br><br>
            
            <center><span id="updateLinkInput"></span></center>
            <br><br>

        </div>
        <div class='usercontent'>
            
            <span id="userContentInput"></span>
 
            <br>
            <form action='' method="POST" id="createYell">
                <textarea class="form-control" placeholder="Yell!" rows='8' cols="50" name='yell' id='yell'></textarea><br>
                <center><input type='submit' name='submit' value='Yell!' id='yellbtn' class='btn btn-primary'></center>
            </form>
        </div>
    </div>
 

</body>
</html>

