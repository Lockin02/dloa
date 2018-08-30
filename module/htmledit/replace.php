<?php
@session_start( );
include( $Htmledit_Path."/../includes/db.inc.php" );
include( $Htmledit_Path."/../includes/config.php" );
if ( $Htmledit_Tag != "Htmledit" )
{
    exit( );
}
echo "<HTML>\r\n<HEAD>\r\n<TITLE>查找替换</TITLE>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=gb2312\">\r\n<Link rel=\"stylesheet\" type=\"text/css\" href=\"../css/pop.css\">\r\n<SCRIPT LANGUAGE=JavaScript FOR=Ok EVENT=onclick>\r\n  window.returnValue = a.value+\"*\"+b.value+\"*\"+(c.checked?\"1\":\"0\")+\"*\";\r\n  window.close();\r\n</SCRIPT>\r\n<script>\r\nfunction IsDigit()\r\n{\r\n  return ((event.keyCode >= 48) && (event.keyCode <= 57));\r\n}\r\n</script>\r\n<style type=\"text/css\">\r\n<!--\r\n.style1 {color: #FF0000}\r\n-->\r\n</style>\r\n</HEAD>\r\n<BODY bgcolor=\"menu\">\r\n<table border=\"0\" cellspacing=\"10\" cellpadding=\"0\" align=center style=\"padding-left:10px\">\r\n<tr>\r\n\t<td colspan=2 align=center>\r\n\t<font class=title>查找替换</font>\r\n\t<hr width=100% >\r\n\t</td>\r\n</tr>\r\n<tr>\r\n\t<td>\r\n\t查找: <INPUT TYPE=TEXT SIZE=15 ID=a>\r\n\t</td>\r\n</tr>\r\n<tr>\r\n\t<td>\r\n\t替换: <INPUT TYPE=TEXT SIZE=15 ID=b>\r\n\t</td>\r\n</tr>\r\n<tr>\r\n\t<td colspan=2>\r\n\t<input type=checkbox id=c>&nbsp;&nbsp;<label for=\"c\">区分大小写</label></td>\r\n</tr>\r\n<tr>\r\n\t<td colspan=2 align=center><input type=\"SUBMIT\" name=\"SUBMIT\" ID=\"Ok\" value=\"开始\">&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"button\" name=\"button\" value=\"关闭\" onclick=window.close();>\r\n\t</td>\r\n</tr>\r\n<tr>\r\n  <td colspan=2 align=center><span class=\"style1\">注：替换会将HTML标记一并替换</span></td>\r\n</tr>\r\n</table>\r\n</BODY>   \r\n</HTML>\r\n";
?>
