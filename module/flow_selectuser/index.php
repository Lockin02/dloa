<?php
do
{
    if ( isset( $toid ) && isset( $toname ) )
        break;
    $toid = "Delete_str";
    $toname = "Delete_str_name";
} while( 0 );
echo "<html>\r\n<head>\r\n<title>选择办理人员</title>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=gb2312\">\r\n<link rel=\"stylesheet\" type=\"text/css\" href=\"../../inc/style.css\">\r\n\r\n</head>\r\n\r\n<frameset cols=\"*\"  rows=\"*\" frameborder=\"YES\" border=\"1\" framespacing=\"0\" id=\"bottom\">\r\n  <frameset cols=\"*\"  rows=\"*\" frameborder=\"YES\" border=\"1\" framespacing=\"0\" id=\"bottom\">\r\n     <frame name=\"user\" src=\"user.php?suser=";
echo $suser;
echo "&toid=";
echo $toid;
echo "&toname=";
echo $toname;
echo "\">\r\n     \r\n  </frameset>\r\n  <frame name=\"bottom\" src=\"bottom.php\" scrolling=\"NO\" frameborder=\"NO\">\r\n</frameset>\r\n</html>\r\n";
?>
