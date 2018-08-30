<html>
<head>
<title>选择流程模块</title>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<link rel="stylesheet" type="text/css" href="../../inc/style.css">
</head>
<?php
if ( isset( $toid ) && isset( $toname ) )
   break;
$toid = "TO_ID";
$toname = "TO_NAME";
?>
<frameset cols="*"  rows="200,*" frameborder="YES" border="1" framespacing="0" id="bottom">  
    <frameset cols="150,*"  rows="*" frameborder="YES" border="1" framespacing="0" id="bottom">     
        <frame name="dept" src="dept.php?toid=<?php 
        echo $toid; 
        echo "&toname=";
        echo $toname;
        echo "&SID=";
        echo $ID;
        ?>"/>
        <frame name="user" src="blank.php" />  
    </frameset>  
    <frame name="bottom" src="bottom.html" scrolling="NO" frameborder="0">
</frameset>
</html>

