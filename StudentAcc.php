<?php
    session_start();
    error_reporting(0);

    if(!isset($_SESSION['active'])){
        $_SESSION['homesess'] = "Please Login. Dont Ruin Our System";
        header('location:/BEProjects/Home/');
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

    $chkuser = $con->prepare("SELECT Name,leader,mygroup,req,mydet FROM studentlist WHERE GRNumber=?");
    $chkuser->bind_param("s",$_SESSION['active']);
    $chkuser->execute(); 
    $myinfo = $chkuser->get_result()->fetch_assoc(); 

    if($myinfo['mygroup']!=NULL){    
        $chkuser = $con->prepare("SELECT title,domain,details FROM groups WHERE id=? LIMIT 1");
        $chkuser->bind_param("i",$myinfo['mygroup']);
        $chkuser->execute();
        $grpinfo = $chkuser->get_result()->fetch_assoc(); 
    } 

    if(isset($_POST['acreq'])){
        $chkuser = $con->prepare("UPDATE studentlist SET mygroup=req,req=0 WHERE GRNumber=?");
        $chkuser->bind_param("s",$_SESSION['active']);
        $chkuser->execute();
        header('location:/BEProjects/StudentHangout/');
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
            
            #header{
                height: 60px;
                width: 100%;
                position: fixed;
                z-index: 100;
                color: #fff;
                background: #00a1ff;
                text-align: center;
                line-height: 60px;
                font-family: 'Agency FB';
                font-size: 40px;
                box-shadow: 0px 1px 3px #053446;
            }
            #acceptreq{
                height: 60px;
                width: 100%;
                position: fixed;
                z-index: 100;
                color: #fff;
                background: #00a1ff;
                text-align: center;
                line-height: 54px;
                font-family: 'Agency FB';
                font-size: 30px;
                transition: all 0.3s;
            }
            #acceptreq:hover{
                cursor: pointer;
                background: #fff;   
                color: #00a1ff;         
            }
            .buts{
                height: 50px;
                width: 20%;
                margin-top: 20px;
                margin-left: 65%;
                background: #ff6767;
                text-align: center;
                line-height: 50px;
                color: #fff;
                font-size: 20px;
                font-style: italic;
                border: 2px solid #ff6767;
                transition: all 0.2s;
            }
            .cgbut{
                width: 50%;
                margin-left: 0px;
            }
            .buts:hover{
                cursor: pointer;
                background: #fff;
                color: #ff6767;               
            }
            #lo{
                height: 100%;
                line-height: 5vh;
                background: #f00;
                width: 7%;
                text-align: center;
                color: #fff;                
                float: right;
                transition: all 0.2s;  
                font-size: 2.5vh;              
            }
            #lo:hover{
                cursor: pointer;
                background: #fff;
                color: #f00;
            }
            #bar{
                height: 3vh;
                margin-top: 1vh;
                background: #fff ;
                width: 30%;
                float: left;
                margin-left: 2%;
                border-radius: 20px;
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
    <body style="padding: 0px;margin: 0px" onload="document.getElementById('loader').style.display = 'none';">
        <div id="loader" style="width: 100%;height: 100vh;position: fixed;top: 0;left: 0;z-index: 10000;background: #fff;line-height: 100vh;text-align: center;font-size: 5vw;"><script>counter();</script></div>
        
        <?php   
            if(isset($_SESSION['afterpost'])){
                echo $_SESSION['afterpost'];
                unset($_SESSION['afterpost']);
            }
            if($myinfo['mygroup']!=NULL){
                    $con = mysqli_connect("localhost","root","","viitbe");
                    $chkuser = $con->prepare("SELECT GRNumber,Name,mygroup FROM studentlist WHERE (GRNumber<>?) AND (mygroup=? OR req=?)");
                    $chkuser->bind_param("sii",$_SESSION['active'],$myinfo['mygroup'],$myinfo['mygroup']);
                    $chkuser->execute(); 
                    $mygrp = $chkuser->get_result()->fetch_all(); 
                    $chkuser = $con->prepare("SELECT `1`,`2`,`3`,`4`,`5`,`6`,`7`,`8`,`9`,`10` FROM checkposts WHERE gid=?");
                    $chkuser->bind_param("i",$myinfo['mygroup']);
                    $chkuser->execute(); 
                    $prog = $chkuser->get_result()->fetch_assoc();
                    $c=1;
                    $t=1;
                    for($i=0;$i<count($mygrp);$i++){
                        if($mygrp[$i]['2']==$myinfo['mygroup']){
                            $c++;
                            $t++;
                        }else{
                            $t++;
                        }
                    }
                    if($c!=$t){
                        $integrity = round($c*100/$t,2);
                        for($i=0;$i<count($mygrp);$i++){
                            if($mygrp[$i]['2']==$myinfo['mygroup']){
                                //echo $mygrp[$i]['0']."A<br>";
                            }else{
                                //echo $mygrp[$i]['0']."N<br>";
                            }
                        }
                    }                 
                    $tc=0;
                    $tt=0;
                    for($i=1;$i<=10;$i++){
                        if($prog[$i]==3){
                            $tc++;
                            $tt++;
                        }
                        else if($prog[$i]!=0){
                            $tt++;
                        }
                    }

                    $con->close();
            } 
            else{                
                if($myinfo['leader']==0){
                    if($myinfo['req']!=0){                        
                        $con = mysqli_connect("localhost","root","","viitbe");
                        $chkuser = $con->prepare("SELECT user1,title FROM groups WHERE id=?");
                        $chkuser->bind_param("i",$myinfo['req']);
                        $chkuser->execute(); 
                        $grpreq = $chkuser->get_result()->fetch_assoc(); 
                        $con = mysqli_connect("localhost","root","","viitbe");
                        $chkuser = $con->prepare("SELECT Name FROM studentlist WHERE GRNumber=?");
                        $chkuser->bind_param("s",$grpreq['user1']);
                        $chkuser->execute(); 
                        $grpreqlname = $chkuser->get_result()->fetch_assoc(); 
                        $con->close();
                    }
                } 
            }
        ?>
        <div id="header">
             <?php
                if($myinfo['mygroup']!=NULL){
                    if(isset($grpinfo)){
                        if(isset($integrity)){
                            echo "Group under construction : ".htmlspecialchars($integrity)."%";
                        }
                        else{
                            echo "Working on '".htmlspecialchars($grpinfo['title'])."'";
                        }
                    }
                }
                else{
                    if(isset($grpreq)){
                        echo "<form method='post' id='accreq'><input type='hidden' name='acreq' value='1'></form><script>function postreq() {document.getElementById('accreq').submit();}</script>";
                        echo "<div id='acceptreq' onclick='postreq()'>Group invitation for '".htmlspecialchars($grpreq['title'])."' by ".htmlspecialchars($grpreqlname['Name'])."</div>";
                    }  
                    else{
                        echo "Alone...";
                    }
                }
             ?>
        </div>
        <div id="base" style="position: absolute;top: 60px;z-index: 90;width: 100%;">
            <br><br><br><br>
            <div style="width: 40%;margin-left: 10%;height: 70vh;float: left;">
                <label style="font-size: 30px;font-family: 'Comic Sans MS';color: #053446;">Project Details</label>
                <div style="margin-top: 10px;font-size: 20px;">
                    <?php
                        if(isset($grpinfo)){
                            if($grpinfo['details']!=NULL){
                                $sentences = explode(".",$grpinfo['details']);
                                for($i=0;$i<count($sentences);$i++){
                                    echo " ".ucfirst(ltrim(htmlspecialchars($sentences[$i]))).".";
                                }
                            }
                            else{
                                echo "<i>Fill Group Details In Settings Section (Group Leader Only)</i>";
                            }
                        }
                        else{
                            if($myinfo['leader']==1){
                                echo "<div onclick='window.location=\"/BEProjects/TeamUp/\";' class='buts cgbut'>Create Group</div>";
                            }
                            else{
                                echo "<i>Waiting for group request</i>";
                            }
                        }
                     ?>
                </div><br><br><br><br>
                <label style="font-size: 30px;font-family: 'Comic Sans MS';color: #053446;">My Details</label>
                <div style="margin-top: 10px;font-size: 20px;">
                    <?php
                        if($myinfo['mydet']!=NULL){     
                             $sentences = explode(".",$myinfo['mydet']);
                             for($i=0;$i<count($sentences);$i++){
                                 echo " ".ucfirst(ltrim(htmlspecialchars($sentences[$i]))).".";
                             }
                        }
                        else{
                            echo "<i>Write about your specialities here. Go to Settings section</i>";
                        }
                     ?>                    
                <br><br><br><br>
                </div>
            </div>    
            <?php
                if($myinfo['mygroup']!=NULL){
                    echo "<div class='buts' onclick='window.location=\"/BEProjects/Barracks/".htmlspecialchars($myinfo['mygroup'])."\";'>My Group</div>"; 
                    echo "<div class='buts' onclick='window.location=\"/BEProjects/Milestones/".htmlspecialchars($myinfo['mygroup'])."\";'>Milestones</div>";
                    echo "<div class='buts' onclick='window.location=\"/BEProjects/MyVoiceIsHeard/".htmlspecialchars($myinfo['mygroup'])."\";'>Discuss</div>";
                }
            ?>   
            <div class='buts' onclick='window.location="/BEProjects/SomeChanges/";'>Settings</div>                   
                <br><br><br><br>                  
        </div> 
        <div style="position: fixed;bottom: 0;width: 100%;height: 5vh;background: #111111;z-index: 300">
            <div id="bar" style="text-align: center;line-height: 3vh;">
                <?php
                    if(isset($grpinfo)){
                        echo "<div style='float:left;text-align: center;line-height:2.3vh;max-width:calc(100% - 0.7vh);height:2.3vh;margin-top:0.35vh;margin-left:0.35vh;border-radius:30px;width:".(round(($tc/$tt),4)*100)."%;background:#f00;'></div>";
                    }
                    else{
                        echo "<i>No Group</i>";
                    }
                ?>                
            </div>
            <div style="text-align: center;position: absolute;width: 30%;margin-left: 2%;height: 5vh;line-height: 5vh;font-size: 1.5vw;font-family: 'Comic Sans MS'"><?php echo (round(($tc/$tt),4)*100)."%"; ?></div>
            <div style="float: left;color: #fff;line-height: 5vh;margin-left: 8%;width: 20%;text-align: center;font-size: 1.35vw;"><i>Hey <?php echo ucfirst(explode(" ",htmlspecialchars($myinfo['Name']))[0]); ?> !!</i></div>
            <div id='lo' style="font-size: 1.25vw;" onclick='lg();'>Logout</div>
            <div style="float: right;color: #fff;line-height: 5vh;margin-right: 5%;font-size: 1.25vw;">SiteName | Online Since 2016</div>
        </div>            
    </body>
</html>
