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
        //"use strict"; 
        var uname = <?php echo json_encode($_SESSION['username']) ?> ;
        $(function() {
            var url = "../API/checkLogin.php";
            var send = { username : uname };
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
                    $("#unameHeader").html(data.username);
                    $("#joindate").html(data.joindate);
                    $("#description").val(data.description);                
                    $("#location").val(data.location);
                    $("#email").val(data.email);
                }
            })
                .fail(function(jqxhr, status, exception) {
                //alert('Exception:' + exception + "\n Status: " + status + " \n jqxhr: " + jqxhr);
                console.log("Failed API call to verify login " + jqxhr);
            });

            $("#userinfo").submit(function(evt) {
                evt.preventDefault();
                var formData = new FormData($(this)[0]);
                if($("#filstreng").val() !== "") {
                    var file_data = $('#filstreng').prop('files')[0]; 
                    formData.append('profilepicture', file_data);
                }
                if($("#filstreng2").val() !== "") {
                    var file_data = $('#filstreng2').prop('files')[0]; 
                    formData.append('userbg', file_data);
                }                
                $.ajax({
                     url: '../API/updateInfo.php',
                     cache: false,
                     contentType: false,
                     processData: false,
                     data: formData,                         
                     async: true,
                     enctype: 'multipart/form-data',
                     type: 'POST',
                     success: function(response){
                        if (response === "Feil") {
                           console.log("Failed to update info");
                           $("#result").html("Failed to update info");
                        }
                        else if (response === "Feil insetting") {
                            console.log("Failed to update info, or info did not get modified");
                            $("#result").html("Failed to update info, or info did not get modified");
                        }
                        else {
                            console.log("Info updated successfully");
                            $("#result").html("Info updated successfully");
                        }
                     }
                  })
                    .fail(function(data) {
                        console.log("Failed API call to update info");
                        console.log(data);
                        $("#result").html("Failed API call to update info");
                    });                       
            }); 
        });
    </script>
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
        <h2 id="unameHeader"></h2>
        <p>Joined on <span id="joindate"></span></p>
        <form method="POST" id='userinfo'>
            <table>
                <tr>
                    <td>
                        <p>Description:</p>
                    </td>
                    <td>
                        <input type='text' name='description' id='description' class="form-control">
                    </td>
                </tr>
                <tr>
                    <td>
                        <p>Location:</p>
                    </td>
                    <td>
                        <input type='text' name='location' id='location' class="form-control">
                    </td>
                </tr>
                <tr>
                    <td>
                        <p>Email:</p>
                    </td>
                    <td>
                        <input type='text' name='email' id='email' class="form-control">
                    </td>
                </tr>            
                <tr>
                    <td>
                        Profile Picture:
                    </td>
                    <td>
                        <input type="file" id="filstreng">
                    </td>
                </tr>
                <tr>
                    <td>
                        <p>Background Image:</p>
                    </td>
                    <td>
                        <input type="file" id="filstreng2">
                    </td>
                </tr>
                <tr>
                    <td colspan="2"><br>
                        <center><input type='submit' name='update' value='Update' class="btn btn-primary"></center>
                        <br><span id="result"></span><br><br>
                        <center><a href="user.php?user=<?php echo $_SESSION['username']; ?>" class="btn btn-primary">Back</a></center>
                    </td>
                </tr>
            </table>
        </form>
    </div>
    
</body>
</html>

