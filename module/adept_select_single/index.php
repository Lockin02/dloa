<?php
echo "<html>\r\n<head>\r\n<title>选择部门</title>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=gb2312\">\r\n<link rel=\"stylesheet\" type=\"text/css\" href=\"../../inc/style.css\">\r\n\r\n</head>\r\n";
    if (!isset( $toid ))
        $toid = "TO_ID_DEPT_S";
    if (!isset( $toname ))
        $toname = "TO_NAME_DEPT_S";
    if (!isset( $formname ))
        $formname = "form1";
        
 echo "<frameset cols=\"*\"  rows=\"300,*\" frameborder=\"YES\" border=\"1\" framespacing=\"0\" id=\"bottom\">\r\n  <frameset cols=\"200,*\"  rows=\"*\" frameborder=\"YES\" border=\"1\" framespacing=\"0\" id=\"bottom\">\r\n     <frame name=\"dept\" src=\"jstree.php?toid=";
echo $toid;
echo "&toname=";
echo $toname;
echo "&formname=";
echo $formname;
echo "\">\r\n     <frame name=\"user\" src=\"blank.php\">\r\n  </frameset>\r\n  <frame name=\"bottom\" src=\"bottom.php\" scrolling=\"NO\" frameborder=\"NO\">\r\n</frameset>\r\n</html>\r\n";
?>
