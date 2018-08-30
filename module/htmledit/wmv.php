<?php
@session_start( );
include( $Htmledit_Path."/../includes/db.inc.php" );
include( $Htmledit_Path."/../includes/config.php" );
if ( $Htmledit_Tag != "Htmledit" )
{
    exit( );
}
echo "<HTML><HEAD><TITLE>插入 MediaPlayer 播放器</TITLE>\r\n<META content=\"text/html; charset=gb2312\" http-equiv=Content-Type>\r\n<Link rel=\"stylesheet\" type=\"text/css\" href=\"../css/pop.css\">\r\n</HEAD>\r\n<script>\r\nfunction returnvar()\r\n{\r\n\tvar s=path.value\r\n\tif (s.length<10 )\r\n\t{\r\n\t\talert(\"请输入正确的文件链接地址！\");\r\n\t}else{\r\n\tvar autostart\r\n\t\tautostart=document.getElementById(\"autostart\").checked\r\n\t\twindow.returnValue = path.value+\"*\"+ autostart +\"*\"+width.value+\"*\"+height.value\r\n\t\twindow.close();\r\n\t}\r\n}\r\n</SCRIPT>\r\n<script>\r\nfunction IsDigit()\r\n{\r\n  return ((event.keyCode >= 48) && (event.keyCode <= 57));\r\n}\r\n</script>\r\n\r\n<BODY bgcolor=\"menu\">\r\n<TABLE border=0 cellPadding=0 cellSpacing=5 align=center style=\"padding-left:5px;width=98%\">\r\n<TBODY>\r\n<TR>\r\n    <TD colSpan=2><font class=title>插入 MediaPlayer 播放器</font>\r\n    <hr width=100% >文件后缀 : avi, wmv, mov</TD></TR>\r\n<TR>\r\n    <TD colSpan=2>地址 : <INPUT id=path size=40 value=\"http://\"></TD></TR>\r\n<TR>\r\n    <TD>宽度 : <INPUT id=width size=7 value=480 ONKEYPRESS=\"event.returnValue=IsDigit();\"></TD></TR>\r\n<TR>\r\n    <TD>高度 : <INPUT id=height size=7 value=360 ONKEYPRESS=\"event.returnValue=IsDigit();\"></TD></TR>\r\n<TR>\r\n    <TD>播放模式 : <INPUT TYPE=\"radio\" name=\"autostart\" value=1 checked id=\"auto\"><label for=\"auto\">自动</label> <INPUT TYPE=\"radio\" name=\"autostart\" value=0 id=\"man\"><label for=\"man\">手动</label></TD></TR>\r\n<tr>\r\n    <TD align=center colSpan=2>\r\n\t<input type=\"button\" name=\"SUBMIT\" ID=\"Ok\" value=\"确定\" onClick=\"returnvar()\">\r\n    &nbsp; &nbsp;\r\n    <input type=\"button\" name=\"button\" value=\"关闭\" onclick=window.close();></TD></TR>\r\n</TBODY></TABLE>\r\n</BODY></HTML>\r\n";
?>
