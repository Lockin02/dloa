<?php
@session_start( );
include( $Htmledit_Path."/../includes/db.inc.php" );
include( $Htmledit_Path."/../includes/config.php" );
if ( $Htmledit_Tag != "Htmledit" )
{
    exit( );
}
echo "<HTML><HEAD>\r\n<TITLE>���� Real Media ������</TITLE>\r\n<META content=\"text/html; charset=gb2312\" http-equiv=Content-Type>\r\n<Link rel=\"stylesheet\" type=\"text/css\" href=\"../css/pop.css\">\r\n<SCRIPT event=onclick for=Ok language=JavaScript>\r\n\tvar s=path.value;\r\n\tif (s.length<10)\r\n\t{\r\n\t\talert(\"��������ȷ���ļ����ӵ�ַ��\");\r\n\t}else{\r\n\t\tvar autostart\r\n\t\tautostart=document.getElementById(\"autostart\").checked\r\n\t\twindow.returnValue = path.value+\"*\"+selrow.value+\"*\"+selcol.value+\"*\"+ autostart\r\n\t\twindow.close();\r\n\t}\r\n</SCRIPT>\r\n<script>\r\nfunction IsDigit()\r\n{\r\n  return ((event.keyCode >= 48) && (event.keyCode <= 57));\r\n}\r\n</script>\r\n<BODY bgcolor=\"menu\">\r\n<TABLE border=0 cellPadding=0 cellSpacing=5 align=center style=\"padding-left:10px;width=98%\">\r\n  <TBODY>\r\n  <TR>\r\n    <TD colSpan=2>\r\n    <font class=title>���� Real Media ������</font>\r\n    <hr width=100% >�ļ���׺ : rm, ra, ram</TD>\r\n    </TR>\r\n<TR>\r\n    <TD colSpan=2>��ַ : <INPUT id=path size=40 value=\"http://\"></TD></TR>\r\n<TR>\r\n    <TD>��� : <INPUT id=selrow size=7 value=480 ONKEYPRESS=\"event.returnValue=IsDigit();\"></TD></TR>\r\n<TR>\r\n    <TD>�߶� : <INPUT id=selcol size=7 value=360 ONKEYPRESS=\"event.returnValue=IsDigit();\"></TD></TR>\r\n<TR>\r\n    <TD>����ģʽ : <INPUT TYPE=\"radio\" name=\"autostart\" value=1 checked id=\"auto\"><label for=\"auto\">�Զ�</label> <INPUT TYPE=\"radio\" name=\"autostart\" value=0 id=\"man\"><label for=\"man\">�ֶ�</label></TD></TR>\r\n<TR>\r\n    <TD align=center colSpan=2>\r\n    <input type=\"SUBMIT\" name=\"SUBMIT\" ID=\"Ok\" value=\"ȷ��\">\r\n    &nbsp; &nbsp;\r\n    <input type=\"button\" name=\"button\" value=\"�ر�\" onclick=window.close();></TD>\r\n</TR></TBODY></TABLE>\r\n</BODY></HTML>\r\n";
?>
