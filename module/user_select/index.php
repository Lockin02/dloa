<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Frameset//EN">
<!-- saved from url=(0039)http://127.0.0.1/OA/module/user_select/ -->
<html><head><title>选择人员</title>
<meta http-equiv=content-type content="text/html; charset=gb2312">
<link href="../../inc/style.css" type="text/css" rel="stylesheet">
<meta content="mshtml 6.00.2900.5726" name="generator">
<?php 
//验证赋值
if(!isset($toid)){
    $toid="TO_ID";
}
if(!isset($toname)){
    $toname="TO_NAME";
}
if(!isset($formname)){
    $formname="form1";
}
if(!isset($todept)){
    $todept='';
}
?>
</head>
<frameset id="bottom" border="1" framespacing="0" rows="*,30" frameborder="no">
    <frameset id="bottom" border="1" framespacing="0" rows="*" frameborder="1" cols="200,*">
        <frame name="dept" marginheight="0" marginwidth="0" src="dept.php?toid=<?php echo $toid;?>&toname=<?php echo $toname; ?>&formname=<?php echo $formname; ?>&todept=<?php echo $todept; ?>">
        <frame name="user" marginheight="0" marginwidth="0" src="user.php?toid=<?php echo $toid;?>&toname=<?php echo $toname; ?>&formname=<?php echo $formname; ?>&todept=<?php echo $todept; ?>">
    </frameset>
    <frame name="control" src="control.php" scrolling="no">
</frameset>
</html>