<?php
echo "<html>\r\n<head>\r\n<title>上传文件</title>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=gb2312\">\r\n<link rel=\"stylesheet\" type=\"text/css\" href=\"../../inc/style.css\">\r\n\r\n</head>\r\n\r\n<frameset cols=\"*\"  rows=\"200,*\" frameborder=\"YES\" border=\"1\" framespacing=\"0\" id=\"bottom\">\r\n  <frameset cols=\"150,*\"  rows=\"*\" frameborder=\"YES\" border=\"1\" framespacing=\"0\" id=\"bottom\">\r\n     <frame name=\"dept\" src=\"dept.php?id=";
echo $id;
echo "&FLOW_ID=";
echo $FLOW_ID;
echo "\">\r\n     <frame name=\"user\" src=\"user.php?mod=";
echo $id;
echo "&FLOW_ID=";
echo $FLOW_ID;
echo "\">\r\n  </frameset>\r\n  <frame name=\"bottom\" src=\"bottom.php\" scrolling=\"NO\" frameborder=\"NO\">\r\n</frameset>\r\n</html>\r\n";
?>
