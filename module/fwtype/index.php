<?php
do
{
    if ( isset( $toid ) && isset( $toname ) )
        break;
    $toid = "TO_ID";
    $toname = "TO_NAME";
} while( 0 );
echo "<html>\r\n<head>\r\n<title>选择</title>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=gb2312\">\r\n<link rel=\"stylesheet\" type=\"text/css\" href=\"../../inc/style.css\">\r\n</head>\r\n\r\n<frameset cols=\"*\"  rows=\"350,*\" frameborder=\"YES\" border=\"1\" framespacing=\"0\" id=\"bottom\">\r\n  <frame name=\"dept\" src=\"dept.php?id=";
echo $id;
echo "&toid=";
echo $toid;
echo "&toname=";
echo $toname;
echo "\">\r\n  <frame name=\"bottom\" src=\"bottom.php\" scrolling=\"NO\" frameborder=\"NO\">\r\n</frameset>\r\n</html>\r\n";
?>
