<?php
    session_start();
    error_reporting(0);
    //STUDENT NOT ALLOWED
    if(!isset($_SESSION['staff'])){
        $_SESSION['homesess'] = "Not Your Place";
        header("location:/BEProjects/Home");
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

    //MY NAME
    $chkuser = $con->prepare("SELECT Name FROM stafflist WHERE Id=?");
    $chkuser->bind_param("i",$_SESSION['staff']);
    $chkuser->execute(); 
    $me = $chkuser->get_result()->fetch_assoc(); 
    
    //GET THE SUPERUSER
    $_SESSION['suflag']=FALSE;
    $chkuser = $con->prepare("SELECT id FROM superuser WHERE 1");
    $chkuser->execute();     
    $su = $chkuser->get_result()->fetch_assoc();
    if($su['id']==$_SESSION['staff']){
        $_SESSION['suflag']=TRUE;
    }
    
    $con->close();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title></title>      
        <script type="text/javascript">
            var ids = [];
            function assignall() {
                for (i = 0; i < ids.length; i++) {
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
                    xhttp.open("GET", "/BEProjects/AjaxRequest/ASSIGN/" + encodeURI(ids[i]) + "/" + encodeURI(document.getElementById(ids[i]).value) + "/", true);
                    xhttp.send();
                }
            }

            function refrsh(x) {
                var f = document.createElement('form');
                f.setAttribute('method', 'post');
                var i = document.createElement('input');
                i.setAttribute('type', 'hidden');
                i.setAttribute('name', 'yr');
                i.setAttribute('value', x);
                f.appendChild(i);
                f.submit();
            }

            function sorts(x) {
                var f = document.createElement('form');
                f.setAttribute('method', 'post');
                var i = document.createElement('input');
                i.setAttribute('type', 'hidden');
                i.setAttribute('name', 'sort');
                i.setAttribute('value', x);
                f.appendChild(i);
                f.submit();
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
        </script>
        <style>
            @font-face{
                font-family: 'Agency FB';
	            src: url('Fonts/AFB.ttf') format('truetype');
            }   
            @font-face{
                font-family: 'Comic Sans MS';
	            src: url('Fonts/CSM.ttf') format('truetype');
            }
            
            th,td{
                height: 40px;
                line-height: 40px;
                color: #000;
                font-family: 'Comic Sans MS';                  
            }
            th{
                transition: all 0.2s;
            }
            th:hover{
                cursor: pointer;
                color: #269bff;                
            }
            .mem:hover{
                color: #000;
                cursor: default;
            }
            .assbut{
                 width: 100px;height: 40px;line-height: 40px;background:#269bff;float:right;
                 transition: all 0.2s;
                 text-align: center;
                 color: #fff;
                 font-size: 20px;
                 font-family: 'Comic Sans MS';
                 border: 2px solid #269bff;
            }
            .assbut:hover{
                cursor: pointer;
                background: #fff;
                color: #269bff;                
            }
            a{
                text-decoration: none;
                color: #000;
                transition: all 0.2s;
            }
            a:hover{
                color: #269bff;
            }
            select{
                height: 40px;
                line-height: 40px;
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
            #slist{
                position: fixed;left: 40px;bottom: 40px;height: 50px;line-height: 50px;background: #ff5252;color: #fff;width: 100px;font-family: 'Comic Sans MS';text-align: center;
                border: 2px solid #ff5252;
                transition: all 0.3s;
            }
            #slist:hover{
                cursor: pointer;
                background: #fff;
                color: #ff5252;
            }
        </style>
        <script type="text/javascript">
            
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
        
        <div style="font-family: 'Comic Sans MS';height: 60px;line-height: 60px;position: fixed;width: 100%;background: #ff5252;text-align: center;box-shadow: 0px 1px 5px #000;color: #fff;font-size: 20px;">
            <div style="float: left;margin-left: 2%;">Welcome, <?php echo ucwords(strtolower(htmlspecialchars($me['Name']))); if(isset($_SESSION['suflag']) && $_SESSION['suflag']){ echo " (SuperUser)";} ?></div>
            <div class="topbar" onclick="lg()">Logout</div>
            <div class="topbar" onclick="window.location='/BEProjects/SomeChanges/';">Settings</div>
        </div><br><br><br><br>
        <div>
            <div style="width: 80%;margin-left: 10%;"><br><br><br><br><br><br>
                <?php                    
                    $con = mysqli_connect("localhost","root","","viitbe");

                    if(isset($_SESSION['suflag']) && $_SESSION['suflag']){                        
                        $chkuser = $con->prepare("SELECT DISTINCT(year) FROM groups ORDER BY year DESC");
                    }
                    else{
                        $chkuser = $con->prepare("SELECT DISTINCT(year) FROM groups WHERE staffmember=? ORDER BY year DESC");
                        $chkuser->bind_param("i",$_SESSION['staff']);
                    }
                    $chkuser->execute(); 
                    $years = $chkuser->get_result()->fetch_all(); 

                    if(count($years)>0){
                        echo "<select style='width:20%' onchange='refrsh(this.value)'>";
                        for($i=0;$i<count($years);$i++){
                            echo "<option value='".$years[$i]['0']."'";
                            if(isset($_POST['yr']) && $years[$i]['0']==$_POST['yr']){
                                echo "selected";
                            }
                            echo ">".$years[$i]['0']."</option>";
                        }
                        echo "</select>";
                    }

                    $con->close();
                ?><br><br>
        <table style="border-collapse: collapse;border: 2px solid #269bff;width: 100%;text-align: center;font-size: 1.2vw;" border="1">
            <?php         
                if(!isset($_POST['yr'])){
                    $_POST['yr'] = date('Y');
                }
                $con = mysqli_connect("localhost","root","","viitbe");

                //GET LIMITED GROUPS
                if(!isset($_SESSION['suflag']) || !$_SESSION['suflag']){
                    $chkuser = $con->prepare("SELECT id,domain,title FROM groups WHERE staffmember=? AND year=?");
                    $chkuser->bind_param("ii",$_SESSION['staff'],$_POST['yr']);
                    $chkuser->execute(); 
                    $mygroups = $chkuser->get_result()->fetch_all(); 
                    for($i=0;$i<count($mygroups);$i++){
                        $chkuser = $con->prepare("SELECT Id,Name FROM studentlist WHERE mygroup=?");
                        $chkuser->bind_param("i",$mygroups[$i]['0']);
                        $chkuser->execute(); 
                        $assigned[$i] = $chkuser->get_result()->fetch_all();
                    }
                }
                //GET ALL GROUPS
                else{
                    $chkuser = $con->prepare("SELECT id,domain,title,staffmember FROM groups WHERE year=? ORDER BY id");
                    $chkuser->bind_param("i",$_POST['yr']);
                    $chkuser->execute(); 
                    $mygroups = $chkuser->get_result()->fetch_all();
                    for($i=0;$i<count($mygroups);$i++){
                        $chkuser = $con->prepare("SELECT Id,Name FROM studentlist WHERE mygroup=?");
                        $chkuser->bind_param("i",$mygroups[$i]['0']);
                        $chkuser->execute(); 
                        $assigned[$i] = $chkuser->get_result()->fetch_all();
                    }
                    $chkuser = $con->prepare("SELECT Id,Name,domain FROM stafflist WHERE 1 ORDER BY name");
                    $chkuser->execute(); 
                    $allstaff = $chkuser->get_result()->fetch_all();
                }

                if($mygroups==NULL){
                    if($_SESSION['suflag']){
                        echo "No Groups are created yet.";
                    }
                    else{
                        echo "No Groups are assigned to you yet.";
                    }
                }
                else{
                    if(isset($_SESSION['suflag']) && $_SESSION['suflag']){
                        echo "<tr><th style='width:15%;' onclick='sorts(0)'>Group Id</th><th class='mem' style='width:21.25%;'>Members</th><th style='width:21.25%;' onclick='sorts(1)'>Domain</th><th style='width:21.25%;' onclick='sorts(2)'>Title</th><th onclick='sorts(3)'>Staff Assigned</th></tr>";
                    }
                    else{
                        echo "<tr><th style='width:15%;' onclick='sorts(0)'>Group Id</th><th class='mem' style='width:28.33%;'>Members</th><th style='width:28.33%;' onclick='sorts(1)'>Domain</th><th onclick='sorts(2)'>Title</th></tr>";
                    }
                }

                if(isset($_POST['sort'])){
                    for($j=0;$j<count($mygroups);$j++){
                        $min = $j;
                        $minv = $mygroups[$j][$_POST['sort']];
                        for($i=$j;$i<count($mygroups);$i++){
                            if($mygroups[$i][$_POST['sort']]<$minv){
                                $min = $i;
                                $minv = $mygroups[$i][$_POST['sort']];
                            }       
                        } 
                        $temp = $mygroups[$min];
                        $mygroups[$min] = $mygroups[$j];
                        $mygroups[$j] = $temp;
                        $temp = $assigned[$j];
                        $assigned[$j] = $assigned[$min];
                        $assigned[$min] = $temp;
                    }     
                }

                for($i=0;$i<count($mygroups);$i++){
                    if(count($assigned[$i])>=3){
                        echo "<script>ids.push('".htmlspecialchars($mygroups[$i]['0'])."'); </script>";
                        echo "<tr><td style='font-size:30px;'><a href='/BEProjects/Barracks/".htmlspecialchars($mygroups[$i]['0'])."'>".htmlspecialchars($mygroups[$i]['0'])."</a></td>";
                        echo "<td>";
                        for($j=0;$j<count($assigned[$i]);$j++){
                            echo "<a href='/BEProjects/WhosIt/".htmlspecialchars($mygroups[$i]['0'])."/".htmlspecialchars($assigned[$i][$j]['0'])."'>".htmlspecialchars($assigned[$i][$j]['1'])."</a><br>";                        
                        }
                        echo "</td>";
                        echo "<td>".htmlspecialchars($mygroups[$i]['1'])."</td>";
                        echo "<td>".htmlspecialchars($mygroups[$i]['2'])."</td>";
                        if(isset($_SESSION['suflag']) && $_SESSION['suflag']){
                            for($j=0;$j<count($allstaff);$j++){
                                $min = $j;
                                $minv = $allstaff[$j]['2'];
                                for($k=$j;$k<count($allstaff);$k++){
                                    if($allstaff[$k]['2']<$minv){
                                        $min = $k;
                                        $minv = $allstaff[$k]['2'];
                                    }       
                                } 
                                $temp = $allstaff[$min];
                                $allstaff[$min] = $allstaff[$j];
                                $allstaff[$j] = $temp;
                            } 
                            echo "<td><select id='".$mygroups[$i]['0']."'>";
                            for($j=0;$j<count($allstaff);$j++){
                                if($allstaff[$j]['0']==$mygroups[$i]['3']){
                                    echo "<option value='".$allstaff[$j]['0']."' selected>".htmlspecialchars($allstaff[$j]['1'])." - ".htmlspecialchars($allstaff[$j]['2'])."</option>";
                                }
                                else{
                                    echo "<option value='".$allstaff[$j]['0']."'>".htmlspecialchars($allstaff[$j]['1'])." - ".htmlspecialchars($allstaff[$j]['2'])."</option>";
                                }
                            }
                            echo "</select></td>";
                        }
                        echo "</tr>";
                    }
                }

                $con->close();
            ?>            
        </table><br><br><br>
        <?php
            if(isset($_SESSION['suflag']) && $_SESSION['suflag'] && $mygroups!=NULL){
                echo "<div onclick='assignall()' class='assbut'>Assign</div>";
            }
        ?>
        </div>
        </div>
        <?php            
            if(isset($_SESSION['suflag']) && $_SESSION['suflag']){
                echo '<a href="/BEProjects/Everyone/"><div id="slist">Staff List</div></a>';
                echo '<a href="/BEProjects/Everykid/"><div id="slist" style="margin-left:120px;">Student List</div></a>';
            }
        ?>
    </body>
</html>
