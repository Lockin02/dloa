<?php
echo "<html>\r\n<head>\r\n<title>选择人员</title>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=gb2312\">\r\n<link rel=\"stylesheet\" type=\"text/css\" href=\"../../inc/style.css\">\r\n</head>\r\n";
do
{
    if ( isset( $toid ) && isset( $toname ) )
        break;
    $toid = "CS_ID";
    $toname = "CS_NAME";
} while( 0 );
echo "<frameset rows=\"*,30\"  rows=\"*\" frameborder=\"NO\" border=\"1\" framespacing=\"0\" id=\"bottom\">\r\n   <frameset cols=\"200,*\"  rows=\"*\" frameborder=\"YES\" border=\"1\" framespacing=\"0\" id=\"bottom\">\r\n      <frame name=\"dept\" src=\"dept.php?toid=";
echo $toid;
echo "&toname=";
echo $toname;
echo "\">\r\n      <frame name=\"user\" src=\"user.php?toid=";
echo $toid;
echo "&toname=";
echo $toname;
echo "\">\r\n   </frameset>\r\n   <frame name=\"control\" scrolling=\"no\" src=\"control.php\">\r\n</frameset>";
?>
