<?php
    /*
    require("Support.php");
    $con = mysqli_connect("mysql.hostinger.in","u288517326_nyx","a1b1c1d1","u288517326_viitb");
    
    $chkuser = $con->prepare("SELECT b.title,a.gid FROM pof a,groups b WHERE a.gid=b.id ORDER BY a.ticks DESC,a.dandt");
    $chkuser->execute();  
    $list = get_result_all($chkuser);

    $con->close();
    */
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
            td{
                height: 40px;
                line-height: 40px;
            }
        </style>
    </head>
    <body style="margin: 0px;padding: 0px;">
        <div style="position: fixed;top: 0px;width: 100%;height: 60px;background: #0094ff;line-height: 60px;font-family: 'Agency FB';font-size: 35px;color: #fff;">
            <a style="text-decoration: none;margin-left: 50px;color: #fff;" href="/BEProjects/Home">Lucid</a> - Page of Fame
        </div>
        <br><br><br><br><br>
        <table style="border-collapse: collapse;width: 70%;margin-left: 15%;text-align: center;font-family: 'Comic Sans MS';font-size: 20px;">
            <tr><th style="width: 50%;">Project</th><th>Rank</th></tr>
            <?php
                for($i=0;($i<count($list) && $i<50);$i++)){
                    if($i>=50){
                        break;
                    }
                    echo "<tr><td><a style='text-decoration: none;margin-left: 50px;color: #000;' href='/BEProjects/Barracks/".$list[$i][1]."'>".$list[$i][0]."</a></td><td>".($i+1)."</td></tr>";
                }
            ?>
        </table>
    </body>
</html>
	