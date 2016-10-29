<?php
    session_start();
    error_reporting(0);

    if(!isset($_SESSION['gleader'])){            
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

    $chkuser = $con->prepare("SELECT mygroup FROM studentlist WHERE GRNumber=?");
    $chkuser->bind_param("s",$_SESSION['active']);
    $chkuser->execute(); 
    $mygid = $chkuser->get_result()->fetch_assoc(); 
    if($mygid['mygroup']!=NULL){
        header('location:/BEProjects/StudentHangout/');
        exit;        
    }
    $con->close();

    if(isset($_POST['m']) && isset($_POST['title']) && $_POST['domain']){
        if(isset($_SESSION['grs'][0]) && isset($_SESSION['grs'][1])){
            $con = mysqli_connect("localhost","root","","viitbe");
            
                $chkuser = $con->prepare("SELECT mygroup,req FROM studentlist WHERE GRNumber=?");
                $chkuser->bind_param("s",$_SESSION['grs'][0]);
                $chkuser->execute(); 
                $gid1 = $chkuser->get_result()->fetch_assoc(); 
                $chkuser = $con->prepare("SELECT mygroup,req FROM studentlist WHERE GRNumber=?");
                $chkuser->bind_param("s",$_SESSION['grs'][1]);
                $chkuser->execute(); 
                $gid2 = $chkuser->get_result()->fetch_assoc(); 
                if(isset($_SESSION['grs'][2])){
                    $chkuser = $con->prepare("SELECT mygroup,req FROM studentlist WHERE GRNumber=?");
                    $chkuser->bind_param("s",$_SESSION['grs'][2]);
                    $chkuser->execute(); 
                    $gid3 = $chkuser->get_result()->fetch_assoc(); 
                }
                if($gid1['mygroup']==NULL && $gid1['req']==0){
                    if($gid2['mygroup']==NULL && $gid2['req']==0){
                        if($con->autocommit(FALSE)){
                            $chkuser = $con->prepare("INSERT INTO groups (user1,title,domain,year) VALUES (?,?,?,".date('Y').")");
                            $user1 = htmlspecialchars($_SESSION['active']);
                            $_POST['title'] = ucfirst($_POST['title']);
                            $_POST['domain'] = ucfirst($_POST['domain']);
                            $chkuser->bind_param("sss",$user1,$_POST['title'],$_POST['domain']);
                            $chkuser->execute(); 
                            $chkuser = $con->prepare("SELECT id FROM groups WHERE user1=?");
                            $chkuser->bind_param("s",$_SESSION['active']);
                            $chkuser->execute();                         
                            $newid = $chkuser->get_result()->fetch_assoc(); 
                            $chkuser = $con->prepare("UPDATE studentlist SET req=? WHERE GRNumber=?");
                            $nei = htmlspecialchars($newid['id']);
                            $gr0 =htmlspecialchars($_SESSION['grs'][0]);
                            $chkuser->bind_param("is",$nei,$gr0);
                            $chkuser->execute(); 
                            $chkuser = $con->prepare("UPDATE studentlist SET req=? WHERE GRNumber=?");
                            $gr1 =htmlspecialchars($_SESSION['grs'][1]);
                            $chkuser->bind_param("is",$nei,$gr1);
                            $chkuser->execute(); 
                            $chkuser = $con->prepare("UPDATE studentlist SET mygroup=? WHERE GRNumber=?");
                            $chkuser->bind_param("is",$nei,$user1);
                            $chkuser->execute(); 
                            if(isset($_SESSION['grs'][2])){
                                if($gid3['mygroup']==NULL  && $gid3['req']==0){                                
                                    $chkuser = $con->prepare("UPDATE studentlist SET req=? WHERE GRNumber=?");
                                    $gr2 =htmlspecialchars($_SESSION['grs'][2]);
                                    $chkuser->bind_param("is",$nei,$gr2);
                                    $chkuser->execute(); 
                                    $chkuser = $con->prepare("INSERT INTO checkposts (gid) VALUES (?)");
                                    $chkuser->bind_param("i",$nei);
                                    $chkuser->execute(); 
                                    $con->commit();
                                    $con->autocommit(TRUE);
                                    header('location:/BEProjects/StudentHangout/');
                                    exit;
                                }
                                else{
                                    $_SESSION['afterpost'] = htmlspecialchars($_SESSION['grs'][2])." is already assigned";     
                                    $con->rollback();                                
                                }
                            }            
                            else{   
                                $con->commit();
                                $con->autocommit(TRUE);
                                header('location:/BEProjects/StudentHangout/');
                                exit;
                            }
                        }
                    }
                    else{
                        $_SESSION['afterpost'] = $_SESSION['grs'][1]." is already assigned";                        
                    }
                }
                else{
                    $_SESSION['afterpost'] = $_SESSION['grs'][0]." is already assigned";
                }

            $con->close();
        }
        else{
            $_SESSION['afterpost'] = "Invalid POST";
        }
    }
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
            }
            .topbuts:hover{
                background: #fff;
                color: #00a1ff;
                cursor: pointer;
                
            }    
            .texts{
                width: 50%;
                height: 30px;
                line-height: 30px;
                text-align: center;
                border: 1px solid #434343;
                margin-top: 20px;
                font-family: 'Agency FB';
                font-size: 20px;
            }        
            .buts{
                width: 40%;
                height: 30px;
                line-height: 30px;
                font-family: 'Agency FB';
                font-size: 20px;
                margin-left: 30%;
                color: #fff;
                border: 2px solid #ff6767;
                background: #ff6767;
                transition: all 0.3s;
            }
            .buts:hover{
                color: #ff6767;
                background: #fff;       
                cursor: pointer;         
            }
        </style>
        <script type="text/javascript">
            var x = 0;

            function getnames() {
                var a = "X";
                if (document.getElementById('n1').value != '') {
                    a = document.getElementById('n1').value;
                }
                var b = "X";
                if (document.getElementById('n2').value != '') {
                    b = document.getElementById('n2').value;
                }
                var c = "X";
                if (document.getElementById('n3').value != '') {
                    c = document.getElementById('n3').value;
                }
                var xhttp;
                if (window.XMLHttpRequest) {
                    xhttp = new XMLHttpRequest();
                } else {
                    xhttp = new ActiveXObject("Microsoft.XMLHTTP");
                }
                xhttp.onreadystatechange = function () {
                    if (xhttp.readyState == 4 && xhttp.status == 200) {
                        var response = xhttp.responseText.trim();
                        if (response == "2") {
                            document.getElementById('err').textContent = "Bad Request";
                        }
                        else if (response == "3") {
                            document.getElementById('err').textContent = "Dont include yourself in the list";
                        }
                        else if (response == "4") {
                            document.getElementById('err').textContent = "Repeated GRNumbers";
                        }
                        else if (response == "5") {
                            document.getElementById('err').textContent = "Enter Minimum two GRNumbers to for group of 3";
                        }
                        else if (response == "6") {
                            document.getElementById('err').textContent = "One of GRNumber is Invalid";
                        }
                        else if (response == "7") {
                            document.getElementById('err').textContent = "One of GRNumber has already joined a group";
                        }
                        else if (response == "8") {
                            document.getElementById('err').textContent = "One of GRNumber is set as a leader";
                        }
                        else {
                            var arr = response.split("|");
                            document.getElementById('nm1').value = arr[1];
                            document.getElementById('nm2').value = arr[2];
                            if (arr[3] != null) {
                                document.getElementById('nm3').value = arr[3];
                            }
                            document.getElementById('f1').style.display = "none";
                            document.getElementById('f2').style.display = "block";
                            document.getElementById('err').textContent = "";
                        }
                    }
                };
                xhttp.open("GET", "/BEProjects/AjaxRequest/NAMERETURN/" + encodeURI(a) + "/" + encodeURI(b) + "/" + encodeURI(c) + "/", true);
                xhttp.send();
            }

            function showf2() {
                if (a && b && c) {
                    document.getElementById('f1').style.display = 'none';
                    document.getElementById('f2').style.display = 'block';
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

            function chktitanddom() {
                if (document.getElementsByName('title')[0].value == "") {
                    document.getElementById('err').textContent = "Enter Project Title";
                    return false;
                }
                if (document.getElementsByName('domain')[0].value == "") {
                    document.getElementById('err').textContent = "Enter Project Domain";
                    return false;
                }
                document.getElementById('cg').submit();
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
        <br><br><br>
        <br><br><br>
        <div id="f1" style="width: 50%;text-align: center;margin-left: 25%;">
            <label style="color: #292929;font-size: 25px;font-family: 'Agency FB';"><b>Enter G.R.Numbers</b></label><br>
            <input class="texts" type="text" id="n1" placeholder="Member 2"><br>
            <input class="texts" type="text" id="n2" placeholder="Member 3"><br>
            <input class="texts" type="text" id="n3" placeholder="Member 4 (Optional)"><br><br>  
            <noscript>Turn on JavaScript</noscript><script>document.write('<div class="buts" onclick="getnames()">Proceed</div>');</script>
        </div>
        <div id="f2" style="display: none;width: 50%;text-align: center;margin-left: 25%;">
            <form method="post" id="cg">
                <input type="hidden" name="m" value="safepost">
                <input class="texts" type="text" name="title" placeholder="Project Title">
                <input class="texts" type="text" name="domain" placeholder="Project Domain">
                <input class="texts" type="text" id="nm1" disabled><br>
                <input class="texts" type="text" id="nm2" disabled><br>
                <input class="texts" type="text" id="nm3" disabled><br> <br>                 
                <noscript>Turn on JavaScript</noscript><script>document.write('<div class="buts" onclick="chktitanddom();">Create</div><br><div class="buts" onclick="window.location.reload();">Change</div>');</script>                
            </form>
        </div><br>
        <div style='color: #f00;width:100%;text-align:center;font-style: italic'>
        <?php
            if(isset($_SESSION['afterpost'])){
                echo "<label id='err'>".$_SESSION['afterpost']."</label>";
                unset($_SESSION['afterpost']);
            }
            else{
                echo "<label id='err'></label>";
            }
        ?></div>
    </body>
</html>
