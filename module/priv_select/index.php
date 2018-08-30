<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Frameset//EN">
<!-- saved from url=(0039)http://127.0.0.1/OA/module/priv_select/ -->
<HTML><HEAD><TITLE>添加角色</TITLE>
<META http-equiv=Content-Type content="text/html; charset=gb2312">
<LINK href="../../inc/style.css" type=text/css rel=stylesheet>
<META content="MSHTML 6.00.2900.5726" name=GENERATOR>
</HEAD>
<?php 
//验证赋值
if(!isset($toid)){
    $toid="PRIV_ID";
}
if(!isset($toname)){
    $toname="PRIV_NAME";
}
?>
<FRAMESET id=bottom 
border=1 frameSpacing=0 rows=330,* frameBorder=YES cols=*>
<FRAME name=dept 
src="dept.php?toid=<?php echo $toid;?>&toname=<?php echo $toname; ?>">
<FRAME name=bottom src="bottom.php" 
frameBorder=NO scrolling=no>
</FRAMESET>
</HTML>