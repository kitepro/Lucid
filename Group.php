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

    if($show){
        $chkuser = $con->prepare("SELECT a.title,a.domain,b.name,a.details,b.id FROM groups a, stafflist b WHERE a.id=? AND b.id = a.staffmember");
        $chkuser->bind_param("i",$_GET['gid']);
        $chkuser->execute(); 
        $groupinfo = $chkuser->get_result()->fetch_assoc();    

        if(isset($_SESSION['staff'])){
            $chkuser = $con->prepare("SELECT staffmember FROM groups WHERE id=?");
            $chkuser->bind_param("i",$_GET['gid']);
            $chkuser->execute(); 
            $mygrp = $chkuser->get_result()->fetch_assoc(); 
        }
        else{
            $chkuser = $con->prepare("SELECT mygroup FROM studentlist WHERE GRNumber=?");
            $chkuser->bind_param("s",$_SESSION['active']);
            $chkuser->execute(); 
            $mygrp = $chkuser->get_result()->fetch_assoc();             
        }
    }
    else{
        $_SESSION['homesess'] = "Please Register Before Wandering";
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
                font-size: 1.25vw;
                font-family: 'Comic Sans MS';
            }
            .topbuts:hover{
                background: #fff;
                color: #00a1ff;
                cursor: pointer;                
            }
            a{
                text-decoration: none;   
                color: #00a1ff;
                transition: all 0.3s;                
            }
            a:hover{
                color: #ff4f4f;
            }
            th,td{
                height: 60px;
                line-height: 60px;
                font-family: 'Comic Sans MS';                
            }
            .rfonts{
                
            }
            #ms{
                width: 60%;margin-left: 20%;height: 60px;line-height: 60px;color: #fff;background: #00a1ff;text-align: center;font-size: 1.5vw;font-family: 'Comic Sans MS';
                transition: all 0.2s;
                border: 2px solid #00a1ff;
            }
            #ms:hover{
                cursor: pointer;
                background: #fff;
                color: #00a1ff;
            }
            #stafflabel:hover{
                cursor: pointer;
            }
        </style>
        <script src="/BEProjects/JQ/external/jquery/jquery.js"></script>
        <script type="text/javascript">
            function drawperc(x) {
                var cnvs = document.getElementById('perc').getContext('2d');
                cnvs.save();
                //PERCENT
                cnvs.beginPath();
                cnvs.moveTo(0, (185 - (165 * x)));
                //cnvs.lineTo(220, (185 - (173 * x)));
                if (x != 1) {
                    cx = 0;
                    yf = true;
                    for (i = 1; i <= 10; i++) {
                        if (yf) {
                            cpy = (185 - (165 * x)) + 3;
                            yf = false;
                        }
                        else {
                            cpy = (185 - (165 * x)) - 3;
                            yf = true;
                        }
                        cx = (cx + (22));
                        cnvs.quadraticCurveTo((cx - 11), cpy, cx, (185 - (165 * x)));
                    }
                }
                else {
                    //cnvs.lineTo(220, (185 - (165 * x)));
                    cx = 0;
                    yf = true;
                    for (i = 1; i <= 5; i++) {
                        if (yf) {
                            cpy = (185 - (165 * x)) + 3;
                            yf = false;
                        }
                        else {
                            cpy = (185 - (165 * x)) - 3;
                            yf = true;
                        }
                        cx = (cx + (44));
                        cnvs.quadraticCurveTo((cx - 22), cpy, cx, (185 - (165 * x)));
                    }
                }
                cnvs.lineTo(220, 220);
                cnvs.lineTo(10, 220);
                cnvs.closePath();
                if (x != 1) {
                    cnvs.fillStyle = "#BD3737";
                }
                else {
                    cnvs.fillStyle = "#3CBD5F";
                }
                cnvs.fill();

                //CAULDRON
                cnvs.beginPath();
                cnvs.moveTo(10, 10);
                cnvs.lineTo(10, 110);
                cnvs.bezierCurveTo(10, 210, 210, 210, 210, 110);
                cnvs.lineTo(210, 10);
                cnvs.stroke();
                cnvs.lineTo(310, 10);
                cnvs.lineTo(220, 220);
                cnvs.lineTo(0, 220);
                cnvs.lineTo(0, 10);
                cnvs.lineTo(10, 10);
                cnvs.fillStyle = "#fff";
                cnvs.fill();

                //BUBBLES
                if (x != 1) {
                    cnvs.beginPath();
                    if (Math.random() < 0.5) {
                        cpx = (Math.random() * 66) + 17;
                        for (i = 1; i <= 3; i++) {
                            cpy = (185 - (165 * x)) - (Math.random() * 20) - 5;
                            cnvs.moveTo(cpx, cpy);
                            cnvs.arc(cpx, cpy, 5, 0, Math.PI * 2);
                            cpx += 50;
                        }
                    } else {
                        cpx = (Math.random() * 100) + 17;
                        for (i = 1; i <= 2; i++) {
                            cpy = (185 - (165 * x)) - (Math.random() * 20) - 5;
                            cnvs.moveTo(cpx, cpy);
                            cnvs.arc(cpx, cpy, 5, 0, Math.PI * 2);
                            cpx += 70;
                        }
                    }
                    if (x != 1) {
                        cnvs.fillStyle = "#BD3737";
                    }
                    else {
                        cnvs.fillStyle = "#3CBD5F";
                    }
                    cnvs.fill();
                }

                cnvs.restore();

                var cnvs = document.getElementById('vp').getContext('2d');
                cnvs.save();
                if (x != 1) {
                    cnvs.fillStyle = "#BD3737";
                }
                else {
                    cnvs.fillStyle = "#3CBD5F";
                }
                cnvs.fillRect(10, 10, 20, 20);
                cnvs.font = "20px Arial";
                if (x != 1) {
                    cnvs.fillText(((x * 100)).toFixed(2).toString() + "%", 40, 27);
                }
                else {
                    cnvs.fillText("Project Complete", 40, 27);
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

            function fonts() {
                var x = Math.round(22 * document.getElementById('tds').clientWidth / 125);
                $(".rfonts").css({ "font-size": x.toString()+"px" });
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
    <body style="margin: 0px;padding: 0px;" onload="fonts();document.getElementById('loader').style.display = 'none';" onresize="fonts()">
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
        <?php 
            if(!isset($groupinfo) || $groupinfo==NULL){
                echo "<br><br><br><br><br><div style='width:100%;text-align:center;font-family:\"Comic Sans MS\";'>No Such Group Exists</div><script>document.getElementById('loader').style.display = 'none';</script>";
                exit;
            }
        ?>
        <div>
            <br><br><br><br><br>
        <?php
            if(isset($groupinfo) && $groupinfo!=NULL){
    
                $con = mysqli_connect("localhost","root","","viitbe");

                $chkuser = $con->prepare("SELECT id,Name,GRNumber FROM studentlist WHERE mygroup=?");
                $chkuser->bind_param("i",$_GET['gid']);
                $chkuser->execute(); 
                $users = $chkuser->get_result()->fetch_all();  
                
                $chkuser = $con->prepare("SELECT `1`,`2`,`3`,`4`,`5`,`6`,`7`,`8`,`9`,`10` FROM checkposts WHERE gid=?");
                $chkuser->bind_param("i",$_GET['gid']);
                $chkuser->execute(); 
                $prog = $chkuser->get_result()->fetch_assoc();     
                $compl = 0;
                $tot = 0;    
                for($i=1;$i<=10;$i++){
                    if($prog[$i]==3){
                        $tot++;
                        $compl++;
                    }
                    elseif($prog[$i]!=0){
                        $tot++;
                    }
                }

                $con->close();
            }
        ?><br><br>
            <div style="width: 40%;margin-left: 10%;float: left;">
                <label style="color: #053446;font-family: 'Comic Sans MS';font-size: 30px;"><?php echo htmlspecialchars($groupinfo['title']); ?> | <?php echo htmlspecialchars($groupinfo['domain']); ?></label><br><br>
                <label style="font-size: 20px;">
                <?php
                         $sentences = explode(".",$groupinfo['details']);
                         for($i=0;$i<count($sentences);$i++){
                             echo " ".ucfirst(ltrim(htmlspecialchars($sentences[$i]))).".";
                         }
                ?>
                </label>
                <br><br><br>
                <label style="color: #053446;font-family: 'Comic Sans MS';font-size: 30px;">Staff assigned : <?php echo "<a id='stafflabel' href='/BEProjects/WhosIt/".$groupinfo['id']."'>".ucwords(strtolower(htmlspecialchars($groupinfo['name']))).'</a>'; ?></label>
                <br><br><br>
                <label style="color: #053446;font-family: 'Comic Sans MS';font-size: 30px;">Project Status</label><br><br><br>
                <div>
                    <canvas height="190px" width="230px" id="perc"></canvas>
                    <canvas height="50px" width="230px" id="vp"></canvas>
                    <?php echo '<script>drawperc('.($compl/$tot).');</script>'; ?>
                </div>
            </div>
            <div style="width: 50%;float: right;">
                <table class="rfonts" style="text-align: center;width: 60%;margin-left: 20%;border: 2px solid #00a1ff;border-collapse: collapse;" border="1">
                    <tr><th id="tds" style="width: 30%"><i>GRN</i></th><th><i>Name</i></th></tr>
                    <?php
                        if(isset($users)){                            
                            for($i=0;$i<count($users);$i++){
                                echo "<tr><td><a href='/BEProjects/WhosIt/".htmlspecialchars($_GET['gid'])."/".htmlspecialchars($users[$i]['0'])."'>".htmlspecialchars($users[$i]['2'])."</a></td><td>".ucwords(htmlspecialchars($users[$i]['1']))."</td></tr>";
                            }
                        }
                    ?>
                </table><br><br>
                <?php
                    if((isset($_SESSION['staff']) && $mygrp['staffmember']==$_SESSION['staff'])||(isset($_SESSION['active']) && $mygrp['mygroup']==$_GET['gid'])){ 
                        echo '<div id="ms" onclick="window.location=\'/BEProjects/Milestones/'.htmlspecialchars($_GET['gid']).'\';">Milestones</div><br>';
                        echo '<div id="ms" onclick="window.location=\'/BEProjects/MyVoiceIsHeard/'.htmlspecialchars($_GET['gid']).'\';">Forum</div>';
                    }
                ?>
            </div>
        </div>
    </body>
</html>
