<?php
@session_start( );
include( $Htmledit_Path."/../includes/db.inc.php" );
include( $Htmledit_Path."/../includes/config.php" );
if ( $Htmledit_Tag != "Htmledit" )
{
    exit( );
}
echo "<HTML><HEAD><TITLE>����ͼƬ</TITLE>\r\n<META http-equiv=Content-Type content=\"text/html; charset=gb2312\">\r\n<Link rel=\"stylesheet\" type=\"text/css\" href=\"../css/pop.css\">\r\n<script>\r\nfunction check()\r\n{\r\n\tif(document.forms[0].webimg.value.length == 0)\r\n\t\talert('����дͼƬ��ַ��');\r\n\telse\r\n\t\tparent.parent.sSendURL(document.forms[0].webimg.value);\r\n}\r\n</script>\r\n</HEAD>\r\n<BODY bgcolor=\"menu\">\r\n<TABLE cellSpacing=0 cellPadding=8 width=\"100%\">\r\n  <FORM>\r\n  <TBODY>\r\n    <TR align=\"center\"> \r\n      <TD><FIELDSET>\r\n      <LEGEND>����ͼƬ</LEGEND>\r\n      <br>\r\n          ��ַ: \r\n          <input name=\"webimg\" type=\"text\" id=\"webimg\" value=\"http://\" size=\"40\">\r\n          <BR>\r\n          <BR>\r\n\t\t  </FIELDSET></TD>\r\n    </TR>\r\n  <TR>\r\n    <TD align=right><INPUT type=submit value=ȷ�� name=sAction onClick=\"check()\"> \r\n<INPUT onclick=\"javascript: window.close();\" type=button value=ȡ��></TD></TR></FORM></TABLE>\r\n</BODY></HTML>\r\n";
?>
