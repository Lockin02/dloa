<?php
do
{
    if ( isset( $toid ) && isset( $toname )&& isset( $formname ) )
        break;
    $formname="form2";
    $toid = "DEPT_ID";
    $toname = "DEPT_NAME";
} while( 0 );
?>
<html>
 <head>
 <title>添加部门</title>
 <meta http-equiv="Content-Type" content="text/html; charset=gb2312">
 <link rel="stylesheet" type="text/css" href="../../inc/style.css">
 </head>
 <frameset cols="*"  rows="350,*" frameborder="YES" border="1" framespacing="0" id="bottom">
   <frame name="dept" src="dept.php?formname=<?php echo $formname;?>&toid=<?php echo $toid;?>&toname=<?php echo $toname;?>">
   <frame name="bottom" src="bottom.php" scrolling="NO" frameborder="NO">
 </frameset>
 </html>

