<?php
    session_start();    
    if($_GET['type']=="SR"){
        if(isset($_GET['grnum']) && isset($_GET['rnum']) && isset($_GET['email'])){          
            $con = mysqli_connect("localhost","root","","viitbe");
            $chkuser = $con->prepare("SELECT RollNum,Password,email FROM studentlist WHERE GRNumber=? AND RollNum=?");
            $chkuser->bind_param("si",$_GET['grnum'],$_GET['rnum']);
            $chkuser->execute();  
            $student = $chkuser->get_result()->fetch_assoc();
            if($student!=NULL && $student['Password']==NULL && $_GET['email']==$student['email']){
                $_SESSION['auth'] = rand(0,9).rand(0,9).rand(0,9).rand(0,9);
                $_SESSION['grnum'] = $_GET['grnum'];
                require 'Mailer/PHPMailerAutoload.php';
                $mail = new PHPMailer(TRUE);
                $mail->IsSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->Port = 587;
                $mail->SMTPSecure = 'tls';
                $mail->SMTPAuth = true;
                $mail->Username = "parkadia12@gmail.com";
                $mail->Password = "a1b1c1d1";
                $mail->SetFrom('nyxassasin12@gmail.com', 'HelpYouLink');
                $mail->AddAddress($_GET['email']);
                $mail->Subject = 'WeMake Email Verification';
                $mail->Body = "Email Verification Code : ".$_SESSION['auth'];                               
                if (!$mail->send()) {
                    $mail->ErrorInfo;
                    echo "2";
                } else {
                    echo "1";
                }
            }
            else if($student!=NULL && $_GET['email']!=$student['email']){
                echo "4";
            }
            else if($student!=NULL){
                echo "3";
            }
            $con->close();     
        }
    }

    elseif($_GET['type']=="TR"){
        if(isset($_GET['eid'])){          
            $con = mysqli_connect("localhost","root","","viitbe");
            $chkuser = $con->prepare("SELECT Name,Password FROM stafflist WHERE eid=?");
            $chkuser->bind_param("s",$_GET['eid']);
            $chkuser->execute();  
            $student = $chkuser->get_result()->fetch_assoc();
            if($student!=NULL && $student['Password']==NULL){
                $_SESSION['auth'] = rand(0,9).rand(0,9).rand(0,9).rand(0,9);
                $_SESSION['eid'] = $_GET['eid'];
                require 'Mailer/PHPMailerAutoload.php';
                $mail = new PHPMailer;
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->Port = 587;
                $mail->SMTPSecure = 'tls';
                $mail->SMTPAuth = true;
                $mail->Username = "nyxassasin12@gmail.com";
                $mail->Password = "Nyxassasin12.";
                $mail->setFrom('nyxassasin12@gmail.com', 'HelpYouLink');
                //STAFF EMAIL
                $mail->addAddress("nyxassasin12@gmail.com");
                //
                $mail->Subject = 'WeMake Email Verification';
                $mail->Body = "Email Verification Code : ".$_SESSION['auth'];
                if (!$mail->send()) {
                    echo "2";
                } else {
                    echo "1";
                    echo $student['Name'];
                }
            }
            else if($student!=NULL){
                echo "3";
            }
            else{
                echo "4";
            }
            $con->close();     
        }
    }

    elseif($_GET['type']=="AUTH"){
        if(isset($_GET['auth'])){
            if(isset($_SESSION['auth']) && $_SESSION['auth']==$_GET['auth']){
                if(isset($_GET['pass']) && isset($_GET['repass']) && (isset($_SESSION['grnum'])||isset($_SESSION['eid']))){
                    if($_GET['pass']==$_GET['repass']){
                        $con = mysqli_connect("localhost","root","","viitbe");
                        if(isset($_SESSION['grnum'])){
                            $chkuser = $con->prepare("UPDATE studentlist SET Password=? WHERE GRNumber=?");
                            $hashed = md5($_GET['pass']."gaswashere");
                            $chkuser->bind_param("ss",$hashed,$_SESSION['grnum']);
                            $chkuser->execute();  
                            unset($_SESSION['grnum']);
                            echo "1";
                        }
                        else if(isset($_SESSION['eid'])){
                            $chkuser = $con->prepare("UPDATE stafflist SET Password=? WHERE eid=?");
                            $hashed = md5($_GET['pass']."gaswashere");
                            $eid = htmlspecialchars($_SESSION['eid']);
                            $chkuser->bind_param("ss",$hashed,$eid);
                            $chkuser->execute();  
                            unset($_SESSION['eid']);
                            echo "1";
                        }
                        $con->close();
                    }
                    else{
                        echo "2";
                    }
                }            
            }
            else{
                echo "3";
            }
        }
    }

    elseif($_GET['type']=="ASSIGN"){
        if(isset($_GET['gid']) && isset($_GET['sid']) && ($_SESSION['suflag'])){
            $con = mysqli_connect("localhost","root","","viitbe");
            $chkuser = $con->prepare("SELECT id FROM superuser");
            $chkuser->execute(); 
            $verify = $chkuser->get_result()->fetch_assoc();
            if($verify['id']==$_SESSION['staff']){ 
                $chkuser = $con->prepare("UPDATE groups SET staffmember=? WHERE id=?");
                $sid = htmlspecialchars($_GET['sid']);
                $gid = htmlspecialchars($_GET['gid']);
                $chkuser->bind_param("si",$sid,$gid);
                $chkuser->execute();  
                echo "1";
            }
            $con->close();
        }
    }

    elseif($_GET['type']=="NAMERETURN"){
        if($_SESSION['gleader']){
            if(isset($_GET['gr1']) && isset($_GET['gr2']) && isset($_GET['gr3'])){
                if($_GET['gr1']==$_SESSION['active'] || $_GET['gr2']==$_SESSION['active'] || $_GET['gr3']==$_SESSION['active']){
                    echo "3";                    
                }
                else{
                    if(($_GET['gr1']=="X" && $_GET['gr2']!="X" && $_GET['gr3']!="X")||($_GET['gr1']!="X" && $_GET['gr2']=="X" && $_GET['gr3']!="X")||($_GET['gr1']!="X" && $_GET['gr2']!="X" && $_GET['gr3']=="X")||($_GET['gr1']!="X" && $_GET['gr2']!="X" && $_GET['gr3']!="X")){
                        if(($_GET['gr1']==$_GET['gr2'] || $_GET['gr3']==$_GET['gr2'] || $_GET['gr1']==$_GET['gr3'])){
                            echo "4";                            
                        }
                        else{
                            $con = mysqli_connect("localhost","root","","viitbe");
                            $c=0;
                            $ret = "";
                            if($_GET['gr1']!="X"){
                                $chkuser = $con->prepare("SELECT Name,mygroup,leader FROM studentlist WHERE GRNumber=?");
                                $chkuser->bind_param("s",$_GET['gr1']);
                                $chkuser->execute();
                                $gr = $chkuser->get_result()->fetch_assoc();
                                if($gr!=NULL){
                                    if($gr['leader']=='1'){
                                        echo "8";
                                        $con->close();
                                        exit;
                                    }
                                    if($gr['mygroup']==NULL){
                                        $ret .= "|".$gr['Name'];
                                        $_SESSION['grs'][$c] = $_GET['gr1'];
                                        $c++;
                                    }
                                    else{
                                        echo "7";
                                        $con->close();  
                                        exit;
                                    }
                                }
                                else{
                                    echo "6";
                                    $con->close();  
                                    exit;
                                }
                            }
                            if($_GET['gr2']!="X"){
                                $chkuser = $con->prepare("SELECT Name FROM studentlist WHERE GRNumber=?");
                                $chkuser->bind_param("s",$_GET['gr2']);
                                $chkuser->execute();
                                $gr = $chkuser->get_result()->fetch_assoc();                                
                                if($gr!=NULL){
                                    $ret .= "|".$gr['Name'];
                                    $_SESSION['grs'][$c] = $_GET['gr2'];
                                    $c++;
                                }
                                else{
                                    echo "6";
                                    $con->close();  
                                    exit;
                                }
                            }
                            if($_GET['gr3']!="X"){
                                $chkuser = $con->prepare("SELECT Name FROM studentlist WHERE GRNumber=?");
                                $chkuser->bind_param("s",$_GET['gr3']);
                                $chkuser->execute();
                                $gr = $chkuser->get_result()->fetch_assoc();
                                if($gr!=NULL){
                                    $ret .= "|".$gr['Name'];
                                    $_SESSION['grs'][$c] = $_GET['gr3'];
                                    $c++;
                                }
                                else{
                                    echo "6";
                                    $con->close();  
                                    exit;
                                }
                            }
                            echo $ret;
                            $con->close();  
                        }   
                    }
                    else{ 
                        echo "5";                                             
                    }                 
                }
            }
            else{
                echo "2";
            }
        }
    }

    elseif($_GET['type']=="STONE"){
        if(isset($_GET['grp']) && isset($_GET['sno']) && (isset($_SESSION['staff'])||isset($_SESSION['gleader']))){
            $con = mysqli_connect("localhost","root","","viitbe");
            $chkuser = $con->prepare("SELECT `".$_GET['sno']."` FROM checkposts WHERE gid=?");
            $chkuser->bind_param("i",$_GET['grp']);
            $chkuser->execute();
            $gr = $chkuser->get_result()->fetch_assoc();
            if(isset($_SESSION['staff'])){
                if($gr[$_GET['sno']]==2){
                    $chkuser = $con->prepare("UPDATE checkposts SET `".htmlspecialchars($_GET['sno'])."`=3 WHERE gid=?");
                    $grp = htmlspecialchars($_GET['grp']);
                    $chkuser->bind_param("i",$grp);
                    $chkuser->execute();
                    echo "1";
                }
                elseif($gr[$_GET['sno']]==3){
                    $chkuser = $con->prepare("UPDATE checkposts SET `".htmlspecialchars($_GET['sno'])."`=2 WHERE gid=?");
                    $grp = htmlspecialchars($_GET['grp']);
                    $chkuser->bind_param("i",$grp);
                    $chkuser->execute();
                    echo "1";
                }
            }
            else{
                if($gr[$_GET["sno"]]==1){
                    $chkuser = $con->prepare("UPDATE checkposts SET `".htmlspecialchars($_GET['sno'])."`=2 WHERE gid=?");
                    $grp = htmlspecialchars($_GET['grp']);
                    $chkuser->bind_param("i",$grp);
                    $chkuser->execute();
                    echo "1";
                }
                elseif($gr[$_GET['sno']]==2){
                    $chkuser = $con->prepare("UPDATE checkposts SET `".htmlspecialchars($_GET['sno'])."`=1 WHERE gid=?");
                    $grp = htmlspecialchars($_GET['grp']);
                    $chkuser->bind_param("i",$grp);
                    $chkuser->execute();
                    echo "1";
                }
            }
            $con->close();
        }
    }

    elseif($_GET['type']=="LOGIN"){
        if(isset($_POST['passl']) && isset($_POST['grnl'])){
            $con = mysqli_connect("localhost","root","","viitbe");
            $hashed = md5($_POST['passl']."gaswashere");
            $chkuser = $con->prepare("SELECT leader FROM studentlist WHERE GRNumber=? AND Password=?");
            $chkuser->bind_param("ss",$_POST['grnl'],$hashed);
            $chkuser->execute();
            $gr = $chkuser->get_result()->fetch_assoc();
            if($gr==NULL){
                echo "2";
            }
            else{
                $_SESSION['password'] = $hashed;
                $_SESSION['active'] = $_POST['grnl'];
                unset($_SESSION['gleader']);
                if($gr['leader']==1){                
                    $_SESSION['gleader'] = TRUE;
                }
                if(isset($_SESSION['staff'])){
                    unset($_SESSION['staff']);
                }
                if(isset($_SESSION['suflag'])){
                    unset($_SESSION['suflag']);
                }
                echo "1";
            }
            $con->close();
        }
    }

    elseif($_GET['type']=="AUTHORIZE"){
        if(isset($_POST['eidl']) && isset($_POST['tpassl'])){
            $con = mysqli_connect("localhost","root","","viitbe");
            $hashed = md5($_POST['tpassl']."gaswashere");
            $chkuser = $con->prepare("SELECT Id FROM stafflist WHERE eid=? AND Password=?");
            $chkuser->bind_param("ss",$_POST['eidl'],$hashed);
            $chkuser->execute();
            $gr = $chkuser->get_result()->fetch_assoc();
            if($gr==NULL){
                echo "2";
            }
            else{
                $_SESSION['password'] = $hashed;
                $_SESSION['staff'] = $gr['Id'];
                $chkuser = $con->prepare("SELECT id FROM superuser LIMIT 1");
                $chkuser->execute();
                $sufl = $chkuser->get_result()->fetch_assoc();
                if(isset($_SESSION['suflag'])){
                    unset($_SESSION['suflag']);
                }
                if($sufl['id']==$gr['Id']){                
                    $_SESSION['suflag'] = TRUE;
                }
                if(isset($_SESSION['active'])){
                    unset($_SESSION['active']);
                }
                echo "1";
            }
            $con->close();
        }
    }

    elseif($_GET['type']=="LOGOUT"){
        if(isset($_SESSION['staff'])){
            unset($_SESSION['staff']);
            if(isset($_SESSION['suflag'])){
                unset($_SESSION['suflag']);
            }
            echo "1";
        }
        else if(isset($_SESSION['active'])){
            unset($_SESSION['active']);
            if(isset($_SESSION['gleader'])){
                unset($_SESSION['gleader']);
            }
            echo "1";
        }
        unset($_SESSION['password']);
    }

    elseif($_GET['type']=="FORUMPOST"){ 
        if(isset($_POST['content'])){
            if($_POST['content']!=NULL){
                $con = mysqli_connect("localhost","root","","viitbe");
                $t = 0;
                if(isset($_POST['sflag']) && $_POST['sflag']==1){
                    $t = 1;
                }       
                $chkuser = $con->prepare("SELECT id FROM forum WHERE gid=? ORDER BY id DESC LIMIT 1");
                $chkuser->bind_param("i",$_GET['gid']);
                $chkuser->execute(); 
                $newid = $chkuser->get_result()->fetch_assoc();  
                if(isset($_SESSION['active'])){
                    $chkuser = $con->prepare("SELECT id FROM studentlist WHERE GRNumber=?");
                    $chkuser->bind_param("s",$_SESSION['active']);
                    $chkuser->execute();
                    $uid = $chkuser->get_result()->fetch_assoc();
                    $uid = $uid['id'];
                }
                else{
                    $uid = (-1)*$_SESSION['staff'];                  
                }
                $ni = 1; 
                if($newid!=NULL){
                    $ni = intval($newid['id'])+1;    
                }        
                $chkuser = $con->prepare("INSERT INTO forum (gid,id,sflag,content,uid,dandt) VALUES (?,?,?,?,?,'".date('d M Y H:i:s')."')");
                $gid = htmlspecialchars($_GET['gid']);
                $content = htmlspecialchars($_POST['content']);
                $chkuser->bind_param("iiisi",$gid,$ni,$t,$content,$uid);
                $chkuser->execute(); 
                echo "1";
                $con->close();
            }
        }
    }

    elseif($_GET['type']=="LIVEFORUM"){ 
        $con = mysqli_connect("localhost","root","","viitbe");
        if($_GET['live']==1){
            if(isset($_SESSION['active'])){
                $chkuser = $con->prepare("UPDATE studentlist SET liveforum=1 WHERE GRNumber=?");
                $chkuser->bind_param("s",$_SESSION['active']);
                $chkuser->execute();   
            }
            else{
                $chkuser = $con->prepare("UPDATE stafflist SET liveforum=1 WHERE id=?");
                $chkuser->bind_param("i",$_SESSION['staff']);
                $chkuser->execute();                  
            }                 
        }   
        elseif($_GET['live']==0){
            if(isset($_SESSION['active'])){
                $chkuser = $con->prepare("UPDATE studentlist SET liveforum=0 WHERE GRNumber=?");
                $chkuser->bind_param("s",$_SESSION['active']);
                $chkuser->execute();   
            }
            else{
                $chkuser = $con->prepare("UPDATE stafflist SET liveforum=0 WHERE id=?");
                $chkuser->bind_param("i",$_SESSION['staff']);
                $chkuser->execute();                  
            }           
        }
        $con->close();
    }

    elseif($_GET['type']=="CHATS"){ 
        $con = mysqli_connect("localhost","root","","viitbe"); 
        if($_GET['page']!=0){
            if(isset($_SESSION['mincounter'])){
                $skips = $_SESSION['mincounter'];
                if(isset($_SESSION['staff'])){
                    $chkuser = $con->prepare("SELECT content,uid,dandt,sflag,id FROM forum WHERE gid=? AND sflag=1 AND id<? ORDER BY id DESC LIMIT 10");
                    $chkuser->bind_param("ii",$_SESSION['gid'],$skips);
                }
                else{
                    $chkuser = $con->prepare("SELECT content,uid,dandt,sflag,id FROM forum WHERE gid=? AND id<? ORDER BY id DESC LIMIT 10");
                    $chkuser->bind_param("ii",$_SESSION['gid'],$skips); 
                }
            }
            else{
                $skips = ($_GET['page']-1)*10;
                if(isset($_SESSION['staff'])){
                    $chkuser = $con->prepare("SELECT content,uid,dandt,sflag,id FROM forum WHERE gid=? AND sflag=1 ORDER BY id DESC LIMIT ?,10");
                    $chkuser->bind_param("ii",$_SESSION['gid'],$skips);
                }
                else{
                    $chkuser = $con->prepare("SELECT content,uid,dandt,sflag,id FROM forum WHERE gid=? ORDER BY id DESC LIMIT ?,10");
                    $chkuser->bind_param("ii",$_SESSION['gid'],$skips); 
                }
            }  
            $chkuser->execute(); 
            $chats = $chkuser->get_result()->fetch_all();
            for($i=0;$i<count($chats);$i++){
                $stw = 0;
                if($chats[$i]['1']<0){
                    $stw = 1;
                    $chats[$i]['1'] = $chats[$i]['1']*(-1);
                    $chkuser = $con->prepare("SELECT Name,alias FROM stafflist WHERE id=?");
                    $chkuser->bind_param("i",$chats[$i]['1']);             
                }
                else{
                    $chkuser = $con->prepare("SELECT Name,alias FROM studentlist WHERE Id=?");
                    $chkuser->bind_param("i",$chats[$i]['1']);                                                        
                }
                $chkuser->execute(); 
                $alias = $chkuser->get_result()->fetch_assoc(); 
                if($alias['alias']==NULL || $alias['alias']==''){
                    $alias = $alias['Name'];
                }   
                else{                    
                    $alias = $alias['alias'];
                } 
                if(isset($_SESSION['staff'])){
                    echo $chats[$i]['2'].$stw.str_pad(strval(strlen($alias)),2,"0",STR_PAD_LEFT).htmlspecialchars($alias).htmlspecialchars($chats[$i]['0'])."|<!@phenomenalchars#$>|"; 
                }
                else{
                    echo $chats[$i]['2'].$stw.str_pad(strval(strlen($alias)),2,"0",STR_PAD_LEFT).htmlspecialchars($alias).htmlspecialchars($chats[$i]['0'])."|<!@phenomenalchars#$>|"; 
                }
                if(!isset($_SESSION['maxcounter'])){
                    $_SESSION['maxcounter']=0;  
                }
                if($_SESSION['maxcounter']<$chats[$i]['4']){
                    $_SESSION['maxcounter'] = $chats[$i]['4'];
                }
                if(!isset($_SESSION['mincounter'])){
                    $_SESSION['mincounter']=$chats[$i]['4'];  
                }
                if($_SESSION['mincounter']>$chats[$i]['4']){
                    $_SESSION['mincounter'] = $chats[$i]['4'];
                }
            }
        }
        else{
            if(isset($_SESSION['staff'])){
                $chkuser = $con->prepare("SELECT content,uid,dandt,id FROM forum WHERE gid=? AND sflag=1 AND id>?");
                $chkuser->bind_param("ii",$_SESSION['gid'],$_SESSION['maxcounter']);
            }
            else{
                $chkuser = $con->prepare("SELECT content,uid,dandt,sflag,id FROM forum WHERE gid=? AND id>?");
                $chkuser->bind_param("ii",$_SESSION['gid'],$_SESSION['maxcounter']); 
            }  
            $chkuser->execute(); 
            $chats = $chkuser->get_result()->fetch_assoc();
            if($chats!=NULL){
                $stw = 0;
                if($chats['uid']<0){
                    $stw = 1;
                    $chats['uid'] = $chats['uid']*(-1);
                    $chkuser = $con->prepare("SELECT Name,alias FROM stafflist WHERE Id=?");
                    $chkuser->bind_param("i",$chats['uid']);             
                }
                else{
                    $chkuser = $con->prepare("SELECT Name,alias FROM studentlist WHERE id=?");
                    $chkuser->bind_param("i",$chats['uid']);                                                        
                }
                $chkuser->execute(); 
                $alias = $chkuser->get_result()->fetch_assoc(); 
                if($alias['alias']==NULL || $alias['alias']==''){
                    $alias = $alias['Name'];
                }   
                else{                    
                    $alias = $alias['alias'];
                } 
                if(isset($_SESSION['staff'])){
                    echo $chats['dandt'].$stw.str_pad(strval(strlen($alias)),2,"0",STR_PAD_LEFT).htmlspecialchars($alias).htmlspecialchars($chats['content']); 
                }
                else{
                    echo $chats['dandt'].$stw.str_pad(strval(strlen($alias)),2,"0",STR_PAD_LEFT).htmlspecialchars($alias).htmlspecialchars($chats['content']); 
                }
                if($_SESSION['maxcounter']<$chats['id']){
                    $_SESSION['maxcounter'] = $chats['id'];
                }
            }
        }           
        $con->close();
    }
    
    elseif($_GET['type']=="GETFILE"){
        $con = mysqli_connect("localhost","root","","viitbe"); 
        if(isset($_SESSION['active'])){            
            $chkuser = $con->prepare("SELECT a.id FROM uploads a,studentlist b WHERE b.GRNumber=? AND a.id=? AND a.uid=b.id");
            $chkuser->bind_param("si",$_SESSION['active'],$_GET['fileid']); 
            $chkuser->execute(); 
            $isme = $chkuser->get_result()->fetch_assoc(); 

            $chkuser = $con->prepare("SELECT a.name,a.dandt,a.uid FROM uploads a,studentlist b WHERE b.GRNumber=? AND a.gid=b.mygroup AND a.id=?");
            $chkuser->bind_param("si",$_SESSION['active'],$_GET['fileid']); 
        }
        elseif(isset($_SESSION['staff'])){            
            $chkuser = $con->prepare("SELECT id FROM uploads WHERE uid=? AND id=?");
            $isitme = $_SESSION['staff']*(-1);
            $chkuser->bind_param("ii",$isitme,$_GET['fileid']); 
            $chkuser->execute(); 
            $isme = $chkuser->get_result()->fetch_assoc(); 

            $chkuser = $con->prepare("SELECT a.name,a.dandt,a.uid FROM uploads a,groups b WHERE b.staffmember=? AND a.gid=b.id AND a.id=?");
            $chkuser->bind_param("ii",$_SESSION['staff'],$_GET['fileid']);              
        } 
        $chkuser->execute(); 
        $thefile = $chkuser->get_result()->fetch_assoc(); 
        if($thefile==NULL){
            echo "1";
        }  
        else{
            if($isme!=NULL){
                echo "2";
            }
            else{
                echo "3";
            }
            if($thefile['uid']>=0){
                $chkuser = $con->prepare("SELECT Name,alias FROM studentlist WHERE id=?");
                $chkuser->bind_param("i",$thefile['uid']); 
            }
            else{
                $thefile['uid'] *= -1;
                $chkuser = $con->prepare("SELECT Name,alias FROM stafflist WHERE Id=?");
                $chkuser->bind_param("i",$thefile['uid']);                
            }            
            $chkuser->execute(); 
            $user = $chkuser->get_result()->fetch_assoc(); 
            if($user['alias']==NULL){
                echo str_pad(strval(strlen($user['Name'])),2,"0",STR_PAD_LEFT).htmlspecialchars($user['Name']);
            }
            else{
                echo str_pad(strval(strlen($user['alias'])),2,"0",STR_PAD_LEFT).htmlspecialchars($user['alias']);                    
            } 
            echo $thefile['dandt'].str_pad(strval(strlen($thefile['name'])),2,"0",STR_PAD_LEFT).htmlspecialchars($thefile['name']).strval(filesize("Uploads/".md5($thefile['name'].$thefile['dandt'])));
        }
        $con->close();
    }
    
    elseif($_GET['type']=="DOWNLOADFILE"){
        $con = mysqli_connect("localhost","root","","viitbe");
        if(isset($_SESSION['active'])){ 
            $chkuser = $con->prepare("SELECT a.id FROM uploads a,studentlist b WHERE b.GRNumber=? AND a.id=? AND a.gid=b.mygroup");
            $chkuser->bind_param("si",$_SESSION['active'],$_GET['fileid']); 
        }
        elseif($_SESSION['staff']){
            $chkuser = $con->prepare("SELECT a.id FROM uploads a,groups b WHERE b.staffmember=? AND a.id=? AND a.gid=b.id");
            $chkuser->bind_param("ii",$_SESSION['staff'],$_GET['fileid']);            
        }
        $chkuser->execute(); 
        $isme = $chkuser->get_result()->fetch_assoc(); 
        if($isme!=NULL){    
            $chkuser = $con->prepare("SELECT name,dandt FROM uploads WHERE id=?");
            $chkuser->bind_param("i",$_GET['fileid']); 
            $chkuser->execute(); 
            $link = $chkuser->get_result()->fetch_assoc();            
            header("Content-Description: File Transfer");
            header("Content-Disposition: attachment;filename=".htmlspecialchars($link['name']));
            header("Content-Transfer-Encoding: binary");    
            readfile("Uploads/".md5($link['name'].$link['dandt']));
        }
        $con->close();
    }

    elseif($_GET['type']=="REMOVEFILE"){
        $con = mysqli_connect("localhost","root","","viitbe");
        if(isset($_SESSION['active'])){            
            $chkuser = $con->prepare("SELECT a.id FROM uploads a,studentlist b WHERE b.GRNumber=? AND a.id=? AND a.uid=b.id");
            $chkuser->bind_param("si",$_SESSION['active'],$_GET['fileid']); 
        }
        elseif(isset($_SESSION['staff'])){            
            $chkuser = $con->prepare("SELECT id FROM uploads WHERE uid=? AND id=?");
            $isitme = $_SESSION['staff']*(-1);
            $chkuser->bind_param("ii",$isitme,$_GET['fileid']);         
        } 
        $chkuser->execute(); 
        $isme = $chkuser->get_result()->fetch_assoc(); 
        if($isme!=NULL){
            $chkuser = $con->prepare("SELECT name,dandt FROM uploads WHERE id=?"); 
            $chkuser->bind_param("i",$_GET['fileid']);
            $chkuser->execute(); 
            $file = $chkuser->get_result()->fetch_assoc();
            if($file!=NULL){
                if(file_exists("Uploads/".md5($file['name'].$file['dandt']))){
                    if(unlink("Uploads/".md5($file['name'].$file['dandt']))){
                        $chkuser = $con->prepare("DELETE FROM uploads WHERE id=?"); 
                        $chkuser->bind_param("i",$_GET['fileid']);
                        $chkuser->execute();    
                        echo "1";                     
                    }
                }
            }                        
        }
        $con->close();
    }
    
    elseif($_GET['type']=="UPLOADFILE"){
        if(isset($_FILES['upld']) && (isset($_SESSION['active']) || isset($_SESSION['staff'])) && isset($_POST['gid'])){                   
            $con = mysqli_connect("localhost","root","","viitbe");
            if(isset($_SESSION['active'])){
                $chkuser = $con->prepare("SELECT id,mygroup FROM studentlist WHERE GRNumber=?");
                $chkuser->bind_param("s",$_SESSION['active']);
                $chkuser->execute();  
                $uid = $chkuser->get_result()->fetch_assoc();
                if($uid['mygroup']==$_POST['gid']){
                    $uid = $uid['id'];
                }   
                else{
                    unset($uid);
                }                        
            }
            elseif(isset($_SESSION['staff'])){
                $chkuser = $con->prepare("SELECT staffmember FROM groups WHERE id=?");
                $chkuser->bind_param("i",$_POST['gid']);
                $chkuser->execute();  
                $uid = $chkuser->get_result()->fetch_assoc();
                if($uid['staffmember']==$_SESSION['staff']){
                    $uid = (-1)*$_SESSION['staff'];             
                }   
                else{
                    unset($uid);
                }
            }
            if(isset($uid)){
                $time = date('d M Y H:i:s');
                move_uploaded_file($_FILES['upld']['tmp_name'],"Uploads/".md5($_FILES['upld']['name'].$time));
                $chkuser = $con->prepare("INSERT INTO uploads (name,gid,uid,dandt) VALUES (?,?,?,?)");
                $chkuser->bind_param("siis",$_FILES['upld']['name'],$_POST['gid'],$uid,$time);
                $chkuser->execute();
                echo "1";
            }
            $con->close();
        }
    }
    
    elseif($_GET['type']=="LOADFILELIST"){
        $con = mysqli_connect("localhost","root","","viitbe");
        if(isset($_SESSION['active'])){
            $chkuser = $con->prepare("SELECT id FROM studentlist WHERE GRNumber=? AND mygroup=?");
            $chkuser->bind_param("si",$_SESSION['active'],$_GET['gid']);
            $chkuser->execute(); 
            $itsrealme = $chkuser->get_result()->fetch_assoc();             
        }
        elseif(isset($_SESSION['staff'])){
            $chkuser = $con->prepare("SELECT id FROM groups WHERE staffmember=? AND id=?");
            $chkuser->bind_param("ii",$_SESSION['staff'],$_GET['gid']);
            $chkuser->execute(); 
            $itsrealme = $chkuser->get_result()->fetch_assoc();            
        }
        if($itsrealme!=NULL){
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
        }
    }

    elseif($_GET['type']=="UPLOADIMAGE"){
        if(isset($_FILES['upld'])){
            if(getimagesize($_FILES['upld']['tmp_name'])!=NULL){
                $con = mysqli_connect("localhost","root","","viitbe");
                if(isset($_SESSION['active'])){
                    $chkuser = $con->prepare("SELECT pimage FROM studentlist WHERE GRNumber=? AND Password=?");
                    $chkuser->bind_param("ss",$_SESSION['active'],$_SESSION['password']);
                }
                elseif(isset($_SESSION['staff'])){
                    $chkuser = $con->prepare("SELECT pimage FROM stafflist WHERE Id=? AND Password=?");
                    $chkuser->bind_param("is",$_SESSION['staff'],$_SESSION['password']);                    
                }
                $chkuser->execute(); 
                $didiupload = $chkuser->get_result()->fetch_assoc(); 
                if($didiupload['pimage']!=NULL){
                    unlink("Uploads/".md5($didiupload['pimage']."ProfImg1"));   
                }
                if(isset($_SESSION['active'])){
                    $chkuser = $con->prepare("UPDATE studentlist SET pimage=? WHERE GRNumber=? AND Password=?");
                    $chkuser->bind_param("sss",$_FILES['upld']['name'],$_SESSION['active'],$_SESSION['password']);
                }
                elseif(isset($_SESSION['staff'])){
                    $chkuser = $con->prepare("UPDATE stafflist SET pimage=? WHERE Id=? AND Password=?");
                    $chkuser->bind_param("sis",$_FILES['upld']['name'],$_SESSION['staff'],$_SESSION['password']);                    
                }
                if($chkuser->execute()){
                    move_uploaded_file($_FILES['upld']['tmp_name'],"Uploads/".md5($_FILES['upld']['name']."ProfImg1"));
                    echo ('data:' . 'image/*' . ';base64,' . base64_encode(file_get_contents("Uploads/".md5($_FILES['upld']['name']."ProfImg1"))));
                }
                $con->close();
            }
            else{
                echo "1";
            }
        }
    }
    
    elseif($_GET['type']=="MAKELEADER"){
        if(isset($_GET['uid']) && isset($_GET['flag'])){ 
            $con = mysqli_connect("localhost","root","","viitbe");           
            $chkuser = $con->prepare("SELECT b.Id FROM superuser a,stafflist b WHERE b.Id=? AND b.Password=? AND a.id=b.Id");
            $chkuser->bind_param("is",$_SESSION['staff'],$_SESSION['password']);
            $chkuser->execute();
            $mesu = $chkuser->get_result()->fetch_assoc(); 
            if($mesu!=NULL){          
                $chkuser = $con->prepare("UPDATE studentlist SET leader=? WHERE id=?");
                $chkuser->bind_param("ii",$_GET['flag'],$_GET['uid']);
                $chkuser->execute();                
            }
            $con->close();
        }
    }

    elseif($_GET['type']=="CHANGEPASS" && isset($_GET['id'])){
        $con = mysqli_connect("mysql.hostinger.in","u288517326_nyx","a1b1c1d1","u288517326_viitb");
        if(isset($_GET['flag']) && $_GET['flag']==0){ 
            $_SESSION['chngp'] = $_GET['id'];
            $chkuser = $con->prepare("SELECT email FROM studentlist WHERE GRNumber=?");
            $chkuser->bind_param("s",$_GET['id']);
            $chkuser->execute(); 
            $groups = get_result_assoc($chkuser); 
            if($groups!=NULL){
                $_SESSION['auth'] = "0".md5(date('d M Y H:i:s'));
                $to      = $groups['email'];
                $subject = "Lucid - Password Reset Request" ;
                $message = "Password Reset Link : <br><form method='post' action='www.lucid.hol.es/BEProjects/AjaxRequest/ChangeMyPassword/' target='_blank'><input type='hidden' value='".$_SESSION['auth']."'><input type='text' name='password' placeholder='New Password'><br><input type='password' name='newrepass' placeholder='Password Again'><br><input type='submit'></form><br>Ignore if you never requested this mail.<br>NOTE : Useless when session expires" ;
                $header = "From: support@lucid.com\r\n"; 
                $header.= "MIME-Version: 1.0\r\n"; 
                $header.= "Content-Type: text/html; charset=utf-8\r\n"; 
                $header.= "X-Priority: 1\r\n"; 
                if(mail($to, $subject, $message, $header)){
                    echo "1";
                }  
            }        
        }
        elseif(isset($_GET['flag']) && $_GET['flag']==1){ 
            $_SESSION['chngp'] = $_GET['id'];
            $chkuser = $con->prepare("SELECT email FROM studentlist WHERE GRNumber=?");
            $chkuser->bind_param("s",$_GET['id']);
            $chkuser->execute(); 
            $groups = get_result_assoc($chkuser);     
            if($groups!=NULL){
                $_SESSION['auth'] = "1".md5(date('d M Y H:i:s'));
                $to      = $groups['email'];
                $subject = "Lucid - Password Reset Request" ;
                $message = "Password Reset Link : <br><form method='post' action='www.lucid.hol.es/BEProjects/AjaxRequest/ChangeMyPassword/' target='_blank'><input type='hidden' value='".md5($_GET['id']."GASISGOD")."'><input type='text' name='password' placeholder='New Password'><br><input type='password' name='newrepass' placeholder='Password Again'><br><input type='submit'></form><br>Ignore if you never requested this mail.<br>NOTE : Useless when session expires" ;
                $header = "From: support@lucid.com\r\n"; 
                $header.= "MIME-Version: 1.0\r\n"; 
                $header.= "Content-Type: text/html; charset=utf-8\r\n"; 
                $header.= "X-Priority: 1\r\n"; 
                if(mail($to, $subject, $message, $header)){
                    echo "1";
                }  
            } 
        }
        $con->close();
    }

    elseif($_GET['type']=="ChangeMyPassword" && isset($_POST['checksum']) && isset($_POST['password']) && isset($_POST['newrepass']) && $_POST['password']!='' && $_POST['newrepass']!=''){
        if($_POST['password']==$_POST['newrepass']){
            if(isset($_SESSION['auth']) && $_SESSION['auth']==$_POST['checksum']){
                $con = mysqli_connect("mysql.hostinger.in","u288517326_nyx","a1b1c1d1","u288517326_viitb");
                if($_SESSION['auth'][0]=='0'){
                    $chkuser = $con->prepare("UPDATE studentlist SET Password=? WHERE GRNumber=?");
                    $hashed = md5($_POST['password']."gaswashere");
                    $chkuser->bind_param("ss",$hashed,$_SESSION['chngp']);
                }
                else{
                    $chkuser = $con->prepare("UPDATE stafflist SET Password=? WHERE eid=?");
                    $hashed = md5($_POST['password']."gaswashere");
                    $chkuser->bind_param("ss",$hashed,$_SESSION['chngp']);                    
                }
                $chkuser->execute();
                $con->close();
                unset($_SESSION['auth']);
                unset($_SESSION['chngp']);
                header("location:/BEProjects/Home");
                exit;
            }
        }
        else{
            echo "<html><head><script>alert('Password Mismatch');window.close();</script></head></html>";
            exit;
        }
        echo "1";
    }
?>
