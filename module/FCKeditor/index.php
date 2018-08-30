<?php
include( "fckeditor.php" );
echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\">\r\n<html>\r\n\t<head>\r\n\t\t<title>在线编辑器</title>\r\n\t\t<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\r\n\t\t<meta name=\"robots\" content=\"noindex, nofollow\">\r\n\t\t<link href=\"sample.css\" rel=\"stylesheet\" type=\"text/css\" />\r\n\t\t</head>\r\n\t<body>\r\n\t\t<form action=\"sampleposteddata.php\" method=\"post\" target=\"_blank\">\r\n\t\t";
$sBasePath = $_SERVER['PHP_SELF'];
$sBasePath = substr( $sBasePath, 0, strpos( $sBasePath, "index.php" ) );
echo $sBasePath;
FCKeditor( "FCKeditor1" );
$oFCKeditor = new FCKeditor;
$oFCKeditor->BasePath = $sBasePath;
$oFCKeditor->Config['SkinPath'] = $sBasePath."editor/skins/".htmlspecialchars( "office2003" )."/";
$oFCKeditor->Value = "欢迎使用鼎利OA系统.";
$oFCKeditor->Create( );
echo "\t\t\t<br>\r\n\t\t\t<input type=\"submit\" value=\"保存\">\r\n\t\t</form>\r\n\t</body>\r\n</html>";
?>
