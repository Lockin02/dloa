<?php
@session_start( );
include( $Htmledit_Path."/../includes/db.inc.php" );
include( $Htmledit_Path."/../includes/config.php" );
if ( $Htmledit_Tag != "Htmledit" )
{
    exit( );
}
echo "<HTML><HEAD><TITLE>����Flash</TITLE>\r\n<META content=\"text/html; charset=gb2312\" http-equiv=Content-Type>\r\n<Link rel=\"stylesheet\" type=\"text/css\" href=\"../css/pop.css\">\r\n<SCRIPT language=JavaScript>\r\nfunction returnvar() {\r\nwindow.returnValue = width.value+\"*\"+height.value+\"*\"+src.value;\r\nwindow.close();\r\n}\r\nfunction setvalue(fi,value) {\r\n\tfi.value = value\r\n}\r\nfunction flashlib(){\r\n\tres = showModalDialog('lib.php?langtype=cn&libtype=flash', null, 'dialogWidth: 770px; dialogHeight: 450px; center: yes; resizable: no; scroll: no; status: no;');\r\n\tif(res) setvalue(src,res)\r\n}\r\nfunction uploadflash(){\r\n\tres = showModalDialog('upflash.php?langtype=cn', null, 'dialogWidth: 400px; dialogHeight: 230px; center: yes; resizable: no; scroll: no; status: no;');\r\n\tif(res) setvalue(src,res)\r\n}\r\n</SCRIPT>\r\n\r\n<META content=\"MSHTML 5.00.2614.3500\" name=GENERATOR></HEAD>\r\n<BODY bgcolor=\"menu\">\r\n<TABLE border=0 cellPadding=0 cellSpacing=5 align=center style=\"padding-left:5px;width=98%\">\r\n  <TBODY>\r\n    <TR>\r\n      <TD colSpan=2><font class=title>����Flash</font>\r\n          <hr width=100% >\r\n          �ļ���׺ : swf </TD>\r\n    </TR>\r\n    <TR>\r\n      <TD colSpan=2>��ַ :\r\n          <INPUT name=src id=\"src\" onfocus=this.select() value=http:// size=40/></TD>\r\n    </TR>\r\n\t<TR>\r\n\t  <TD><BUTTON onclick=flashlib(); style=\"width:15em\">��Flash����ѡ��һ��Flash</BUTTON></TD>\r\n    </TR>\r\n\t<TR>\r\n\t  <TD><BUTTON onclick=uploadflash(); style=\"width:15em\">�ϴ�Flash</BUTTON></TD>\r\n    </TR>\r\n    <TR>\r\n      <TD>���� :\r\n      <INPUT name=width id=\"width\" onfocus=this.select() value=100 size=7></TD>\r\n    </TR>\r\n    <TR>\r\n      <TD>�߶� :\r\n        <input name=height id=\"height\" onfocus=this.select() value=100 size=7></TD>\r\n    </TR>\r\n    <tr>\r\n      <TD align=center colSpan=2>\r\n        <input type=\"button\" name=\"button1\" onClick=returnvar(); value=\"ȷ��\">&nbsp;&nbsp;\r\n        <input type=\"button\" name=\"button\" value=\"�ر�\" onclick=window.close();></TD>\r\n    </TR>\r\n  </TBODY>\r\n</TABLE>\r\n</BODY>\r\n</HTML>\r\n";
?>