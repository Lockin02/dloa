<?php
include( "../../includes/db.inc.php" );
include( "../../includes/config.php" );
$uploadfile = "../../attachment/".$ATTACHMENT_ID."/$ATTACHMENT_NAME";
$DOCType = "�༭";
$DocType = isset($DocType)?$DocType:1;
if ( $DocType == 1 )
{
    $DOCType = "�༭ģ��";
    if ( file_exists( $uploadfile ) == NULL )
    {
        copy( "../../attachment/mod/newdoc.doc", $uploadfile );
    }
}
echo "<HTML>\r\n<HEAD>\r\n<TITLE>�����Ķ�</TITLE>\r\n<meta http-equiv=\"content-type\" content=\"text/html;charset=gb2312\">\r\n<link rel=\"stylesheet\" type=\"text/css\" href=\"../../inc/style.css\">\r\n<SCRIPT LANGUAGE=\"JavaScript\" src=\"tangerocx.js\"></SCRIPT>\r\n<script>\r\nfunction myload()\r\n{\r\n\r\n  TANGER_OCX_SetInfo();\r\n\r\n}\r\n\r\nfunction MY_SetMarkModify(flag)\r\n{\r\n  if(flag)\r\n  {\r\n     mflag1.className=\"TableHeader2\";\r\n     mflag2.className=\"TableHeader1\";\r\n  }\r\n  else\r\n  {\r\n     mflag1.className=\"TableHeader1\";\r\n     mflag2.className=\"TableHeader2\";\r\n  }\r\n  TANGER_OCX_SetMarkModify(flag);\r\n}\r\n\r\nfunction MY_ShowRevisions(flag)\r\n{\r\n  if(flag)\r\n  {\r\n     sflag1.className=\"TableHeader2\";\r\n     sflag2.className=\"TableHeader1\";\r\n  }\r\n  else\r\n  {\r\n     sflag1.className=\"TableHeader1\";\r\n     sflag2.className=\"TableHeader2\";\r\n  }\r\n  TANGER_OCX_ShowRevisions(flag);\r\n}\r\n</script>\r\n</HEAD>\r\n\r\n<BODY class=\"bodycolor\" leftmargin=\"0\" topmargin=\"0\" onLoad=\"javascript:myload()\" onunload=\"javascript:close_doc()\" ";
//echo base64_decode( $SHOWCODE );
echo ">\r\n\r\n<form name=\"form1\" id=\"form1\" method=\"post\" action=\"\" enctype=\"multipart/form-data\">\r\n\r\n<table width=100% height=100% class=\"small\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">\r\n<tr width=100%>\r\n<td valign=top width=80>\r\n  <table border=\"0\" cellspacing=\"1\" width=\"100%\" class=\"small\" bgcolor=\"#000000\" cellpadding=\"3\" align=\"center\">\r\n     <tr class=\"";
echo $TableHeader;
echo "\">\r\n       <td nowrap align=\"center\">�ļ�����</td>\r\n     </tr>\r\n     <tr class=\"TableHeader1\" onclick=\"TANGER_OCX_PrintDoc('0')\" style=\"cursor:hand\">\r\n       <td nowrap align=\"center\">��ӡ</td>\r\n     </tr>\r\n   <tr class=\"";
echo $TableHeader;
echo "\">\r\n     <td nowrap align=\"center\">������֤</td>\r\n   </tr>\r\n   \r\n     <tr class=\"TableHeader1\" onclick=\"DoCheckSign()\" style=\"cursor:hand\">\r\n       <td nowrap align=\"center\">��֤ǩ����ӡ��</td>\r\n     </tr>\r\n  </table>\r\n</td>\r\n<td width=100% valign=\"top\">\r\n<script src=\"/includes/ntko/ntkooffice.js\"></script>\r\n</td>\r\n</tr>\r\n</table>\r\n\r\n<script language=\"JScript\" for=TANGER_OCX event=\"OnDocumentClosed()\">\r\nTANGER_OCX_OnDocumentClosed()\r\n</script>\r\n\r\n<script language=\"JScript\">\r\nvar TANGER_OCX_str;\r\nvar TANGER_OCX_obj;\r\n\r\nfunction close_doc()\r\n{\r\n   document.all(\"TANGER_OCX\").setAttribute(\"IsNoCopy\",false);\r\n}\r\n</script>\r\n\r\n<script language=\"JScript\" for=TANGER_OCX event=\"OnDocumentOpened(TANGER_OCX_str,TANGER_OCX_obj)\">\r\nTANGER_OCX_OnDocumentOpened(TANGER_OCX_str,TANGER_OCX_obj);\r\n</script>\r\n<span id=\"TANGER_OCX_op\" style=\"display:none\">";
echo $OP;
echo "</span>\r\n<span id=\"TANGER_OCX_filename\" style=\"display:none\">";
echo $ATTACHMENT_NAME;
echo "</span>\r\n<span id=\"TANGER_OCX_attachName\" style=\"display:none\">";
echo $ATTACHMENT_NAME;
echo "</span>\r\n<span id=\"TANGER_OCX_attachURL\" style=\"display:none\">read_doc.php?filename=";
echo $ATTACHMENT_NAME;
echo "&uploaddir=";
echo $uploadfile;
echo "</span>\r\n<span id=\"TANGER_OCX_user\" style=\"display:none\">";
echo $USERNAME;
echo "</span>\r\n\r\n<input style=\"display:none\" type=\"file\" name=\"ATTACHMENT\">\r\n<input type=\"hidden\" name=\"ATTACHMENT_ID\" value=\"";
echo $ATTACHMENT_ID;
echo "\">\r\n<input type=\"hidden\" name=\"ATTACHMENT_NAME\" value=\"";
echo $ATTACHMENT_NAME;
echo "\">\r\n</form>";
?>
<!--<object id="TANGER_OCX" classid="clsid:C9BC4DFF-4248-4a3c-8A49-63A7D317F404" codebase="../../general/esystem/OfficeControl.cab#version=4,0,1,8" width="100%" height="100%"></object>-->
</body>
</html>