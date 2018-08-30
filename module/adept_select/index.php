<HTML><HEAD><TITLE>添加部门</TITLE>
 <meta http-equiv="Content-Type" content="text/html; charset=gb2312"> 
 <link rel="stylesheet" type="text/css" href="../../inc/style.css"> 
<META content="MSHTML 6.00.2900.5726" name=GENERATOR>
</HEAD>
<?php 
if(!isset($toid)){ 
    $toid="COPY_TO_ID";
}
if(!isset($toname)){ 
    
    $toname="COPY_TO_NAME";
}
?>
<FRAMESET id=bottom 
border=1 frameSpacing=0 rows=300,* frameBorder=YES cols=*>
<FRAMESET id=bottom border=1 frameSpacing=0 rows=* frameBorder=YES cols=200,*>
<FRAME name=dept src="jstree.php?toid=<?php echo $toid; ?> &toname=<?php echo $toname;?>">
<FRAME name=user src="blank.php">
</FRAMESET>
<FRAME name=bottom src="bottom.php" frameBorder=NO scrolling=no>
</FRAMESET>
</HTML>


