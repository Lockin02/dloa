<?php
@session_start( );
include( $Htmledit_Path."/../includes/db.inc.php" );
include( $Htmledit_Path."/../includes/config.php" );
if ( $Htmledit_Tag != "Htmledit" )
{
    exit( );
}
echo "<HTML><HEAD><TITLE>上传Flash</TITLE>\r\n<META http-equiv=Content-Type content=\"text/html; charset=gb2312\">\r\n<SCRIPT>\r\n function sSendURL(sURL){\r\n  returnValue = sURL;\r\n  self.close();\r\n }\r\n</SCRIPT>\r\n</HEAD>\r\n<BODY leftMargin=0 topMargin=0 marginwidth=\"0\" marginheight=\"0\"><IFRAME src=\"uploadflash.php?langtype=cn\" frameBorder=0 height=\"100%\" width=\"100%\"></IFRAME>\r\n<DIV id=divCover style=\"DISPLAY: none; FONT-SIZE: 11px; Z-INDEX: 100; LEFT: 0px; WIDTH: 100%; FONT-FAMILY: tahoma; POSITION: absolute; TOP: 0px; HEIGHT: 100%; BACKGROUND-COLOR: buttonface; TEXT-ALIGN: center\"><BR><BR><BR><BR><BR><BR><BR>Loading ...</DIV></BODY></HTML>";
?>
