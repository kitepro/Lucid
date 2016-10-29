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

    if(isset($_SESSION['mincounter'])){
        unset($_SESSION['mincounter']);
    }
    if(isset($_SESSION['maxcounter'])){
        unset($_SESSION['maxcounter']);
    }

    $show = FALSE;
    //STAFF
    if(isset($_SESSION['staff'])){
        $chkuser = $con->prepare("SELECT staffmember FROM groups WHERE id=?");
        $chkuser->bind_param("i",$_GET['gid']);
        $chkuser->execute(); 
        $info = $chkuser->get_result()->fetch_assoc();
        if($info!=NULL){
            if($info['staffmember']==$_SESSION['staff']){
                $show = TRUE;      
                $chkuser = $con->prepare("SELECT liveforum FROM stafflist WHERE id=?");
                $chkuser->bind_param("i",$_SESSION['staff']);
                $chkuser->execute(); 
                $lf = $chkuser->get_result()->fetch_assoc();        
            }
            else{
                $_SESSION['homesess'] = "Sorry, We Respect Privacy Of Other Groups";
                header('location:/BEProjects/Home/');
                exit;            
            }
        }
        else{
            $_SESSION['homesess'] = "You Are Not Assigned To Any Group";   
            header('location:/BEProjects/Home/');
            exit;                     
        }
    }

    //STUDENT
    elseif(isset($_SESSION['active'])){
        $chkuser = $con->prepare("SELECT mygroup FROM studentlist WHERE GRNumber=?");
        $chkuser->bind_param("s",$_SESSION['active']);
        $chkuser->execute(); 
        $info = $chkuser->get_result()->fetch_assoc();
        if($info!=NULL){
            if($info['mygroup']==$_GET['gid']){
                $chkuser = $con->prepare("SELECT liveforum FROM studentlist WHERE GRNumber=?");
                $chkuser->bind_param("s",$_SESSION['active']);
                $chkuser->execute(); 
                $lf = $chkuser->get_result()->fetch_assoc();  
                $show = TRUE;             
            }
            else{
                $_SESSION['homesess'] = "You Cant Snoop Into Other Groups";
                header('location:/BEProjects/Home/');
                exit;            
            }
        }
        else{
            $_SESSION['homesess'] = "You Have To Wait For A Group Request";   
            header('location:/BEProjects/Home/');
            exit;                     
        }
    }

    else{
        $_SESSION['homesess'] = "Login First";   
        header('location:/BEProjects/Home/');
        exit;          
    }

    //GET INFO
    if($show){
        $chkuser = $con->prepare("SELECT title FROM groups WHERE id=?");
        $chkuser->bind_param("i",$_GET['gid']);
        $chkuser->execute(); 
        $gn = $chkuser->get_result()->fetch_assoc();       
    }

    $_SESSION['gid'] = $_GET['gid'];

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
            }
            .topbuts:hover{
                background: #fff;
                color: #00a1ff;
                cursor: pointer;
                
            }
            #postspeak{
                font-family: 'Comic Sans MS';
                font-size: 20px;
                background: #00a1ff;
                border: 2px solid #00a1ff;
                color: #fff;
                width: 100px;
                height: 35px;
                line-height: 35px; 
                text-align: center;
                float: right;
                transition: all 0.3s;
            }
            #postspeak:hover{
                color: #00a1ff;
                background: #fff;
                cursor: pointer;  
            }
            #loadmore{
                width: 100%;text-align: center;border: 2px solid #00a1ff;background: #00a1ff;color: #fff;
                height: 45px; line-height: 45px;           
                font-family: 'Comic Sans MS';
                font-size: 20px;     
                transition: all 0.3s;
            }
            #loadmore:hover{
                cursor: pointer;
                background: #fff;
                color: #00a1ff;
            }
            .file{
                width: 18%;
                height: 20vh;
                text-align: center;
                font-size: 20px;
                overflow: hidden;
                padding: 1vh;
                margin-top: 1.3vh;
                float: left; 
                transition: all 0.3s; 
            }
            .file:hover{
                cursor: pointer;     
                color: #00a9ff;         
            }
            #fetchin{
                animation-name: bounce;
                animation-iteration-count: infinite;
                animation-duration: 4s;
                animation-timing-function: ease;
                position: relative;
            }
            @keyframes bounce{
                0% {
                    top: 0px;
                }
                20%{
                    top: 30vh;
                }
                100% {
                    top: 0px;
                }
            }
            .filebut{
                height: 40px;
                line-height: 40px;
                background: #00a1ff;
                color: #fff;
                transition: all 0.3s;
                font-size: 20px;
                width: 120px;
                border: 2px solid #00a1ff;
            }
            .filebut:hover{
                background: #fff;
                color: #00a1ff;     
                cursor: pointer;           
            }
            .disableme{
                height: 40px;
                line-height: 40px;
                background: #00a1ff;
                color: #fff;
                transition: all 0.3s;
                font-size: 20px;
                width: 120px;
                border: 2px solid #00a1ff;  
                opacity: 0.7;              
            }
            .disableme:hover{
                cursor: default;
            }
            #fileloading{
                animation-name: load;
                animation-iteration-count: infinite;
                animation-duration: 4s;
                animation-timing-function: ease;
                position: relative;
            }
            #fileloadingsupprt{
                animation-name: load;
                animation-iteration-count: infinite;
                animation-duration: 4s;
                animation-timing-function: ease;
                animation-delay: 2s;
                position: relative;
            }
            @keyframes load{
                0% {
                    top: 0px;
                    left: 0px;
                }
                12.5%{
                    top: -30px;
                    left: 30px;
                }
                25%{
                    top: 0px;
                    left: 60px;                    
                }
                37.5% {
                    top: -30px;
                    left: 30px;
                }
                50% {
                    top: 0px;
                    left: 0px;
                }
                62.5% {
                    top: -30px;
                    left: -30px;
                }
                75% {
                    top: 0px;
                    left: -60px;
                }
                87.5% {
                    top: -30px;
                    left: -30px;
                }
                100% {
                    top: 0px;
                    left: 0px;
                }            
            }
            
            #uploadbut{
                width: 10%;float: right;border: 2px solid #00a1ff;height: 25px;line-height: 25px;text-align: center;
                background: #00a1ff;
                transition: all 0.3s;
                color: #fff;
                font-family: 'Comic Sans MS';
            }
            
            #uploadbut:hover {
                cursor: pointer;
                color: #00a1ff;
                background: #fff;
            }
            
        </style>
        <script src="/BEProjects/JQ/external/jquery/jquery.js"></script>
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

            function timepass() {
                if (document.getElementById('stick').checked) {
                    document.getElementById('postspeak').textContent = "Speak";
                }
                else {
                    document.getElementById('postspeak').textContent = "Shout";
                }
            }

            var gid = -1;
            var lfi;
            var staff = false;
            function postit() {
                if (document.getElementsByName('content')[0].value == '') {
                    document.getElementById('errlbl').textContent = "Blank Post";
                    return;
                }
                var xhttp;
                if (window.XMLHttpRequest) {
                    xhttp = new XMLHttpRequest();
                } else {
                    xhttp = new ActiveXObject("Microsoft.XMLHTTP");
                }
                xhttp.onreadystatechange = function () {
                    if (xhttp.readyState == 4 && xhttp.status == 200) {
                        document.getElementsByName('content')[0].value = '';
                        if (xhttp.responseText.trim() == '1') {
                            document.getElementById('errlbl').textContent = "Success";
                        }
                        else {
                            document.getElementById('errlbl').textContent = "Some Problem";
                        }
                        window.setTimeout(function () { if (lfi == 1) document.getElementById('errlbl').textContent = "Chat"; else document.getElementById('errlbl').textContent = "Forum"; }, 2000);
                    }
                };
                xhttp.open("POST", "/BEProjects/AjaxRequest/FORUMPOST/" + encodeURI(gid), true);
                xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                if (document.getElementById('stick').checked || staff) {
                    xhttp.send("content=" + encodeURI(document.getElementsByName('content')[0].value) + "&sflag=1");
                }
                else {
                    xhttp.send("content=" + encodeURI(document.getElementsByName('content')[0].value) + "&sflag=0");
                }
            }

            function fl() {
                var xhttp;
                if (window.XMLHttpRequest) {
                    xhttp = new XMLHttpRequest();
                } else {
                    xhttp = new ActiveXObject("Microsoft.XMLHTTP");
                }
                xhttp.onreadystatechange = function () {
                    if (xhttp.readyState == 4 && xhttp.status == 200) {
                        window.location.reload();
                    }
                };
                if (document.getElementById('live').checked) {
                    xhttp.open("GET", "/BEProjects/AjaxRequest/LIVEFORUM/1", true);
                }
                else {
                    xhttp.open("GET", "/BEProjects/AjaxRequest/LIVEFORUM/0", true);
                }
                xhttp.send();
            }

            var page = 1;
            var liveflag = false;
            var timer;
            function alive() {
                if (liveflag) {
                    window.setInterval(function () {
                        var xhttp;
                        if (window.XMLHttpRequest) {
                            xhttp = new XMLHttpRequest();
                        } else {
                            xhttp = new ActiveXObject("Microsoft.XMLHTTP");
                        }
                        xhttp.onreadystatechange = function () {
                            if (xhttp.readyState == 4 && xhttp.status == 200) {
                                var resp = xhttp.responseText.trim();
                                if (resp != '') {
                                    var dandt = resp.substr(0, 20);
                                    var stw = parseInt(resp.substr(20, 1));
                                    var alen = parseInt(resp.substr(21, 2));
                                    var alias = resp.substr(23, alen);
                                    var content = resp.substr(23 + alen);
                                    if (stw == 1) {
                                        document.getElementById('chats').innerHTML = "<div style='width:100%;padding:10px;'><label style='color:#f00;font-size:23px;'>" + alias + "</label> <i>on " + dandt + "</i><br>" + content + "</div>" + document.getElementById('chats').innerHTML;
                                    }
                                    else {
                                        document.getElementById('chats').innerHTML = "<div style='width:100%;padding:10px;'><label style='color:#4ad79b;font-size:23px;'>" + alias + "</label> <i>on " + dandt + "</i><br>" + content + "</div>" + document.getElementById('chats').innerHTML;
                                    }
                                }
                            }
                        };
                        xhttp.open("GET", "/BEProjects/AjaxRequest/CHATS/0", true);
                        xhttp.send();
                    }, 500);
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

            function getchats() {
                var xhttp;
                if (window.XMLHttpRequest) {
                    xhttp = new XMLHttpRequest();
                } else {
                    xhttp = new ActiveXObject("Microsoft.XMLHTTP");
                }
                xhttp.onreadystatechange = function () {
                    if (xhttp.readyState == 4 && xhttp.status == 200) {
                        var resp = xhttp.responseText.trim();
                        resp = resp.split("|<!@phenomenalchars#$>|");
                        for (i = (resp.length - 2); i >= 0; i--) {
                            var dandt = resp[i].substr(0, 20);
                            var stw = resp[i].substr(20, 1);
                            var alen = parseInt(resp[i].substr(21, 2));
                            var alias = resp[i].substr(23, alen);
                            var content = resp[i].substr(23 + alen);
                            if (stw == 1) {
                                document.getElementById('chats').innerHTML = "<div style='width:100%;padding:10px;'><label style='color:#f00;font-size:23px;'>" + alias + "</label> <i>on " + dandt + "</i><br>" + content + "</div>" + document.getElementById('chats').innerHTML;
                            }
                            else {
                                document.getElementById('chats').innerHTML = "<div style='width:100%;padding:10px;'><label style='color:#4ad79b;font-size:23px;'>" + alias + "</label> <i>on " + dandt + "</i><br>" + content + "</div>" + document.getElementById('chats').innerHTML;
                            }
                        }
                        document.getElementById('loader').style.display = 'none';
                        alive();
                    }
                };
                xhttp.open("GET", "/BEProjects/AjaxRequest/CHATS/1", true);
                xhttp.send();
            }

            function addmore10() {
                document.getElementById('morecont').innerHTML = "Loading";
                var xhttp;
                if (window.XMLHttpRequest) {
                    xhttp = new XMLHttpRequest();
                } else {
                    xhttp = new ActiveXObject("Microsoft.XMLHTTP");
                }
                xhttp.onreadystatechange = function () {
                    if (xhttp.readyState == 4 && xhttp.status == 200) {
                        var resp = xhttp.responseText.trim();
                        resp = resp.split("|<!@phenomenalchars#$>|");
                        for (i = 0; i < (resp.length - 1); i++) {
                            var dandt = resp[i].substr(0, 20);
                            var stw = resp[i].substr(20, 1);
                            var alen = parseInt(resp[i].substr(21, 2));
                            var alias = resp[i].substr(23, alen);
                            var content = resp[i].substr(23 + alen);
                            if (stw == 1) {
                                document.getElementById('chats').innerHTML = document.getElementById('chats').innerHTML + "<div style='width:100%;padding:10px;'><label style='color:#f00;font-size:23px;'>" + alias + "</label> <i>on " + dandt + "</i><br>" + content + "</div>";
                            }
                            else {
                                document.getElementById('chats').innerHTML = document.getElementById('chats').innerHTML + "<div style='width:100%;padding:10px;'><label style='color:#4ad79b;font-size:23px;'>" + alias + "</label> <i>on " + dandt + "</i><br>" + content + "</div>";
                            }
                        }
                        document.getElementById('morecont').innerHTML = '<div id="loadmore" onclick="page++;addmore10()">More</div>';
                    }
                };
                xhttp.open("GET", "/BEProjects/AjaxRequest/CHATS/" + page, true);
                xhttp.send();
            }

            var rclick;
            function showfile(x) {
                document.getElementById('filecurtain').style.display = "block";
                document.getElementById('fileinfo').style.display = "block";
                var xhttp;
                if (window.XMLHttpRequest) {
                    xhttp = new XMLHttpRequest();
                } else {
                    xhttp = new ActiveXObject("Microsoft.XMLHTTP");
                }
                xhttp.onreadystatechange = function () {
                    if (xhttp.readyState == 4 && xhttp.status == 200) {
                        var resp = xhttp.responseText.trim();
                        document.getElementById('fetchin').style.display = 'none';
                        document.getElementById('infoready').style.display = 'block';
                        if (resp == '1') {
                            document.getElementById('filename').textContent = "Error..";
                            document.getElementById('filecreator').textContent = "";
                            document.getElementById('filedate').textContent = "";
                            document.getElementById('filesize').textContent = "";
                            document.getElementById('filedownload').style.display = "none";
                        }
                        else {
                            if (resp[0] != '2') {
                                document.getElementById('fileremove').setAttribute('class', 'disableme');
                            }
                            else {
                                document.getElementById('fileremove').setAttribute('onclick', 'rmov()');
                            }
                            document.getElementById('filecreator').textContent = "Uploaded by " + resp.substr(3, parseInt(resp.substr(1, 2)));
                            document.getElementById('filedate').textContent = "Date : " + resp.substr((3 + parseInt(resp.substr(1, 2))), 20);
                            document.getElementById('filename').textContent = resp.substr((25 + parseInt(resp.substr(1, 2))), parseInt(resp.substr((23 + parseInt(resp.substr(1, 2))), 2)));
                            var siz = function () {
                                var sz = resp.substr((25 + parseInt(resp.substr(1, 2))) + parseInt(resp.substr((23 + parseInt(resp.substr(1, 2))), 2)));
                                if ((sz / 1024) < 1) {
                                    return sz.toFixed(2) + "B";
                                }
                                else {
                                    sz /= 1024;
                                    if ((sz / 1024) < 1) {
                                        return sz.toFixed(2) + "KB";
                                    }
                                    else {
                                        sz /= 1024;
                                        return sz.toFixed(2) + "MB";
                                    }
                                }
                            }
                            document.getElementById('filesize').textContent = siz();
                            document.getElementById('filedownload').style.display = "block";
                        }
                    }
                };
                rclick = x.getAttribute('data-filepackage');
                xhttp.open("GET", "/BEProjects/AjaxRequest/GETFILE/" + rclick, true);
                xhttp.send();
            }

            function dwld() {
                var x = document.createElement('iframe');
                document.body.appendChild(x);
                x.setAttribute("src", "/BEProjects/AjaxRequest/DOWNLOADFILE/" + rclick);
                x.style.display = "none";
            }

            function rmov() {
                document.getElementById('filecurtain').style.display = "none";
                document.getElementById('fileinfo').style.display = "none";
                document.getElementById('filelistcurtain').style.display = "block";
                var xhttp;
                if (window.XMLHttpRequest) {
                    xhttp = new XMLHttpRequest();
                } else {
                    xhttp = new ActiveXObject("Microsoft.XMLHTTP");
                }
                xhttp.onreadystatechange = function () {
                    if (xhttp.readyState == 4 && xhttp.status == 200) {
                        var resp = xhttp.responseText.trim();
                        if (resp == "1") {
                            $("#filelist").load("/BEProjects/AjaxRequest/LOADFILELIST/" + gid, function (status, data, handle) {
                                document.getElementById('filelistcurtain').style.display = "none";
                            }); 
                        }
                    }
                };
                xhttp.open("GET", "/BEProjects/AjaxRequest/REMOVEFILE/" + rclick, true);
                xhttp.send();
            }

            var sesupld = 0;
            function upld() {
                document.getElementById('upfile').click();
            }

            function doupld() {
                if (document.getElementById('upfile').files.length != 0) {
                    document.getElementById('fileuploader').style.display = 'block';
                    for (i = 0; i < document.getElementById('upfile').files.length; i++) {
                        var fd = new FormData();
                        fd.append("upld", document.getElementById('upfile').files[i]);
                        fd.append("gid", gid);
                        $.ajax({
                            xhr: function () {
                                var temp = sesupld;
                                sesupld++;
                                document.getElementById('fileuploader').innerHTML += "<div style='overflow:hidden;font-size:20px;line-height:20px;width:70%;text-align:center;margin-left:15%;margin-top:50px;height:20px;border:2px solid #fff;' id='f" + temp + "'><div style='height:20px;width:0%;float:left;position:absolute;background:#fff;'></div><div style='overflow:hidden;position:absolute;z-index:100;color:#22d1ff;width:70%;height:20px;'></div></div>";
                                document.getElementById('f' + temp).childNodes[1].textContent = document.getElementById('upfile').files[i].name;
                                var xhr = new window.XMLHttpRequest();
                                xhr.upload.addEventListener("progress", function (x) {
                                    if (x.lengthComputable) {
                                        document.getElementById('f' + temp).childNodes[0].style.width = (x.loaded * 70 / x.total) + "%";
                                        if (x.loaded >= x.total) {
                                            document.getElementById('fileuploader').removeChild(document.getElementById('f' + temp));
                                        }
                                    }
                                }, false);
                                return xhr;
                            },
                            url: '/BEProjects/AjaxRequest/UPLOADFILE/',
                            dataType: 'text',
                            cache: false,
                            contentType: false,
                            processData: false,
                            data: fd,
                            type: 'post',
                            success: function (resp) {
                                if (resp == "1") {
                                    if (document.getElementById('fileuploader').childNodes.length == 0) {
                                        document.getElementById('fileuploader').style.display = "none";
                                    }
                                    document.getElementById('filelistcurtain').style.display = "block";
                                    $("#filelist").load("/BEProjects/AjaxRequest/LOADFILELIST/" + gid, function (status, data, handle) {
                                        document.getElementById('filelistcurtain').style.display = "none";
                                    });
                                }
                            }
                        });
                    }
                }
                else {
                    document.getElementById('prog').textContent = "No Files Selected";
                }
            }
        </script>
    </head>
    <body style="margin: 0px; padding: 0px;" onload="getchats();">
        <div id="loader" style="width: 100%;height: 100vh;position: fixed;top: 0;left: 0;z-index: 10000;background: #fff;line-height: 100vh;text-align: center;font-size: 5vw;"><script>counter();</script></div>
        <div id="filecurtain" onclick="this.style.display='none';document.getElementById('fileinfo').style.display='none';" style="display: none;position: fixed;top: 0;opacity: 0.6;height: 100vh;width: 100%;background: #000;"></div>
        <div id="fileinfo" style="display: none;border-radius: 10px;overflow: hidden;border: 2px solid #00a1ff;position: fixed;width: 50%;margin-left: 25%;height: 50vh;top: 25vh;background: #fff;padding-top: 5vh;padding-bottom: 5vh;text-align: center;">
            <div id="infoready" style="display: none;">
                <label id="filename" style="font-family: 'Comic Sans MS'; font-size: 35px;font-style: italic;color: #00a1ff"></label><br><br><br>
                <label id="filedate" style="font-family: 'Comic Sans MS'; font-size: 25px;"></label><br><br><br>
                <label id="filecreator" style="font-family: 'Comic Sans MS'; font-size: 25px;"></label><br><br><br>
                <label id="filesize" style="font-family: 'Comic Sans MS'; font-size: 25px;"></label><br><br><br>
                <div id="filedownload" class="filebut" style="float: left;margin-left: calc(50% - 121px);" onclick="dwld()">Download</div>
                <div id="fileremove" class="filebut" style="float: left;margin-left: 2px;">Remove</div>
            </div><br>
            <label id="fetchin" style="font-family: 'Comic Sans MS';font-size: 35px;">Fetching Info</label>
        </div>
        <?php if($lf['liveforum']==1){ echo "<script>liveflag=true;</script>"; }  ?>
        <?php if(isset($_SESSION['staff'])){ echo "<script>staff=true;</script>"; }  ?>
        <?php echo "<script>gid=".htmlspecialchars($_GET['gid']).";</script>"; ?>
        <div style="height: 60px;width: 100%;position: fixed;z-index: 100;background: #00a1ff;box-shadow: 0px 1px 5px #053446">
            <label style="margin-left: 3%;height: 60px;line-height: 60px;font-family: 'Comic Sans MS';color: #fff;"><?php echo htmlspecialchars($gn['title'])." | Forum";  ?></label>
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
        <br><br><br><br><br><br><br><br>
        <div id="speak" style="width: 70%;margin-left: 15%;">
            <label id="errlbl" style="color: #f00;font-family: 'Comic Sans MS';"><?php if($lf['liveforum']==1){ echo "<script>lfi=1;</script>Chat"; } else{ echo "<script>lfi=0;</script>Forum"; } ?></label>
            <textarea name="content" style="width:100%;height: 100px;font-family: 'Comic Sans MS';" placeholder="I want to say something"></textarea>   
            <?php 
                if(isset($_SESSION['active'])){ echo '<input type="checkbox" name="sflag" onchange="timepass()" id="stick" style="margin: 0px;"><label for="stick" style="font-family: \'Comic Sans MS\';font-size: 15px;">&nbsp Visible to staff member too?</label>'; }
                else{ echo '<input type="checkbox" name="sflag" id="stick" style="display:none;" checked>'; }
            ?>
            <div id="postspeak" onclick="postit()"><?php if(isset($_SESSION['active'])){echo "Shout";} else{echo "Post";} ?></div>       
        </div>
        <div style="width: 80%;margin-left: 10%;border: 1px solid #053446;height: 0px;margin-top:50px;"></div>
        <div id="chats" style="width: 70%;margin-left: 15%;height: 85vh;overflow-y: scroll;margin-top: 50px;overflow-x: hidden;">
                                   
        </div>
        <div id="morecont" style="text-align: center;font-family: 'Comic Sans MS';font-size: 20px;width: 10%;height: 45px;line-height: 45px;margin-left: 45%;"><div id="loadmore" onclick="page++;addmore10()">More</div></div>
        <div style="width: 80%;margin-left: 10%;border: 1px solid #053446;height: 0px;margin-top:50px;"></div>
        <input type='checkbox' style='margin-left: 10%;text-align: center;' name='lf' id="live" onchange="fl()" <?php if($lf['liveforum']==1){ echo "checked"; }?> ><label for="live" style="font-family: 'Comic Sans MS';">&nbsp Keep Forum Alive</label><br><br>
        <br><br><br><br>
        <div style="text-align: center;width: 100%;"><label style="font-family: 'Comic Sans MS';font-size: 25px;">Code Repository</label></div>
        <div id="filelistcurtain" style="display: none;border: 2px solid #464646;height: 70vh;width: 80%;margin-left: 10%;background: #000;opacity: 0.4;position: absolute;line-height: 70vh;font-size: 70px;color: #fff;text-align: center;"><label id="fileloading">.</label><label id="fileloadingsupprt">.</label></div>
        <div id="fileuploader" style="display: none;border: 2px solid #464646;height: 70vh;width: 80%;margin-left: 10%;background: #000;opacity: 0.4;position: absolute;line-height: 70vh;font-size: 70px;color: #fff;text-align: center;"></div>
        <div style="border: 2px solid #464646;height: 70vh;width: 80%;margin-left: 10%;" id="filelist">
            <?php                
                $con = mysqli_connect("localhost","root","","viitbe");
                $chkuser = $con->prepare("SELECT name,id FROM uploads WHERE gid=?");
                $chkuser->bind_param("i",$_GET['gid']);
                $chkuser->execute(); 
                $files = $chkuser->get_result()->fetch_all(); 
                $con->close();
                if($files!=NULL){
                    for($i=0;$i<count($files);$i++){
                        echo "<div class='file' data-filepackage='".$files[$i][1]."' onclick='showfile(this)'><div style='height:14.5vh;width:100%;background:url(\"/BEProjects/Images/F".(($i%3)+1).".png\");background-size:contain;background-repeat: no-repeat;background-position: center'></div><div style='overflow:hidden;'>".$files[$i][0]."</div></div>";
                    }
                }
                else{
                    echo "<br><label style='font-family:\"Comic Sans MS\";margin-left:30px;'>Drive Empty</label>";
                }
            ?>
        </div><br>
        <div style="width: 80%;margin-left: 10%;"><input onchange="doupld()" style="visibility: hidden;" type="file" id="upfile" multiple><div onclick="upld()" id="uploadbut">Upload</div></div>
        <br><br><br><br>
    </body>
</html>
