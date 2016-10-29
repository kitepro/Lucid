<?php    
    session_start();
    error_reporting(0);

    $con = mysqli_connect("localhost","root","","viitbe");

    if(isset($_SESSION['staff'])){
        $chkuser = $con->prepare("SELECT id FROM stafflist WHERE Id=? AND Password=?");
        $chkuser->bind_param("is",$_SESSION['staff'],$_SESSION['password']);
        $chkuser->execute(); 
        $verify = $chkuser->get_result()->fetch_assoc(); 
        if($verify==NULL){
            $_SESSION['homesess'] = "Session Hijacked";
            unset($_SESSION['staff']);
            unset($_SESSION['password']);    
            $con->close();
            header('location:/BEProjects/Home/');
            exit;
        }
    }
    elseif(isset($_SESSION['active'])){
        $chkuser = $con->prepare("SELECT id FROM studentlist WHERE GRNumber=? AND Password=?");
        $chkuser->bind_param("ss",$_SESSION['active'],$_SESSION['password']);
        $chkuser->execute(); 
        $verify = $chkuser->get_result()->fetch_assoc(); 
        if($verify==NULL){   
            $_SESSION['homesess'] = "Session Hijacked";
            unset($_SESSION['active']);
            unset($_SESSION['password']);  
            $con->close();
            header('location:/BEProjects/Home/');
            exit;
        }
    }

    $show = FALSE;
    //STAFF
    if(isset($_SESSION['staff'])){
        $show = TRUE;             
    }

    //STUDENT
    elseif(isset($_SESSION['active'])){
        $show = TRUE; 
    }

    //GET INFO
    if($show){
        $chkuser = $con->prepare("SELECT Name,mydet,domain,alias,id FROM stafflist WHERE id=?");
        $chkuser->bind_param("i",$_GET['eid']);
        $chkuser->execute(); 
        $info = $chkuser->get_result()->fetch_assoc();
        $chkuser = $con->prepare("SELECT title FROM groups WHERE staffmember=?");
        $chkuser->bind_param("i",$_GET['eid']);
        $chkuser->execute(); 
        $workingon = $chkuser->get_result()->fetch_all();
    }
    else{
        $_SESSION['homesess'] = "Please Login Before Wandering";
        header('location:/BEProjects/Home/');
        exit;
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
                 
            .topbuts{
                height: 60px;line-height: 60px;float: right;width: 10%;color: #fff;
                background: #00a1ff;
                transition: all 0.3s;
                text-align: center;
                font-family: 'Comic Sans MS';
                font-size: 1.25vw;
            }
            .topbuts:hover{
                background: #fff;
                color: #00a1ff;
                cursor: pointer;
                
            }
            .infos{
                font-family: 'Comic Sans MS';
                font-size: 30px;
                color: #053446;
            }            
            .texts{
                font-family: 'Comic Sans MS';
                font-size: 30px;
                color: #00a1ff;
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

            function acc(x) {
                if (x == 1) {
                    window.location = "/BEProjects/IAmAuthorized/";
                }
                else {
                    window.location = "/BEProjects/StudentHangout/";
                }
            }
            
            function counter() {
                var arr = ["#ff5757", "#3fc4e8", "#00da8a", "#a755e7"];
                var x = 1;
                var c = 0;
                document.getElementById('loader').style.color = arr[0];
                window.setInterval(function () {
                    if (x <= 3) {
                        document.getElementById('loader').innerHTML += ".";
                        x++;
                    }
                    else {
                        c++;
                        if (c == 4) {
                            c = 0;
                        }
                        document.getElementById('loader').style.color = arr[c];
                        document.getElementById('loader').innerHTML = "";
                        x = 1;
                    }
                }, 500);
            }
        </script>
    </head>
    <body style="margin: 0px;padding: 0px;" onload="document.getElementById('loader').style.display = 'none';">
        <div id="loader" style="width: 100%;height: 100vh;position: fixed;top: 0;left: 0;z-index: 10000;background: #fff;line-height: 100vh;text-align: center;font-size: 5vw;"><script>counter();</script></div>
        
        <div style="height: 60px;width: 100%;position: fixed;z-index: 100;background: #00a1ff;box-shadow: 0px 1px 5px #053446">
            <div class="topbuts" onclick="lg()">Logout</div>
            <?php 
                if(isset($_SESSION['staff'])){
                    echo '<div class="topbuts" onclick="acc(1)">Account</div>'; 
                }
                else{
                    echo '<div class="topbuts" onclick="acc(2)">Account</div>';                     
                }
            ?>            
        </div>
        <br><br><br><br><br>
        <div style="width: 80%;margin-left: 10%;text-align: left;">
        <?php
            if(!isset($info) || $info==NULL){
                echo "<label style='color:#f00;font-family:\"Comic Sans MS\";'>No such staff exists</label>";
                exit;
            }
        ?>       
        <label class="texts" style="font-size: 40px;color: #2e2e2e">
        <?php            
            echo htmlspecialchars($info['Name']);
        ?>
        </label>
        <br><br>         
        <label class="infos">Domain : </label>
        <label class="texts">
        <?php    
            echo htmlspecialchars($info['domain']);         
        ?>
        </label>    
        <br><br>
        <label class="infos"><?php if(isset($workingon) && $workingon!=NULL){ echo "Working on : " ;}else { echo "<label class='texts'>Currently Free</label>";}  ?> </label><label class="texts"><br>
        <?php  
            if(isset($workingon) && $workingon!=NULL){
                for($i=0;$i<count($workingon);$i++) 
                echo htmlspecialchars($workingon[$i][0])."<br>";          
            }
        ?>
        </label>
        <br><br>
        <?php  
            if(isset($info['alias']) && $info['alias']!=NULL){ 
                echo '<label class="infos">Alias : </label><label class="texts">'.htmlspecialchars($info['alias']).'</label>';          
            }
        ?>
        <br><br>
        <?php  
            if(isset($info['mydet']) && $info['mydet']!=NULL){ 
                echo '<label class="infos">Details : </label><label class="texts">'.htmlspecialchars($info['mydet']).'</label>';          
            }
        ?>
        <br><br>
        </div>
        <div style="position: absolute;border: 2px solid #000;height: 300px;width: 300px;z-index: 100;right: 200px;top: 150px;text-align: center;font-family: 'Comic Sans MS';line-height: 300px;">
            <?php                
                $con = mysqli_connect("localhost","root","","viitbe");
                if(isset($_GET['eid'])){
                    $chkuser = $con->prepare("SELECT pimage FROM stafflist WHERE Id=?");
                    $chkuser->bind_param("i",$_GET['eid']);                    
                }                
                $chkuser->execute(); 
                $getimg = $chkuser->get_result()->fetch_assoc(); 
                if($getimg['pimage']!=NULL){
                    echo "<img src=".('data:' . 'image/*' . ';base64,' . base64_encode(file_get_contents("Uploads/".md5($getimg['pimage']."ProfImg1"))))." height='300' width='300' alt='Profile'>";
                }
                else{
                    echo "No Image Uploaded";
                }
                $con->close();
            ?>
        </div>
    </body>
</html>
