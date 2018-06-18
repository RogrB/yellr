<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="view/jquery-3.2.0.js"></script>
    <script src="view/js/bootstrap.min.js"></script>    
    
    <title>Yellr. It's what's happening.</title>
    <link href="view/css/bootstrap.min.css" rel="stylesheet">
    <link rel='stylesheet' type='text/css' href='view/yellr.css'>
    <script src="view/yellr.js" type="text/javascript"></script>
</head>
<body>
    
    
<script type="text/javascript">
    "use strict"; 
    $(function() {
        var url = "API/getLatestYell.php";
        $.getJSON(url,function(data) {
            var tabell;
            if(data === "Error") {
                tabell = "Could not access latest yell";
            }
            else {
                tabell = "<h1 class='titleh1'>Latest Yell:</h1>";
                tabell += "<p class='whitetext'>" + data.yell + "</p>";                
                tabell += "By <a class='whitetext' href='view/yellr.php?user=" + data.username + "'>" + data.username + "</a> " + data.date;
            }
            $("#latestYell").html(tabell);
        })
            .fail(function() {
                console.log("Failed to get latest yell");
                $("#latestYell").html("Failed to get latest yell");
        });
        
        $("#registerForm").submit(function(evt) {
            evt.preventDefault();
            var url = "API/registerUser.php";
            var send = {
                username        : $("#regusername").val(),
                email           : $("#regemail").val(),
                password        : $("#regpassword").val()             
            };
            //console.log(send);
            $.post(url,send,function(data) {
                if(data === "Feil") {
                    console.log("Failed to register new user " + send.username);
                }
                else {
                    //console.log(data);
                   $(location).attr('href', 'view/user.php?user=' + send.username + '&newuser=true');  
                }
            })
                .fail(function(data) {
                    console.log("Failed API call");
                    console.log(data);
                });
                
        }); 
        
        $("#loginForm").submit(function(evt) {
            evt.preventDefault();
            var url = "API/login.php";
            var send = {
                username        : $("#username").val(),
                password        : $("#password").val()             
            };

            $.post(url,send,function(data) {
                if(data === "DB error") {
                    console.log("Failed to register new user " + send.username);
                }
                else if (data === "Wrong password") {
                    console.log("Login fail");
                }
                else if (data === "No such user") {
                    console.log("Login fail");
                }
                else {
                    $(location).attr('href', 'view/user.php?user=' + send.username);  
                }
            })
                .fail(function(data) {
                    console.log("Failed API call");
                    console.log(data);
                });
                
        });             
        
    });
    
</script>

    <div class="header">
        <div class="headerlinks">
            <table>
                <tr>
                    <td style="vertical-align: top">
                        <a href="index.php"><img src="view/image/yellrlogo.png" width="20" height="20"></a>&nbsp;&nbsp;
                    </td>
                    <td>
                        <p><a href="index.php">Home</a> &nbsp;&nbsp;&nbsp;&nbsp; <a href="view/about.php">About</a></p>
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
                            <img src='view/image/yellrlogo.png' height="288" width="265">&nbsp;&nbsp;&nbsp;&nbsp;
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
                                <form id="loginForm" method="POST">
                                    <table>
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
                                                &nbsp;&nbsp;<input type="submit" value="Log in" id='login'class="btn btn-primary">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div id='remember'><input type='checkbox' name='remember' value='true' checked="checked"> Remember me</div>
                                            </td>
                                        </tr>
                                    </table>
                                </form>
                            </div>
                            <br>
                            <div class='formdiv2'>
                                <form id="registerForm" method="POST">
                                    <input type='text' name='regusername' id='regusername' placeholder="Username" class="form-control">
                                    <input type='text' name='regemail' id='regemail' placeholder="Email" class="form-control">
                                    <input type='password' name='regpassword' id='regpassword' placeholder="Password" class="form-control">
                                    <center><input type="submit" id="register" class="btn btn-primary" value="Sign up for Yellr"></center>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                        </td>
                        <td><div id="latestYell"></div>

                                
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

