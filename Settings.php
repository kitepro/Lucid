<?php
    session_start(); 
    error_reporting(0);

    if(!(isset($_SESSION['staff']) || isset($_SESSION['active']))){
        $_SESSION['homesess'] = "One Needs To Have Account To Make Changes In It";
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
    $con->close();

    if(isset($_POST['updt'])){
        $con = mysqli_connect("localhost","root","","viitbe");
        if(isset($_POST['pass'])){
            $md5pass = md5($_POST['pass']."gaswashere");
            if(isset($_SESSION['staff'])){
                $chkuser = $con->prepare("SELECT Id FROM stafflist WHERE Id=? AND Password=?");
                $chkuser->bind_param("is",$_SESSION['staff'],$md5pass);
                $chkuser->execute(); 
                $me = $chkuser->get_result()->fetch_assoc(); 
                if($me!=NULL){
                    if(isset($_POST['email']) && $_POST['email']!=''){
                        $chkuser = $con->prepare("UPDATE stafflist SET email=? WHERE Id=?");
                        $email = htmlspecialchars($_POST['email']);
                        $chkuser->bind_param("si",$email,$_SESSION['staff']);
                        $chkuser->execute(); 
                    }
                    if(isset($_POST['alias']) && $_POST['alias']!=''){
                        if(strlen($_POST['alias'])<=20){
                            $chkuser = $con->prepare("UPDATE stafflist SET alias=? WHERE Id=?");
                            $alias = htmlspecialchars($_POST['alias']);
                            $chkuser->bind_param("si",$alias,$_SESSION['staff']);
                            $chkuser->execute(); 
                        }
                    }
                    if(isset($_POST['mydet']) && $_POST['mydet']!=''){
                        $chkuser = $con->prepare("UPDATE stafflist SET mydet=? WHERE Id=?");
                        $mydet = htmlspecialchars($_POST['mydet']);
                        $chkuser->bind_param("si",$mydet,$_SESSION['staff']);
                        $chkuser->execute(); 
                    }
                    if(isset($_POST['npass1']) && isset($_POST['npass2']) && $_POST['npass1']!='' && $_POST['npass2']!=''){
                        if($_POST['npass1'] == $_POST['npass2']){
                            $md5pass = md5($_POST['npass1']."gaswashere");
                            $chkuser = $con->prepare("UPDATE stafflist SET Password=? WHERE Id=?");
                            $chkuser->bind_param("si",$md5pass,$_SESSION['staff']);
                            $chkuser->execute();                              
                        }
                    }
                }
                else{
                    $_SESSION['posterr'] = "Invalid Password";
                }
            }
            else{
                $chkuser = $con->prepare("SELECT GRNumber FROM studentlist WHERE GRNumber=? AND Password=?");
                $chkuser->bind_param("ss",$_SESSION['active'],$md5pass);
                $chkuser->execute(); 
                $me = $chkuser->get_result()->fetch_assoc(); 
                if($me!=NULL){
                    if(isset($_POST['email']) && $_POST['email']!=''){
                        $chkuser = $con->prepare("UPDATE studentlist SET email=? WHERE GRNumber=?");
                        $email = htmlspecialchars($_POST['email']);
                        $chkuser->bind_param("ss",$email,$_SESSION['active']);
                        $chkuser->execute();
                    }
                    if(isset($_POST['alias']) && $_POST['alias']!=''){
                        $chkuser = $con->prepare("UPDATE studentlist SET alias=? WHERE GRNumber=?");
                        $alias = htmlspecialchars($_POST['alias']);
                        $chkuser->bind_param("ss",$alias,$_SESSION['active']);
                        $chkuser->execute(); 
                    }
                    if(isset($_POST['mydet']) && $_POST['mydet']!=''){
                        $chkuser = $con->prepare("UPDATE studentlist SET mydet=? WHERE GRNumber=?");
                        $mydet = htmlspecialchars($_POST['mydet']);
                        $chkuser->bind_param("ss",$mydet,$_SESSION['active']);
                        $chkuser->execute(); 
                    }
                    if(isset($_POST['npass1']) && isset($_POST['npass2']) && $_POST['npass1']!='' && $_POST['npass2']!=''){
                        if($_POST['npass1'] == $_POST['npass2']){
                            $md5pass = md5($_POST['pass']."gaswashere");
                            $chkuser = $con->prepare("UPDATE studentlist SET Password=? WHERE GRNumber=?");
                            $chkuser->bind_param("ss",$md5pass,$_SESSION['active']);
                            $chkuser->execute();                              
                        }
                    }
                    $_SESSION['posterr'] = "Updated";
                }
                else{
                    $_SESSION['posterr'] = "Invalid Password";
                } 
            }
        }
        $con->close();
        header('location:/BEProjects/SomeChanges/');
        exit;
    }

    if(isset($_POST['ne']) && isset($_POST['ps']) && isset($_SESSION['suflag']) && $_SESSION['suflag']){
        $con = mysqli_connect("localhost","root","","viitbe");

        $md5pass = md5($_POST['ps']."gaswashere");
        $chkuser = $con->prepare("SELECT Id FROM stafflist WHERE Id=? AND Password=?");
        $chkuser->bind_param("is",$_SESSION['staff'],$md5pass);
        $chkuser->execute();
        $confrmps = $chkuser->get_result()->fetch_assoc(); 
        if($confrmps!=NULL){
            $chkuser = $con->prepare("SELECT Id FROM stafflist WHERE eid=?");
            $chkuser->bind_param("s",$_POST['ne']);
            $chkuser->execute();
            $neexists = $chkuser->get_result()->fetch_assoc();       
            if($neexists!=NULL){
                $chkuser = $con->prepare("UPDATE superuser SET Id=?");
                $chkuser->bind_param("i",$neexists['Id']);
                $chkuser->execute();   
                $_SESSION['posterr'] = "You Are No Longer A SuperUser";      
                unset($_SESSION['suflag']);       
            }   
            else{
                $_SESSION['posterr'] = "Bad Employee Id";
            }        
        }   
        else{
            $_SESSION['posterr'] = "Invalid Password";
        }        

        $con->close();
        header('location:/BEProjects/SomeChanges/');
        exit;        
    }

    if(isset($_POST['mydet2']) && isset($_POST['ps2']) && isset($_SESSION['gleader']) && $_SESSION['gleader']){
        $con = mysqli_connect("localhost","root","","viitbe");

        $md5pass = md5($_POST['ps2']."gaswashere");
        $chkuser = $con->prepare("SELECT mygroup FROM studentlist WHERE GRNumber=? AND Password=?");
        $chkuser->bind_param("ss",$_SESSION['active'],$md5pass);
        $chkuser->execute();
        $confrmps = $chkuser->get_result()->fetch_assoc(); 
        if($confrmps!=NULL){
            $chkuser = $con->prepare("UPDATE groups SET details=? WHERE id=?");
            $mydet = htmlspecialchars($_POST['mydet2']);
            $chkuser->bind_param("si",$mydet,$confrmps['mygroup']);
            $chkuser->execute();   
            $_SESSION['posterr'] = "Group Info Updated";       
        }   
        else{
            $_SESSION['posterr'] = "Invalid Password";
        }        

        $con->close();
        header('location:/BEProjects/SomeChanges/');
        exit;        
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
                font-family: 'Comic Sans MS';
            }
            .topbuts:hover{
                background: #fff;
                color: #00a1ff;
                cursor: pointer;
                
            }
            input{
                height: 40px;
                width: 50%;
                font-family: 'Comic Sans MS';
            }
            #sb,#chngsu,#chnggd{
                height: 45px;
                width: 40%;
                margin-left: calc(30% - 1px);
                background: #00a1ff;
                color: #fff;
                line-height: 45px;
                transition: all 0.3s;
                border: 2px solid #00a1ff;
                font-size: 20px;
                font-family: 'Comic Sans MS';
                text-align: center;
            }
            #sb:hover,#chngsu:hover,#chnggd:hover{
                background: #fff;
                color: #00a1ff;
                cursor: pointer;
            }
            #suresu{
                width: 20%;margin-left: 40%;height: 50px;line-height: 50px;font-size: 30px;
                background: #00a1ff;
                color: #fff;
                cursor: pointer;
                transition: all 0.2s;    
                border: 2px solid #00a1ff;            
            }
            #suresu:hover{
                background: #fff;
                color: #00a1ff;
                cursor: pointer;                
            }
        </style>
        
        <script src="/BEProjects/JQ/external/jquery/jquery.js"></script>        
        <script type="text/javascript">
            function acc(x) {
                if (x == 1) {
                    window.location = "/BEProjects/IAmAuthorized/";
                }
                else {
                    window.location = "/BEProjects/StudentHangout/";
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

            function chkform() {
                if (document.getElementsByName('pass')[0].value != '') {
                    if (document.getElementsByName('email')[0].value == '' && document.getElementsByName('alias')[0].value == '' && document.getElementsByName('npass1')[0].value == '' && document.getElementsByName('mydet')[0].value == '') {
                        document.getElementById('errlbl').textContent = "Empty Form";
                    }
                    else {
                        if (document.getElementsByName('alias')[0].value.length > 20) {
                            document.getElementById('errlbl').textContent = "Alias Max Length Is 20";
                        }
                        else if (document.getElementsByName('npass1')[0].value != '' && document.getElementsByName('npass2')[0].value == '') {
                            document.getElementById('errlbl').textContent = "Confirm New Password";
                        }
                        else {
                            if (document.getElementsByName('npass1')[0].value == document.getElementsByName('npass2')[0].value) {
                                document.getElementById('updates').submit();
                            }
                            else {
                                document.getElementById('errlbl').textContent = "Passwords Didnt Match";
                            }
                        }
                    }
                }
                else {
                    document.getElementById('errlbl').textContent = "Enter Current Password";
                }
            }

            function curts(x) {
                if (document.getElementsByName('ne')[0].value == '' || document.getElementsByName('ps')[0].value == '') {
                    document.getElementById('errlbl').textContent = "Incomplete form";
                    return;
                }
                if (x == 1) {
                    document.getElementById('curtain').style.display = 'block';
                    document.getElementById('curtain').style.top = document.body.scrollTop + 'px';
                    document.getElementById('p1').style.display = 'block';
                    document.getElementById('p1').style.top = document.body.scrollTop + 'px';
                    document.body.style.overflow = 'hidden';
                }
                else {
                    document.getElementById('curtain').style.display = 'none';
                    document.getElementById('p1').style.display = 'none';
                    document.body.style.overflow = 'initial';
                }
            }

            function chngsu() {
                var x = document.createElement('form');
                x.setAttribute('method', 'post');
                x.appendChild(document.getElementsByName('ne')[0]);
                x.appendChild(document.getElementsByName('ps')[0]);
                x.submit();
            }

            function chngd() {
                var x = document.createElement('form');
                x.setAttribute('method', 'post');
                x.appendChild(document.getElementsByName('mydet2')[0]);
                x.appendChild(document.getElementsByName('ps2')[0]);
                x.submit();
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

            function upldpi() {
                document.getElementById('imagesample').innerHTML = "";
                document.getElementById('picurtain').style.display = 'block';
                document.getElementById('imagesample').style.display = 'block';
                var ifile = document.createElement('input');
                ifile.setAttribute('type', 'file');
                ifile.setAttribute('accept', 'image/*');
                ifile.addEventListener('change', function () {
                    document.getElementById('picurtain').childNodes[0].textContent = "Loading";
                    var fd = new FormData();
                    fd.append("upld", ifile.files[0]);
                    $.ajax({
                        url: '/BEProjects/AjaxRequest/UPLOADIMAGE/',
                        dataType: 'text',
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: fd,
                        type: 'post',
                        success: function (resp) {
                            resp.trim();
                            if (resp != "1") {
                                var img = document.createElement('img');
                                document.getElementById('imagesample').appendChild(img);
                                document.getElementById('imagesample').childNodes[0].src = resp;
                                document.getElementById('imagesample').childNodes[0].height = '300';
                                document.getElementById('imagesample').childNodes[0].width = '300';
                                document.getElementById('picurtain').childNodes[0].textContent = "Done";
                            }
                        }
                    });
                });
                ifile.style.visibility = "hidden";
                document.body.appendChild(ifile);
                ifile.click();
            }
        </script>
    </head>
    <body style="padding: 0px;margin: 0px;" onload="document.getElementById('loader').style.display = 'none';">
        <div id="picurtain" style="position: fixed;height: 100vh;width: 100%;opacity: 0.7;background: #000;display: none;line-height: 150vh;text-align: center;font-family: 'Comic Sans MS';" onclick="this.style.display = 'none';document.getElementById('imagesample').style.display ='none';"><label style="color:#fff;">Select Image</label></div>
        <div id="imagesample" style="overflow: hidden;display: none;position: fixed;height: 300px;width: 300px;border: 3px solid #fff;left: calc(50% - 150px);top: calc(50vh - 150px);background: #fff;"></div>
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
        </div><br><br><br><br><br><br><br><br><br>
        <div style="border: 1px dashed #053446;width: 50%;margin-left: 25%;text-align: center;">
            <div id="loader" style="width: 100%;height: 100vh;position: fixed;top: 0;left: 0;z-index: 10000;background: #fff;line-height: 100vh;text-align: center;font-size: 5vw;"><script>counter();</script></div>
        
            <label style="color: #053446;font-size: 25px;font-family: 'Comic Sans MS';">Account Details</label><br><br>
            <label style="color: #f00;font-family: 'Comic Sans MS';">
            <?php                
                $con = mysqli_connect("localhost","root","","viitbe");

                if(isset($_SESSION['staff'])){
                    $chkuser = $con->prepare("SELECT email,password,alias FROM stafflist WHERE Id=?");
                    $chkuser->bind_param("i",$_SESSION['staff']);
                    $chkuser->execute(); 
                    $me = $chkuser->get_result()->fetch_assoc(); 
                }
                else{
                    $chkuser = $con->prepare("SELECT email,password,alias,mygroup FROM studentlist WHERE GRNumber=?");
                    $chkuser->bind_param("s",$_SESSION['active']);
                    $chkuser->execute(); 
                    $me = $chkuser->get_result()->fetch_assoc();                      
                }
                $con->close(); 
                echo "Current Email : ".$me['email'];
                if($me['alias']!=''){
                    echo "<br><br>Current Alias : ".$me['alias'];
                }
            ?></label><br><br>
            <form method="post" id="updates">
            <input type="hidden" name="updt" value="1">
            <input  type='email' style='text-align:center;' name='email' placeholder='New Email'><br><br>
            <input type='text' style='text-align:center;' name='alias' placeholder='New Alias (20 Chars Max)'><br><br>
            <input type='password' style='text-align:center;' name='npass1' placeholder='New Password'><br><br>
            <input type='password' style='text-align:center;' name='npass2' placeholder='New Password Again'><br><br>
            <textarea name="mydet" style="text-align: center;width: 50%;height: 100px;font-family: 'Comic Sans MS';" placeholder="My Details"></textarea><br><br>
            <input type='password' style='text-align:center;' name='pass' placeholder='Current Password'><br><br>
            </form>
            <div id="sb" onclick="chkform()">Save</div><br>
            <div id="sb"><a>My Profile</a></div><br>
            <div id="sb" onclick="upldpi()">Upload Profile Image</div><br><br>
            <label style="color: #f00;font-style: italic;" id="errlbl">Fill the fields to be updated</label>
            <?php
                if(isset($_SESSION['posterr'])){
                    echo "<script>document.getElementById('errlbl').textContent='".$_SESSION['posterr']."';</script>";
                    unset($_SESSION['posterr']);
                }
            ?>
        </div><br><br><br><br>
        <?php
            if(isset($_SESSION['suflag']) && $_SESSION['suflag']!=NULL){
                echo "<input type='text' name='ne' style='text-align:center;width:30%;margin-left:35%;' placeholder='New SuperUser Employee Id'><br><br>";
                echo "<input type='password' name='ps' style='text-align:center;width:30%;margin-left:35%;' placeholder='Current Password'><br><br>";
                echo "<div id='chngsu' onclick='curts(1)'>Change SuperUser</div>";
            }
            elseif(isset($_SESSION['gleader']) && $_SESSION['gleader'] && $me['mygroup']!=NULL){
                echo '<textarea name="mydet2" style="text-align: center;width: 40%;margin-left:30%;height: 100px;font-family: \'Comic Sans MS\';" placeholder="Group Details"></textarea><br><br>';
                echo "<input type='password' name='ps2' style='text-align:center;width:40%;margin-left:30%;' placeholder='Current Password'><br><br>";
                echo "<div id='chnggd' onclick='chngd()'>Change Group Details</div>";                
            }
        ?><br><br><br><br>
        <div id="curtain" style="display: none;width: 100%;height: 100vh;background: #fff;opacity: 0.9;position: absolute;z-index: 2000;" onclick="curts(2)">
        </div>
        <div id="p1" style="display: none;width: 50%;margin-left:25%;height: 70vh;margin-top: 15vh;background: #fff;border: 2px solid #053446;z-index: 2000;position: absolute;border-radius: 30px;text-align: center;line-height: 70px; font-size: 40px;font-family: 'Comic Sans MS';">
            <br><br>Are You Sure?<br><br>
            <div id="suresu" onclick="chngsu()">Yes</div>
        </div>
    </body>
</html>
