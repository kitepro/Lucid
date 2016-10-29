<?php
    session_start();
    
    error_reporting(0);

    if(!(isset($_SESSION['active']) || isset($_SESSION['staff']))){
        $_SESSION['homesess'] = "Please Register Before Wandering";
        header("location:/BEProjects/Home/");
        exit;        
    }
        
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

    if(isset($_SESSION['active'])){
        $chkuser = $con->prepare("SELECT mygroup,leader FROM studentlist WHERE GRNumber=?");
        $chkuser->bind_param("s",$_SESSION['active']);
        $chkuser->execute();     
        $mygrp = $chkuser->get_result()->fetch_assoc();
        if($mygrp['mygroup']!=$_GET['gid']){
            header("location:/BEProjects/StudentHangout/");
            exit;          
        }
    }
    else{
        $chkuser = $con->prepare("SELECT staffmember FROM groups WHERE id=?");
        $chkuser->bind_param("i",$_GET['gid']);
        $chkuser->execute(); 
        $mygrp = $chkuser->get_result()->fetch_assoc();
        if($mygrp['staffmember']!=$_SESSION['staff']){
            header("location:/BEProjects/IAmAuthorized/");
            exit;  
        }
    }


    $chkuser = $con->prepare("SELECT GRNumber FROM studentlist WHERE req=?");
    $chkuser->bind_param("i",$_GET['gid']);
    $chkuser->execute();     
    $nota = $chkuser->get_result()->fetch_all();
    if($nota!=NULL){
        header("location:/BEProjects/StudentHangout/");
        exit;
    }

    $chkuser = $con->prepare("SELECT `1`,`2`,`3`,`4`,`5`,`6`,`7`,`8`,`9`,`10`,`task1`,`task2`,`task3`,`task4`,`task5`,`task6`,`task7`,`task8`,`task9`,`task10` FROM checkposts WHERE gid=?");
    $chkuser->bind_param("i",$_GET['gid']);
    $chkuser->execute(); 
    $allowmore = $chkuser->get_result()->fetch_assoc();

    $chkuser = $con->prepare("SELECT title FROM groups WHERE id=?");
    $chkuser->bind_param("i",$_GET['gid']);
    $chkuser->execute(); 
    $grpinfo = $chkuser->get_result()->fetch_assoc();

    if(isset($_POST['task']) && $_POST['task']!=''){
        if($allowmore[10]==0 && isset($_SESSION['taskno'])){
            if(isset($_POST['task']) && $_POST['task']!=''){
                $chkuser = $con->prepare("UPDATE checkposts SET `task".htmlspecialchars($_SESSION['taskno'])."`=?,`".htmlspecialchars($_SESSION['taskno'])."`=1 WHERE gid=?");
                $task = htmlspecialchars($_POST['task']);
                $chkuser->bind_param("si",$task,$_GET['gid']);
                $chkuser->execute();
                unset($_SESSION['taskno']);
                header("location:/BEProjects/Milestones/".$_GET['gid']);
                exit;  
            }
        }   
        else{
            echo "No more";
        }
    }

    if(isset($_POST['mpost']) && isset($_SESSION['gleader'])){
        for($i=1;$i<=10;$i++){
            if(isset($_POST['mt'.$i]) && $_POST['mt'.$i]!=''){
                $chkuser = $con->prepare("UPDATE checkposts SET `task".$i."`=? WHERE gid=? AND `".$i."`=1");
                $mt = htmlspecialchars($_POST['mt'.$i]);
                $chkuser->bind_param("si",$mt,$_GET['gid']);
                $chkuser->execute();
            }
        }
        header("location:/BEProjects/Milestones/".$_GET['gid']);
        exit;
    }

    $con->close();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title></title>
        <style type="text/css">
            @font-face{
                font-family: 'Agency FB';
	            src: url('Fonts/AFB.ttf') format('truetype');
            }   
            @font-face{
                font-family: 'Comic Sans MS';
	            src: url('Fonts/CSM.ttf') format('truetype');
            }
            
            .ms1{
                border: 2px solid #ff8d8d; 
                background: #ff8d8d;    
            }
            .ms2{
                border: 2px solid #38b4f3;   
                background: #38b4f3;              
            }
            .ms3{
                border: 2px solid #48daa5;   
                background: #48daa5;               
            }
            .common{
                width: 70%;
                text-align: center;
                min-height: 30px;
                height: auto;
                line-height: 30px;
                font-size: 20px;
                font-style: italic;
                transition: all 0.3s;
                border-radius: 4px;
                color: #fff;
                margin-left: 10%;
                overflow: hidden;
            }
            .common:hover{
                cursor: pointer;             
            }
            .ms1:hover{
                background: #fff;
                color: #ff8d8d;                   
            }
            .ms2:hover{
                background: #fff;  
                color: #38b4f3;                 
            }
            .ms3:hover{
                background: #fff;  
                color: #48daa5;                
            }
            .changetext{
                text-align: center;
                font-size: 15px;
                height: 20px;
                line-height: 20px;
                width: 70%;
                border-radius: 4px;
                border: 1px solid #515151;    
            }
            .topbuts{
                height: 60px;line-height: 60px;float: right;width: 10%;color: #fff;
                background: #00a1ff;
                transition: all 0.3s;
                text-align: center;
                font-size: 1.25vw;
                font-family: 'Comic Sans MS';
            }
            .topbuts:hover{
                background: #fff;
                color: #00a1ff;
                cursor: pointer;                
            }
            .edited{
                height: 30px;
                line-height: 30px;
                border: 1px solid #212121;
                border-radius: 4px;
                width: 70%;
                text-align: center;
                margin-left: 10%;
            }
            .buts{
                height: 40px;
                width: 65%;
                margin-top: 10px;
                background: #00a1ff;
                text-align: center;
                line-height: 40px;
                color: #fff;
                font-size: 15px;
                font-family: 'Comic Sans MS';
                border: 2px solid #00a1ff;
                transition: all 0.2s;
            }
            .buts:hover{
                cursor: pointer;
                background: #fff;
                color: #00a1ff;                
            }
            #errlbl{
                color: #f00;
                font-family: 'Comic Sans MS';
                font-size: 15px;
            }
        </style>
        <script type="text/javascript">
            var l = 0;
            function invert(x, y) {
                if (l == 1) {
                    var xhttp;
                    if (window.XMLHttpRequest) {
                        xhttp = new XMLHttpRequest();
                    } else {
                        xhttp = new ActiveXObject("Microsoft.XMLHTTP");
                    }
                    xhttp.onreadystatechange = function () {
                        if (xhttp.readyState == 4 && xhttp.status == 200) {
                            var response = xhttp.responseText.trim();
                            if (response == 1) {
                                window.location.reload();
                            }
                        }
                    };
                    xhttp.open("GET", "/BEProjects/AjaxRequest/STONE/" + encodeURI(x) + "/" + encodeURI(y) + "/", true);
                    xhttp.send();
                }
                else {
                    alert("leader plz");
                }
            }

            function changetotext() {
                document.getElementById('chng').textContent = "Save";
                document.getElementById('chng').onclick = function () {
                    chkpost2();
                };
                for (i = 1; i <= 10; i++) {
                    if (document.getElementById('t' + i)) {
                        document.getElementById('t' + i).innerHTML = "<input name='mt" + i + "' class='edited' class='changetext' type='text' placeholder='" + document.getElementById('at' + i).textContent + "'>";
                    }
                }
            }

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

            function chkpost1() {
                if (document.getElementsByName('task')[0].value == '') {
                    document.getElementById('errlbl').textContent = "Blank Milestone Found";
                }
                else {
                    document.getElementById("addms").submit();
                }
            }

            function chkpost2() {
                document.getElementById('modification').submit();
            }

            function drawcanvs() {
                var cnvs = document.getElementById('help').getContext('2d');
                cnvs.font = "18px Arial";
                cnvs.beginPath();
                cnvs.fillStyle = "#ff8d8d";
                cnvs.arc(20, 10, 7, 0, Math.PI * 2);
                cnvs.fillText("Not Complete", 35, 15);
                cnvs.fill();
                cnvs.beginPath();

                cnvs.fillStyle = "#38b4f3";
                cnvs.arc(20, 40, 7, 0, Math.PI * 2);
                cnvs.fillText("Waiting for evaluation", 35, 45);
                cnvs.fill();
                cnvs.beginPath();

                cnvs.fillStyle = "#48daa5";
                cnvs.arc(20, 70, 7, 0, Math.PI * 2);
                cnvs.fillText("Ticked", 35, 75);
                cnvs.fill();
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
        <br><br><br><br><br><br>
        <div style="float: left;width: 70%;">
        <label style="font-family: 'Comic Sans MS';font-size: 25px;margin-left: 10%;">Milestones for <i><?php echo htmlspecialchars($grpinfo['title']); ?></i></label><br><br>
        <form id="modification" method="post" >
        <input type="hidden" value="1" name="mpost">
        <?php

            $i=1;
            if(isset($_SESSION['staff']) || $mygrp['leader']==1){
                echo "<script>l=1;</script>";
            }
            while($i!=11 && $allowmore[$i]!=0){
                if($allowmore[$i]==1){
                    echo "<div id='t".$i."'><div class='ms1 common' id='at".$i."' onclick='invert(\"".htmlspecialchars($_GET['gid'])."\",\"".$i."\")'>".htmlspecialchars($allowmore['task'.$i])."</div></div><br>";                    
                }elseif($allowmore[$i]==2){
                    echo "<div><div class='ms2 common' onclick='invert(\"".htmlspecialchars($_GET['gid'])."\",\"".$i."\")'>".htmlspecialchars($allowmore['task'.$i])."</div></div><br>";
                }else{
                    echo "<div><div class='ms3 common' onclick='invert(\"".htmlspecialchars($_GET['gid'])."\",\"".$i."\")'>".htmlspecialchars($allowmore['task'.$i])."</div></div><br>";
                }
                $i++;
            }
        ?>
        </form>
        <?php
            if($i==1){
                echo "No milestones set yet.";
            }
            if($i!=11 && isset($_SESSION['gleader'])){
                $_SESSION['taskno'] = $i;
                echo "<form method='post' id='addms'><input type='text' class='edited' name='task' placeholder='Task ".$i."'></form>";
            }
        ?>
        </div>
        <?php
            if(isset($_SESSION['gleader'])){
                echo "<div style='float:right;width:30%;'><br><br><br><br><div class='buts' onclick='chkpost1();'>Add Milestone</div><br>";
                echo '<div class="buts" id="chng" onclick="changetotext()">Edit</div><br><label id="errlbl"></label><br><br><br>
                        <canvas id="help"></canvas><script>drawcanvs()</script></div>';
            }
        ?>
    </body>
</html>
