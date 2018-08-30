<?php
@session_start( );
include( $Htmledit_Path."/../includes/db.inc.php" );
include( $Htmledit_Path."/../includes/config.php" );
if ( $Htmledit_Tag == "Htmledit" )
{
    $ImgPath = "http://".$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
    $ImgPath = str_replace( "sliblook_feast.php", "images/", $ImgPath );
}
else
{
    exit( );
}
echo "<HTML><HEAD><TITLE>选择Flash</TITLE>\r\n<META http-equiv=Content-Type content=\"text/html; charset=gb2312\">\r\n</HEAD>\r\n<BODY leftMargin=0 topMargin=0 marginheight=\"0\" marginwidth=\"0\" bgcolor=\"#666666\">\r\n<table width=\"545\" border=\"0\" cellspacing=\"1\" cellpadding=\"0\" align=\"center\">\r\n<tr bgcolor=\"#FFFFFF\" align=\"center\">\r\n<td height=136 width=108>\r\n<table width=100% height=128 border=0 cellspacing=0 cellpadding=0 align=center>\r\n<tr><td align=center height=108>\r\n<object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0\" width=\"100\" height=\"100\">\r\n  <param name=\"movie\" value=\"";
echo $ImgPath;
echo "Iloveyou.swf\">\r\n  <param name=\"quality\" value=\"high\">\r\n  <embed src=\"";
echo $ImgPath;
echo "Iloveyou.swf\" quality=\"high\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" type=\"application/x-shockwave-flash\" width=\"100\" height=\"100\"></embed>\r\n</object>\r\n</td></tr><tr><td height=28 bgcolor=#d4d0c8 align=center>\r\n<INPUT onClick=\"window.location='sliblookf.php?step=ok&nowfile=";
echo $ImgPath;
echo "Iloveyou.swf'\" type=button value=选中>\r\n</td></tr></table>\r\n</td>\r\n<td height=136 width=108>\r\n</td>\r\n<td height=136 width=108>\r\n</td>\r\n<td height=136 width=108>\r\n</td>\r\n<td height=136 width=108>\r\n</td>\r\n</tr>\r\n</table>\r\n</BODY></HTML>\r\n";
?>
