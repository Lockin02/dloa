<?php
echo "<html>\r\n<head>\r\n<title>选择人员</title>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=gb2312\">\r\n<link rel=\"stylesheet\" type=\"text/css\" href=\"../../inc/style.css\">\r\n</head>\r\n";
if ( isset( $toid ) )
    $toid = $toid;
else
    $toid = "TO_ID";
if (isset( $toname ))
    $toname = $toname;
else
    $toname = "TO_NAME";
if (isset( $formname ))
    $formname = $formname;
else
    $formname = "form1";
if (isset( $todept ))
    $todept = $todept;
else
    $todept = "";
echo "<frameset rows=\"*,30\"  rows=\"*\" frameborder=\"NO\" border=\"1\" framespacing=\"0\" id=\"bottom\">\r\n   <frameset cols=\"200,*\"  rows=\"*\" frameborder=\"YES\" border=\"1\" framespacing=\"0\" id=\"bottom\">\r\n
		      <frame name=\"dept\" id=\"dept\" src=\"dept.php?toid=";
echo $toid;
echo "&toname=";
echo $toname;
echo "&formname=";
echo $formname;
echo "&todept=";
echo $todept;
echo "\">\r\n      <frame name=\"user\" id=\"user\"  src=\"query.php?toid=";
echo $toid;
echo "&toname=";
echo $toname;
echo "&formname=";
echo $formname;
echo "&todept=";
echo $todept;
echo "\">\r\n   </frameset>\r\n  </frameset>";
?>
