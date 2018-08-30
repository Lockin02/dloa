<?php
@session_start( );
include( $Htmledit_Path."/../includes/db.inc.php" );
include( $Htmledit_Path."/../includes/config.php" );
if ( $Htmledit_Tag != "Htmledit" )
{
    exit( );
}
echo "<HTML><HEAD><TITLE>插入图片</TITLE>\r\n<META http-equiv=Content-Type content=\"text/html; charset=gb2312\">\r\n<Link rel=\"stylesheet\" type=\"text/css\" href=\"../css/pop.css\">\r\n<script>\r\nfunction check()\r\n{\r\n\tif(document.forms[0].webimg.value.length == 0)\r\n\t\talert('请填写图片地址！');\r\n\telse\r\n\t\tparent.parent.sSendURL(document.forms[0].webimg.value);\r\n}\r\n</script>\r\n</HEAD>\r\n<BODY bgcolor=\"menu\">\r\n<TABLE cellSpacing=0 cellPadding=8 width=\"100%\">\r\n  <FORM>\r\n  <TBODY>\r\n    <TR align=\"center\"> \r\n      <TD><FIELDSET>\r\n      <LEGEND>网上图片</LEGEND>\r\n      <br>\r\n          地址: \r\n          <input name=\"webimg\" type=\"text\" id=\"webimg\" value=\"http://\" size=\"40\">\r\n          <BR>\r\n          <BR>\r\n\t\t  </FIELDSET></TD>\r\n    </TR>\r\n  <TR>\r\n    <TD align=right><INPUT type=submit value=确定 name=sAction onClick=\"check()\"> \r\n<INPUT onclick=\"javascript: window.close();\" type=button value=取消></TD></TR></FORM></TABLE>\r\n</BODY></HTML>\r\n";
?>
