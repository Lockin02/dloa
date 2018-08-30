<?php
@session_start( );
include( $Htmledit_Path."/../includes/db.inc.php" );
include( $Htmledit_Path."/../includes/config.php" );
if ( $Htmledit_Tag != "Htmledit" )
{
    exit( );
}
echo "<HTML><HEAD><TITLE>选择插入的图片</TITLE>\r\n<META http-equiv=Content-Type content=\"text/html; charset=gb2312\">\r\n<Link rel=\"stylesheet\" type=\"text/css\" href=\"../css/pop.css\">\r\n<SCRIPT>\r\n function sCallLib(){\r\n  returnValue = 'lib';\r\n  self.close();\r\n }\r\n function sSendURL(sURL){\r\n  returnValue = sURL;\r\n  self.close();\r\n }\r\n function sCover(sParam){\r\n  divCover.style.display = sParam ? 'none' : 'inline';\r\n\r\n }\r\n</SCRIPT>\r\n</HEAD>\r\n<BODY leftMargin=0 topMargin=0 marginwidth=\"0\" marginheight=\"0\"><IFRAME src=\"sNewLogo.php?langtype=cn\" frameBorder=0 align=\"middle\"></IFRAME>\r\n<DIV id=divCover style=\"DISPLAY: none; FONT-SIZE: 11px; Z-INDEX: 100; LEFT: 0px; WIDTH: 100%; FONT-FAMILY: tahoma; POSITION: absolute; TOP: 0px; HEIGHT: 100%; BACKGROUND-COLOR: buttonface; TEXT-ALIGN: center\"><BR><BR><BR><BR><BR><BR><BR>Loading ...</DIV></BODY></HTML>\r\n";
?>
