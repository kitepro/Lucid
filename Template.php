<?php
    session_start();
    error_reporting(0);
    if(isset($_SESSION['auth'])){
        unset($_SESSION['auth']);
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
            
            .who{
                width: 50%;
                height: 10vh;
                line-height: 10vh;
                text-align: center;
                font-size:2vw;
                color: #fff;
                transition: all 0.5s;     
                background: #000; 
                font-family: 'Comic Sans MS';          
            }
            .who:hover{
                background: #fff;
                color: #000;
                cursor: pointer;
            }
            .scrl{
                border-radius: 50%;
                background: #000;
                border: 2px solid #000;
                position: fixed;
                right: 2vh;
                transition: all 0.5s;
            }
            .scrl:hover{
                cursor: pointer;
            }
            .back{
                width: 100%;height: 100vh;background: #000;
                z-index: 10;
                position: absolute;
            }
            body{
                width: 100%;   
                overflow-x: hidden; 
            }
            .cont{
                width: 100%;
                height: 100vh;
                position: relative;  
                overflow-y: scroll;  
            }
            .forms{
                width: 70%;
                height: 60vh;
                margin-left: 15%;
                margin-top: 25vh;
                z-index: 10;
                position: relative;
                border-radius: 20px;
            }
            .ftype{
                height: 10vh;
                line-height: 10vh;
                color: #000;
                background: #fff;
                width: 70%;
                margin-left: 15%;
                border-radius: 20px;
                text-align: center;
                font-size:2vw;
                display: none;
                margin-top: 5vh;
                transition: all 0.3s;
                font-family: 'Comic Sans MS';
            }
            .ftype:hover{
                cursor: pointer;
            }
            .acform{
                height: 60vh;
                margin-left: 15%;
                width: 70%;
                background: #fff;
                transition: all 0.3s;
                margin-top: -60vh;
                border-radius: 20px;
            }
            .buttons{
                width: 30%;height: 5vh;margin-top: 2vh;margin-left: 35%;text-align: center;background: #000;
                color: #fff;
                line-height: 5vh;
                font-size: 25px;
                transition: all 0.2s;
                border: 2px solid #000;
                font-family: 'Agency FB';
            }
            .buttons:hover{
                color: #000;
                background: #fff;
                cursor: pointer;
            }
            .srforms,.slforms{
                transition: all 0.2s;
            }
            #curtain1,#curtain2,#curtain3,#curtain4{
                 z-index: 5;
                 position: absolute;
                 background: #fff;
                 border-radius: 20px;
                 margin-top: 0px;
                 margin-left: 0px;
                 height: inherit;
                 width: 70%;
                 display: none;
                 opacity: 0.9;
                 text-align: center;
                 line-height: 60vh;
                 font-size: 30px;
                 font-family: 'Agency FB';
            }
            .ips{
                font-family:'Agency FB';font-size: 20px ;text-align: center;height: 5vh;line-height: 5vh;width: 50%;margin-left: 25%;
            }
            #err{
                transition: all 0.2s;
                color: #fff;
                text-align: center;
                line-height: 5vh;
                font-style: italic;
            }
            .topbt{
                z-index: 7;float: right;color: #fff;line-height: 9vh;height: 9vh;width: 10%;text-align: center;
                font-family: 'Comic Sans MS';
                border: 0.5vh solid #000;
            }
            .topbt:hover{
                cursor: pointer;
                color: #000;
                background: #fff;
            }
            #s1img:hover{
                animation-name: rotat;
                animation-duration: 1s;
                animation-iteration-count: infinite;
                animation-timing-function: ease;
            }
            @keyframes rotat{
                from{transform: rotateY(0deg);}
                to{transform: rotateY(360deg);}
            }
            th,td{
                padding: 1vh;
                height: 7.5vh;
            }
            .trs{
                transition: all 0.3s;
            }
            .trs:hover{
                cursor: pointer;
                background: #4fd189;
                color: #fff;
            }
        </style>
        <script src="/BEProjects/JQ/external/jquery/jquery.js"></script>
        <script src="/BEProjects/underscore.js"></script>
        <script type="text/javascript">
            var f = false;

            function disp(x) {
                document.getElementById("strt").style.display = "none";
                document.getElementsByClassName("acform")[0].style.marginTop = "-60vh";
                document.getElementsByClassName("acform")[1].style.marginTop = "-60vh";
                document.getElementsByClassName("acform")[2].style.marginTop = "-60vh";
                document.getElementsByClassName("acform")[3].style.marginTop = "-60vh";
                document.getElementsByClassName("acform")[1].style.display = "block";
                document.getElementsByClassName("acform")[0].style.display = "block";
                document.getElementsByClassName("acform")[2].style.display = "block";
                document.getElementsByClassName("acform")[3].style.display = "block";

                if (x == 1) {
                    document.getElementsByClassName("ftype")[0].style.display = "block";
                    document.getElementsByClassName("ftype")[1].style.display = "block";
                    document.getElementsByClassName("ftype")[2].style.display = "none";
                    document.getElementsByClassName("ftype")[3].style.display = "none";
                }
                else if (x == 2) {
                    document.getElementsByClassName("ftype")[0].style.display = "none";
                    document.getElementsByClassName("ftype")[1].style.display = "none";
                    document.getElementsByClassName("ftype")[2].style.display = "block";
                    document.getElementsByClassName("ftype")[3].style.display = "block";
                }
            }

            function theform(x) {
                disp(3);
                if (x == 1) {
                    document.getElementsByClassName("acform")[0].style.marginTop = "15vh";
                    document.getElementsByClassName("acform")[0].style.display = "block";
                    document.getElementsByClassName("acform")[1].style.display = "none";
                    document.getElementsByClassName("acform")[2].style.display = "none";
                    document.getElementsByClassName("acform")[3].style.display = "none";
                }
                else if (x == 2) {
                    document.getElementsByClassName("acform")[1].style.marginTop = "15vh";
                    document.getElementsByClassName("acform")[1].style.display = "block";
                    document.getElementsByClassName("acform")[0].style.display = "none";
                    document.getElementsByClassName("acform")[2].style.display = "none";
                    document.getElementsByClassName("acform")[3].style.display = "none";
                }
                else if (x == 3) {
                    document.getElementsByClassName("acform")[2].style.marginTop = "15vh";
                    document.getElementsByClassName("acform")[2].style.display = "block";
                    document.getElementsByClassName("acform")[0].style.display = "none";
                    document.getElementsByClassName("acform")[1].style.display = "none";
                    document.getElementsByClassName("acform")[3].style.display = "none";
                }
                else {
                    document.getElementsByClassName("acform")[3].style.marginTop = "15vh";
                    document.getElementsByClassName("acform")[3].style.display = "block";
                    document.getElementsByClassName("acform")[0].style.display = "none";
                    document.getElementsByClassName("acform")[1].style.display = "none";
                    document.getElementsByClassName("acform")[2].style.display = "none";
                }

            }

            function sregstep1() {
                document.getElementById('curtain1').style.display = "block";
                var xhttp;
                if (window.XMLHttpRequest) {
                    xhttp = new XMLHttpRequest();
                } else {
                    xhttp = new ActiveXObject("Microsoft.XMLHTTP");
                }
                xhttp.onreadystatechange = function () {
                    if (xhttp.readyState == 4 && xhttp.status == 200) {
                        document.getElementById('curtain1').style.display = "none";
                        var response = xhttp.responseText.trim();
                        if (response == "1") {
                            for (i = 0; i < document.getElementsByClassName("srforms").length; i++) {
                                document.getElementsByClassName("srforms")[i].style.marginTop = (parseInt(document.getElementsByClassName("srforms")[i].style.marginTop) - 60).toString() + "vh";
                            }
                        }
                        else if (response == "2") {
                            document.getElementById('err').innerHTML = "<b>Error Sending an Email</b>";
                            doit();

                        }
                        else if (response == "3") {
                            document.getElementById('err').innerHTML = "<b>Already Registered</b>";
                            doit();

                        }
                        else if (response == "4") {
                            document.getElementById('err').innerHTML = "<b>Email didnt match out database</b>";
                            doit();

                        }
                        else {
                            document.getElementById('err').innerHTML = "<b>GR Number or Roll Number didnt matched our database</b>";
                            doit();
                        }
                    }
                };
                xhttp.open("GET", "/BEProjects/AjaxRequest/SR/" + encodeURI(document.getElementById('grn').value) + "/" + encodeURI(document.getElementById('rn').value) + "/" + encodeURI(document.getElementById('email').value) + "/", true);
                xhttp.send();
            }

            function tregstep1() {
                document.getElementById('curtain3').style.display = "block";
                var xhttp;
                if (window.XMLHttpRequest) {
                    xhttp = new XMLHttpRequest();
                } else {
                    xhttp = new ActiveXObject("Microsoft.XMLHTTP");
                }
                xhttp.onreadystatechange = function () {
                    if (xhttp.readyState == 4 && xhttp.status == 200) {
                        document.getElementById('curtain3').style.display = "none";
                        var response = xhttp.responseText.trim();
                        if (response[0] == "1") {
                            document.getElementById('sname').value = response.substring(1);
                            for (i = 0; i < document.getElementsByClassName("trforms").length; i++) {
                                document.getElementsByClassName("trforms")[i].style.marginTop = (parseInt(document.getElementsByClassName("trforms")[i].style.marginTop) - 60).toString() + "vh";
                            }
                        }
                        else if (response == "2") {
                            document.getElementById('err').innerHTML = "<b>Error Sending an Email</b>";
                            doit();

                        }
                        else if (response == "3") {
                            document.getElementById('err').innerHTML = "<b>Already Registered</b>";
                            doit();

                        }
                        else {
                            document.getElementById('err').innerHTML = "<b>Employee Id didnt matched our database</b>";
                            doit();
                        }
                    }
                };
                xhttp.open("GET", "/BEProjects/AjaxRequest/TR/" + encodeURI(document.getElementById('eid').value) + "/", true);
                xhttp.send();
            }

            function sregstep2() {
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
                            document.getElementById('err').innerHTML = "<b>You can now login</b>";
                            doit();
                            theform(2);
                        }
                        else if (response == "2") {
                            document.getElementById('err').innerHTML = "<b>Password mismatch</b>";
                            doit();
                        }
                        else if (response == "3") {
                            document.getElementById('err').innerHTML = "<b>Invalid pin</b>";
                            doit();

                        }
                    }
                };
                xhttp.open("GET", "/BEProjects/AjaxRequest/AUTH/" + encodeURI(document.getElementById('pin').value) + "/" + encodeURI(document.getElementById('pass').value) + "/" + encodeURI(document.getElementById('repass').value) + "/", true);
                xhttp.send();
            }

            function tregstep2() {
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
                            document.getElementById('err').innerHTML = "<b>You can now login</b>";
                            doit();
                            theform(4);
                        }
                        else if (response == "2") {
                            document.getElementById('err').innerHTML = "<b>Password mismatch</b>";
                            doit();
                        }
                        else if (response == "3") {
                            document.getElementById('err').innerHTML = "<b>Invalid pin</b>";
                            doit();

                        }
                    }
                };
                xhttp.open("GET", "/BEProjects/AjaxRequest/AUTH/" + encodeURI(document.getElementById('tpin').value) + "/" + encodeURI(document.getElementById('tpass').value) + "/" + encodeURI(document.getElementById('trepass').value) + "/", true);
                xhttp.send();
            }

            function slog() {
                document.getElementById('curtain2').style.display = "block";
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
                            window.location = "/BEProjects/StudentHangout/";
                        }
                        else if (response == "2") {
                            document.getElementById('err').innerHTML = "<b>Invalid Username Or Password</b>";
                            doit();
                            document.getElementById('curtain2').style.display = "none";
                        }
                    }
                };
                xhttp.open("POST", "/BEProjects/AjaxRequest/LOGIN/", true);
                xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhttp.send("grnl=" + encodeURI(document.getElementById('grnl').value) + "&passl=" + encodeURI(document.getElementById('passl').value));
            }

            function tlog() {
                document.getElementById('curtain4').style.display = "block";
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
                            window.location = "/BEProjects/IAmAuthorized/";
                        }
                        else if (response == "2") {
                            document.getElementById('err').innerHTML = "<b>Invalid Username Or Password</b>";
                            doit();
                            document.getElementById('curtain4').style.display = "none";
                        }
                    }
                };
                xhttp.open("POST", "/BEProjects/AjaxRequest/AUTHORIZE/", true);
                xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhttp.send("eidl=" + encodeURI(document.getElementById('teidl').value) + "&tpassl=" + encodeURI(document.getElementById('tpassl').value));
            }


            function doit() {
                document.getElementById('err').style.marginTop = "10vh";
                window.setTimeout(function () {
                    document.getElementById('err').style.marginTop = "0vh";
                }, 5000);
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

            var slid;
            var scount = 0;
            function slides() {
                slid = window.setInterval(function () {
                    if (scount == 0) {
                        document.getElementById('s4').style.marginRight = "880vw";
                        scount++;
                        return;
                    }
                    scount++;
                    if (scount == 4) {
                        document.getElementById('s4').style.marginRight = "840vw";
                        scount = 0;
                        return;
                    }
                    document.getElementById('s4').style.marginRight = (parseInt(document.getElementById('s4').style.marginRight.substring(0, (document.getElementById('s4').style.marginRight.length - 2))) + 40).toString() + "vw";

                }, 2000);
            }

            var posx = [], posy = [], ix = [], iy = [];
            var rx = [], ry = [];
            function start() {
                document.getElementById("random").style.zIndex = -100;
                document.getElementById("random").height = document.body.clientHeight * 0.9;
                document.getElementById("random").style.height = document.body.clientHeight * 0.9;
                document.getElementById("random").width = document.body.clientWidth;
                document.getElementById("random").style.width = document.body.clientWidth;

                for (var i = 0; i <= 79; i++) {
                    posx.push(0);
                    posy.push(0);
                    rx.push(0);
                    ry.push(0);
                    ix.push(0);
                    iy.push(0);
                    ix[i] = posx[i] = Math.floor(Math.random() * document.getElementById("random").width);
                    rx[i] = Math.floor(Math.random() * document.getElementById("random").width);
                    iy[i] = posy[i] = Math.floor(Math.random() * document.getElementById("random").height);
                    ry[i] = Math.floor(Math.random() * document.getElementById("random").height);
                }

                var ctx = document.getElementById("random").getContext("2d");
                ctx.textAlign = "center";
                ctx.font = "60px 'Agency FB'";
                setInterval(blips, 10);
            }
            var f = 0;
            function blips() {
                var ctx = document.getElementById("random").getContext("2d");
                ctx.clearRect(0, 0, document.getElementById("random").width, document.getElementById("random").height);
                var w = document.getElementById("random").width / 2;
                ctx.fillStyle = "rgba(255, 255,255,1)";
                ctx.font = "60px 'Agency FB'";
                ctx.fillText("Lucid", w, document.getElementById("random").height * 0.16);
                ctx.stroke(); 
                ctx.font = "30px 'Agency FB'";
                ctx.fillText("Lets make things easy", w, document.getElementById("random").height * 0.22);
                for (var i = 0; i <= 79; i++) {
                    ctx.beginPath();
                    if (posx[i] >= document.getElementById("random").width || posx[i] <= 0 || posy[i] >= document.getElementById("random").height || posy[i] <= 0) {
                        ix[i] = posx[i];
                        rx[i] = Math.floor(Math.random() * document.getElementById("random").width);
                        iy[i] = posy[i];
                        ry[i] = Math.floor(Math.random() * document.getElementById("random").height);
                    }
                    ctx.beginPath();
                    posx[i] = posx[i] + (rx[i] - ix[i]) / 2000;
                    posy[i] = posy[i] + (ry[i] - iy[i]) / 2000;
                    ctx.arc(posx[i], posy[i], 3, 0, 2 * Math.PI, false);
                    ctx.lineWidth = 2;
                    ctx.fillStyle = "rgba(0, 0, 255, 0.5)"; // #018cd2
                    ctx.fill();
                    for (var j = 0; j <= 49; j++) {
                        if (Math.abs(posx[i] - posx[j]) <= 40 && Math.abs(posy[i] - posy[j]) <= 40) {
                            ctx.beginPath();
                            ctx.moveTo(posx[i], posy[i]);
                            ctx.strokeStyle = "rgba(0, 0, 255, 0.7)";
                            ctx.lineTo(posx[j], posy[j]);
                            ctx.stroke();
                        }
                    }
                }
            }
            var ctx1, ctx2, c1 = 100, c2 = 100, rx1 = 0, ry1 = 0, rx2 = 0, ry2 = 0, r1 = 1, r2 = 1;
            function slide2() {
                document.getElementById("s21").height = document.body.clientHeight * 0.6;
                document.getElementById("s21").width = document.getElementById('s21c').clientWidth;
                document.getElementById("s22").height = document.body.clientHeight * 0.6;
                document.getElementById("s22").width = document.getElementById('s22c').clientWidth;
                ctx1 = document.getElementById("s21").getContext("2d");
                ctx2 = document.getElementById("s22").getContext("2d");
                window.setInterval(function () {
                    ctx1.clearRect(0, 0, document.getElementById("s21").width, document.getElementById("s21").height);
                    if (c1 == 100) {
                        r1 = 1;
                        rx1 = Math.floor(Math.random() * (document.getElementById("s21").width - 30) + 15);
                        ry1 = document.getElementById("s21").height;
                        c1 = 0;
                    }
                    else {
                        ctx1.beginPath();
                        ctx1.arc(rx1, ry1, r1, 0, 2 * Math.PI, false);
                        ctx1.fillStyle = "#4fd189";
                        r1 += 0.05;
                        ctx1.lineWidth = 2;
                        ctx1.fill();
                        ry1--;
                        if (ry1 <= (r1 + 2)) {
                            c1 = 100;
                        }
                    }
                    var cx = 0;
                    var yf = true;
                    ctx1.beginPath();
                    ctx1.moveTo(0, document.getElementById("s21").height - 10);
                    for (i = 1; i <= 7; i++) {
                        if (yf) {
                            cpy = document.getElementById("s21").height;
                            yf = false;
                        }
                        else {
                            cpy = document.getElementById("s21").height - 20;
                            yf = true;
                        }
                        cx = (cx + (44));
                        ctx1.quadraticCurveTo((cx - 22), cpy, cx, document.getElementById("s21").height - 10);
                    }
                    ctx1.strokeStyle = "#4fd189";
                    ctx1.stroke();
                }, (Math.random() * 5 + 5));
                window.setInterval(function () {
                    ctx2.clearRect(0, 0, document.getElementById("s22").width, document.getElementById("s22").height);
                    if (c2 == 100) {
                        r2 = 1;
                        rx2 = Math.floor(Math.random() * (document.getElementById("s22").width - 30) + 15);
                        ry2 = document.getElementById("s22").height;
                        c2 = 0;
                    }
                    else {
                        ctx2.beginPath();
                        ctx2.arc(rx2, ry2, r2, 0, 2 * Math.PI, false);
                        ctx2.fillStyle = "#4fd189";
                        r2 += 0.05;
                        ctx2.lineWidth = 2;
                        ctx2.fill();
                        ry2--;
                        if (ry2 <= (r2 + 2)) {
                            c2 = 100;
                        }
                    }
                    var cx = 0;
                    var yf = true;
                    ctx2.beginPath();
                    ctx2.moveTo(0, document.getElementById("s22").height - 10);
                    for (i = 1; i <= 7; i++) {
                        if (yf) {
                            cpy = document.getElementById("s22").height;
                            yf = false;
                        }
                        else {
                            cpy = document.getElementById("s22").height - 20;
                            yf = true;
                        }
                        cx = (cx + (44));
                        ctx2.quadraticCurveTo((cx - 22), cpy, cx, document.getElementById("s22").height - 10);
                    }
                    ctx2.strokeStyle = "#4fd189";
                    ctx2.stroke();
                }, (Math.random() * 5 + 5));
            }
            var data;
            function showdet(x) {
                document.getElementById('detailcurtain').style.display = "block";
                document.getElementById('detailblock').style.display = "block";
                document.getElementById('title').textContent = data[x][0];
                document.getElementById('details').textContent = data[x][2];
            }

            function hidedet() {
                document.getElementById('detailcurtain').style.display = "none";
                document.getElementById('detailblock').style.display = "none";
            }
        </script>
    </head>
    <body style="margin: 0px;padding: 0px;" onload="document.getElementById('loader').style.display = 'none';slides();start();slide2()">
        <div id="loader" style="width: 100%;height: 100vh;position: fixed;top: 0;left: 0;z-index: 10000;background: #fff;line-height: 100vh;text-align: center;font-size: 5vw;"><script>counter();</script></div>
            
            <div class="cont" id="c2">
                <div id="0" class="back">
                    <canvas id="random" style="height: 90vh;width: 100%;position: absolute;top: 10vh;"></canvas>
                    <div style="position: absolute;z-index: 7;height: 10vh;width: 100%;background: #000">
                        <div style="position: absolute;z-index: 7;line-height: 10vh;width: 50%;height: 10vh;color: #fff;float: left;"> </div>
                        <a href="/BEProjects/OnlyForYou"><div class="topbt">Credits</div></a>
                        <a href="/BEProjects/TheyWavedTheirFlag"><div class="topbt">POF</div></a>
                        <a href="/BEProjects/FAQ"><div class="topbt">FAQ</div></a>
                    </div>
                    
                    <div style="position: absolute;height: 50vh;width: 40%;left: 30%;top: 35vh;background: #fff;border-radius: 20px;border: 2px solid #000;overflow: hidden;">
                        <div style="width: 2000vw;overflow: hidden;height: 50vh;position: absolute;margin-left: -1000vw;">
                        <div id="s4" style="transition: all 0.9s;float: right;margin-right: 840vw;height: 50vh;width: 40vw;background: #fff;text-align: center;font-family: 'Comic Sans MS';font-size: 30px;padding-top: 8vh;">
                            <i>Get in Page of Fame.</i><br><br>
                            <img id="s4img" style="height: 20vh;width: 20vh;" src="/BEProjects/Images/s4.png" alt="Group">
                        </div>
                        <div id="s3" style="transition: all 0.9s;float: right;height: 50vh;width: 40vw;background: #fff;text-align: center;font-family: 'Comic Sans MS';font-size: 30px;padding-top: 8vh;">
                            <i>Set your milestones.</i><br><br>
                            <img id="s3img" style="height: 20vh;width: 20vh;" src="/BEProjects/Images/s3.png" alt="Group">
                        </div>
                        <div id="s2" style="transition: all 0.9s;float: right;height: 50vh;width: 40vw;background: #fff;text-align: center;font-family: 'Comic Sans MS';font-size: 30px;padding-top: 8vh;">
                            <i>Divide and conquer the job.</i><br><br>
                            <img id="s2img" style="height: 20vh;width: 20vh;" src="/BEProjects/Images/s2.png" alt="Group">
                        </div>
                        <div id="s1" style="transition: all 0.9s;float: right;height: 50vh;width: 40vw;background: #fff;text-align: center;font-family: 'Comic Sans MS';font-size: 30px;padding-top: 8vh;">
                            <i>The only place you will need.</i><br><br>
                            <img id="s1img" style="height: 20vh;width: 20vh;" src="/BEProjects/Images/s1.png" alt="Brainstorming">
                        </div>
                        </div>
                    </div>
                </div>
                <?php
                    $con = mysqli_connect("localhost","root","","viitbe");
                    $chkuser = $con->prepare("SELECT title,domain,details FROM groups ORDER BY id DESC LIMIT 7");
                    $chkuser->execute(); 
                    $groups = $chkuser->get_result()->fetch_all(); 
                    echo "<script>data = ".json_encode($groups)."</script>";
                    $con->close();
                ?>
                <div id="1" style="width: 100%;height: 100vh;text-align: center;position: absolute;margin-top: 100vh;z-index: 8;overflow: hidden;">
                    <div id="detailcurtain" onclick="hidedet()" style="height: 100vh;width: 100%;display: none;position: absolute;opacity: 0.4;background: #000;"></div>
                    <div id="detailblock" style="display: none;height: 60vh;width: 50%;margin-left: 25%;z-index: 9000;margin-top: 20vh;background: #fff;border-radius: 30px;position: absolute;"><br><br>
                        <label id="title" style="font-family: 'Comic Sans MS';font-size: 25px;"></label><br><br>
                        <label id="details" style="font-family: 'Comic Sans MS';font-size: 20px;"></label><br>
                    </div><br>
                    <label style="font-size: 35px;font-family: 'Agency FB';">Recently Added Projects</label><br><br>
                    <div id="s21c" style="height: 80vh;width: calc(20% - 2px);float: left;">
                        <canvas id="s21" style="height: 60vh;margin-top: 0vh;width: 100%;"></canvas>
                    </div>
                    <div style="float: left;border: 2px solid #4fd189;height: 80vh;border-radius: 25px;width: 60%;">
                    <table style="font-family: 'Comic Sans MS';text-align: center;width: 100%;font-size: 2vw;border-collapse:collapse">
                        <tr><th style="width: 50%;">Title</th><th>Domain</th></tr>
                        <?php
                            for($i=0;$i<=6;$i++){
                                if(isset($groups[$i])){
                                    echo "<tr class='trs' onclick='showdet(".$i.")'><td>".$groups[$i][0]."</td><td>".$groups[$i][1]."</td></tr>";
                                }
                            }
                        ?>
                    </table>
                    </div>
                    <div id="s22c" style="height: 80vh;width: calc(20% - 2px);float: left;">
                        <canvas id="s22" style="height: 60vh;margin-top: 0vh;width: 100%;"></canvas>
                    </div>
                </div>
                <div id="2" style="width: 100%;height: 100vh;background: #000;position: absolute;margin-top: 200vh;overflow: hidden">
                    <div class="who" style="float: left;position: absolute;z-index: 10;" onclick="disp(1);">Student</div>
                    <div class="who" style="float: right;margin-left: 50%;position: absolute;z-index: 10;" onclick="disp(2)">Staff</div>
                    <div id="strt" style="position: absolute;width: 100%;height: 90vh;line-height: 90vh;text-align: center;font-family: 'Agency FB';font-size: 50px;color: #fff;">Who are you?</div>
                    <div id="err" style="background: #f00;height:5vh;width: 100%;position: absolute;margin-top: -5vh;z-index: 7;"></div>
                    <div style="overflow: hidden;height: 90vh;width: 70%;position: absolute;margin-left: 15%;margin-top: 10vh;z-index: 5">
                        <div id="srform" class="acform" style="overflow: hidden;">
                            <div id="curtain1">Sending Email...</div>
                            <div class="srforms" style="height: inherit;width: 100%;margin-top: 0vh; ">
                            <input id="grn" class="ips" type="text" onchange="" style="margin-top: 10vh;" placeholder="GR Number">
                            <input id="email" class="ips" type="text" onchange="" style="margin-top: 2vh;" placeholder="Registered Email">
                            <input id="rn" class="ips" type="text" onchange="" style="margin-top: 2vh;" placeholder="Roll No"><br><br>
                            <script>document.write('<div class="buttons" onclick="sregstep1()">Proceed</div>');</script>   
                            </div>      
                            <div class="srforms" style="height: inherit;width: 100%;margin-top: 60vh; "><br><br><br>
                            <input type="password" id="pin" class="ips" onchange="" style="margin-top: 2vh;" placeholder="PIN Sent on Email">
                            <input type="password" id="pass" class="ips" onchange="" style="margin-top: 2vh;" placeholder="Password">
                            <input type="password" id="repass" class="ips" onchange="" style="margin-top: 2vh;" placeholder="ReEnter Password"><br><br>
                            <script>document.write('<div class="buttons" onclick="sregstep2()">Register</div>');</script>                                   
                            </div>                   
                        </div>
                        <div id="slform" class="acform" style="overflow: hidden;">
                            <div id="curtain2">Logging In...</div>
                            <div class="slforms" style="height: inherit;width: 100%;margin-top: 0vh; ">
                            <input id="grnl" class="ips" type="text" onchange="" style="margin-top: 15vh;" placeholder="GR Number">
                            <input id="passl" class="ips" type="password" onchange="" style="margin-top: 2vh;" placeholder="Password"><br><br>
                            <script>document.write('<div class="buttons" onclick="slog()">Login</div>');</script>  
                            </div>
                        </div>
                        <div id="trform" class="acform" style="overflow: hidden;">
                            <div id="curtain3">Sending Email...</div>
                            <div class="trforms" style="height: inherit;width: 100%;margin-top: 0vh; ">
                            <input id="eid" class="ips" type="text" onchange="" style="margin-top: 20vh;" placeholder="Employee Id"><br><br>
                            <script>document.write('<div class="buttons" onclick="tregstep1()">Proceed</div>');</script>   
                            </div>      
                            <div class="trforms" style="height: inherit;width: 100%;margin-top: 60vh; ">
                            <input type="text" id="sname" class="ips" onchange="" style="margin-top: 10vh;" disabled>
                            <input type="password" id="tpin" class="ips" onchange="" style="margin-top: 2vh;" placeholder="PIN Sent on Email">
                            <input type="password" id="tpass" class="ips" onchange="" style="margin-top: 2vh;" placeholder="Password">
                            <input type="password" id="trepass" class="ips" onchange="" style="margin-top: 2vh;" placeholder="ReEnter Password"><br><br>
                            <script>document.write('<div class="buttons" onclick="tregstep2()">Register</div>');</script>                                   
                            </div>         
                        </div>
                        <div id="tlform" class="acform" style="overflow: hidden;">
                            <div id="curtain4">Logging In...</div>
                            <div class="slforms" style="height: inherit;width: 100%;margin-top: 0vh; ">
                            <input id="teidl" class="ips" type="text" onchange="" style="margin-top: 15vh;" placeholder="Employee Id">
                            <input id="tpassl" class="ips" type="password" onchange="" style="margin-top: 2vh;" placeholder="Password"><br><br>
                            <script>document.write('<div class="buttons" onclick="tlog()">Login</div>');</script>  
                            </div>
                        </div>
                        <div id="forms" class="forms">
                            <div class="ftype" onclick="theform(1)">Student Registration</div>
                            <div class="ftype" onclick="theform(2)">Student Login</div>
                            <div class="ftype" onclick="theform(3)">Staff Registration</div>
                            <div class="ftype" onclick="theform(4)">Staff Login</div>
                        </div>
                    </div>
                </div>
            </div>
        <script type="text/javascript">
            var l = 0;
            var x = 0;
            var doscroll = true;
            var lastpos = 0;
            $('#c2').on('scroll', _.debounce(function () {
                if (doscroll) {
                    lastpos = $('#0').height() * l;
                    if ($('#c2').scrollTop() > lastpos) {
                        l++;
                        $('#c2').animate({ scrollTop: $('#0').height() * l }, '500');
                    }
                    else {
                        l--;
                        $('#c2').animate({ scrollTop: $('#0').height() * l }, '500');
                    }
                    doscroll = false;
                    window.setTimeout(function () { doscroll = true; }, 700);
                }
            }, 100));

            function stlogs() {
                $('#c3').animate({ scrollTop: 2*$('#0').height()}, '500');
            }
        </script>
        <?php
            if(isset($_SESSION['homesess'])){
                echo "<script>stlogs();document.getElementById('err').innerHTML = '<b>".$_SESSION['homesess']."</b>';doit();</script>";
                unset($_SESSION['homesess']);
            }
        ?>
    </body>
</html>
