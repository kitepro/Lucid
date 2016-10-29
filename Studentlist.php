<?php
    session_start();
    error_reporting(0);
    
    $con = mysqli_connect("localhost","root","","viitbe");
    
    if(isset($_SESSION['suflag']) && $_SESSION['suflag']){        
        $chkuser = $con->prepare("SELECT id FROM superuser");
        $chkuser->execute(); 
        $verify = $chkuser->get_result()->fetch_assoc(); 
        if($verify['id']==$_SESSION['staff']){            
            $chkuser = $con->prepare("SELECT Name,mygroup,id,BE_Division,leader,RollNum FROM studentlist WHERE year=".date('Y')." ORDER BY BE_Division,RollNum");
            $chkuser->execute(); 
            $allstaff = $chkuser->get_result()->fetch_all();
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
            .name{ 
                color: #000;
                transition: all 0.3s;             
            }
            .name:hover{
                cursor: pointer;  
                color: #ff5252;
            }
            a{                
                text-decoration: none;
            }
            .texts{
                height: 30px;
                line-height: 30px;
                width: 17%;
                text-align: center;
            }
        </style>
        <script type="text/javascript">
            var stdlist;
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

            function makehiml(x, y) {
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
                if (y) {
                    xhttp.open("GET", "/BEProjects/AjaxRequest/MAKELEADER/" + x + "/1", true);
                }
                else {
                    xhttp.open("GET", "/BEProjects/AjaxRequest/MAKELEADER/" + x + "/0", true);
                }
                xhttp.send();
            }

            function alter() {
                var nf = document.getElementById('nf').value;
                var patt = new RegExp("^" + nf.toString(), "i");
                var df = document.getElementById('df').value;
                var rf = document.getElementById('rf').value;
                var gf = document.getElementById('gf').value;
                var toadd = "<tr><th>Name</th><th>Division</th><th>Roll No</th><th>Group</th><th>Leader</th></tr>";
                for (i = 0; i < stdlist.length; i++) {
                    if (df != '' && df != stdlist[i][3]) {
                        continue;
                    }
                    if (rf != '' && rf != stdlist[i][5]) {
                        continue;
                    }
                    if (gf != '' && gf != stdlist[i][1]) {
                        continue;
                    }
                    if (nf != '' && !patt.test(stdlist[i][0])) {
                        continue;
                    }
                    toadd += "<tr><td><a href='/BEProjects/WhosIt/" + stdlist[i][2] + "'><label class='name'>" + stdlist[i][0] + "</label></a></td><td>" + stdlist[i][3] + "</td><td>" + stdlist[i][5] + "</td>";
                    if (stdlist[i][1] != null) {
                        toadd += "<td><a href='/BEProjects/Barracks/" + stdlist[i][1] + "'><label class='name'>" + stdlist[i][1] + "</label></a></td>";
                    }
                    else {
                        toadd += "<td>-</td>";
                    }
                    if (stdlist[i][4] == 1) {
                        toadd += "<td><input onchange='makehiml(" + stdlist[i][2] + ",this.checked)' type='checkbox' checked></td>";
                    }
                    else {
                        toadd += "<td><input onchange='makehiml(" + stdlist[i][2] + ",this.checked)' type='checkbox'></td>";
                    }
                    toadd += "</tr>";
                }
                document.getElementById('tabl').innerHTML = toadd.toString();
            }
        </script>
    </head>
    <body style="margin: 0px;padding: 0px;" onload="document.getElementById('loader').style.display = 'none';">
        <div id="loader" style="width: 100%;height: 100vh;position: fixed;top: 0;left: 0;z-index: 10000;background: #fff;line-height: 100vh;text-align: center;font-size: 5vw;"><script>counter();</script></div>
        
        <div style="font-family: 'Comic Sans MS';height: 60px;line-height: 60px;position: fixed;width: 100%;background: #ff5252;text-align: center;box-shadow: 0px 1px 5px #000;color: #fff;font-size: 20px;">
            <div class="topbar" onclick="lg()">Logout</div>
            <div class="topbar" onclick="window.location='/BEProjects/IAmAuthorized/';">Account</div>
        </div><br><br><br><br><br><br><br><br>
        <div style="width: 80%;text-align: center;margin-left: 10%;font-family: 'Comic Sans MS';font-size: 20px;">Filter : <input class="texts" type="text" placeholder="Name" id="nf" onchange="alter()"><input class="texts" id="df" type="text" placeholder="Division" onchange="alter()"><input class="texts" id="rf" type="text" placeholder="Roll No" onchange="alter()"><input class="texts" type="text" placeholder="Group" id="gf" onchange="alter()"></div>
        <br><br>
        <table id="tabl" style="width: 70%;margin-left: 15%;text-align: center;font-family: 'Comic Sans MS';font-size: 25px;">
            <tr><th>Name</th><th>Division</th><th>Roll No</th><th>Group</th><th>Leader</th></tr>
        <?php
            echo "<script>stdlist = ".json_encode($allstaff).";</script>";
            for($i=0;$i<count($allstaff);$i++){
                echo "<tr><td><a href='/BEProjects/WhosIt/".$allstaff[$i][2]."'><label class='name'>".$allstaff[$i][0]."</label></a></td><td>".$allstaff[$i][3]."</td><td>".$allstaff[$i][5]."</td>";
                if($allstaff[$i][1]!=NULL){
                    echo "<td><a href='/BEProjects/Barracks/".$allstaff[$i][1]."'><label class='name'>".$allstaff[$i][1]."</label></a></td>";
                }
                else{
                    echo "<td>-</td>";
                }
                if($allstaff[$i][4]==1){
                    echo "<td><input onchange='makehiml(".$allstaff[$i][2].",this.checked)' type='checkbox' checked></td>";
                }
                else{
                    echo "<td><input onchange='makehiml(".$allstaff[$i][2].",this.checked)' type='checkbox'></td>";
                }
                echo "</tr>";
            }
        ?>
        </table>
    </body>
</html>
