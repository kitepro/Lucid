<?php
    session_start();
    error_reporting(0);
    
    $con = mysqli_connect("localhost","root","","viitbe");
    
    if(isset($_SESSION['suflag']) && $_SESSION['suflag']){        
        $chkuser = $con->prepare("SELECT id FROM superuser");
        $chkuser->execute(); 
        $verify = $chkuser->get_result()->fetch_assoc(); 
        if($verify['id']==$_SESSION['staff']){            
            $chkuser = $con->prepare("SELECT Name,domain,Id FROM stafflist ORDER BY domain");
            $chkuser->execute(); 
            $allstaff = $chkuser->get_result()->fetch_all();
             
            $chkuser = $con->prepare("SELECT a.Id,b.title FROM stafflist a,groups b WHERE b.staffmember=a.Id");
            $chkuser->execute(); 
            $groups = $chkuser->get_result()->fetch_all();
        }
    }
    else{
        header("location:/BEProjects/Home");
        die;
    }

    $con->close();    
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title></title>
        <style>   
            @font-face{
                font-family: 'Agency FB';
	            src: url('Fonts/AFB.ttf') format('truetype');
            }   
            @font-face{
                font-family: 'Comic Sans MS';
	            src: url('Fonts/CSM.ttf') format('truetype');
            }
                     
            .topbar{
                float: right;
                width: 10%;
                background: #ff5252;
                color: #fff;
                transition: all 0.2s;
            }
            .topbar:hover{
                cursor: pointer;
                background: #fff;
                color: #ff5252;
            }
            .namediv{
                width: 50%;
                margin-left: 25%;
                font-family: 'Comic Sans MS';
                font-size: 25px;
                height: 30px;
            }
            .namediv1{
                width: 50%;
                margin-left: 25%;
                font-family: 'Comic Sans MS';
                font-size: 20px;
                height: 30px;
            }
            .name{
                float: left;
                cursor: pointer;   
                color: #000;
                transition: all 0.3s;             
            }
            .name:hover{
                color: #ff5252;
            }
            .dom{
                float: right;
            }
            .group{
                margin-left: 40px;
            }
        </style>
        <script type="text/javascript">
            function lg() {
                var xhttp;
                if (window.XMLHttpRequest) {
                    xhttp = new XMLHttpRequest();
                } else {
                    xhttp = new ActiveXObject("Microsoft.XMLHTTP");
                }
                xhttp.onreadystatechange = function () {
                    if (xhttp.readyState == 4 && xhttp.status == 200) {
                        var response = xhttp.responseText.trim();
                        if (response == "1") {
                            window.location = "/BEProjects/Home/";
                        }
                    }
                };
                xhttp.open("GET", "/BEProjects/AjaxRequest/LOGOUT/", true);
                xhttp.send(); 
            }
        </script>
    </head>
    <body style="margin: 0px;padding: 0px;" onload="document.getElementById('loader').style.display = 'none';">
        <div id="loader" style="width: 100%;height: 100vh;position: fixed;top: 0;left: 0;z-index: 10000;background: #fff;line-height: 100vh;text-align: center;font-size: 5vw;"><script>counter();</script></div>
        
        <div style="font-family: 'Comic Sans MS';height: 60px;line-height: 60px;position: fixed;width: 100%;background: #ff5252;text-align: center;box-shadow: 0px 1px 5px #000;color: #fff;font-size: 20px;">
            <div class="topbar" onclick="lg()">Logout</div>
            <div class="topbar" onclick="window.location='/BEProjects/IAmAuthorized/';">Account</div>
        </div><br><br><br><br><br><br><br><br>
        <?php
            for($i=1;$i<count($allstaff);$i++){
                echo "<div class='namediv'><a href='/BEProjects/WhosIt/".$allstaff[$i][2]."'><label class='name'>".$allstaff[$i][0]."</label></a><label class='dom'>".$allstaff[$i][1]."</label></div><br>";
                for($j=0;$j<count($groups);$j++){
                    if($groups[$j][0]==$allstaff[$i][2]){
                        echo "<div class='namediv1'><label class='group'>".$groups[$j][1]."</label></div><br>";
                    }
                }
            }
        ?>
    </body>
</html>
