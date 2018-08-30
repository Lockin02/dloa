<?php
@session_start( );
include( $Htmledit_Path."/../includes/db.inc.php" );
include( $Htmledit_Path."/../includes/config.php" );
if ( $Htmledit_Tag != "Htmledit" )
{
    exit( );
}
$Urlstr = "slib.php";
if ( $libtype == "flash" )
{
    $Urlstr = "slib_flash.php";
}
echo "<HTML><HEAD><TITLE>媒体库</TITLE>\r\n<META http-equiv=Content-Type content=\"text/html; charset=gb2312\">\r\n<SCRIPT>\r\n function sSendURL(sURL){\r\n  returnValue = sURL;\r\n  self.close();\r\n }\r\n</SCRIPT>\r\n</HEAD>\r\n<BODY leftMargin=0 topMargin=0 marginwidth=\"0\" marginheight=\"0\">\r\n<IFRAME width=\"100%\" height=\"100%\" src=\"";
echo $Urlstr;
echo "\" frameBorder=\"0\"></IFRAME>\r\n</BODY></HTML>\r\n";
?>
