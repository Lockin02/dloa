<?php
@session_start( );
include( $Htmledit_Path."/../includes/db.inc.php" );
include( $Htmledit_Path."/../includes/config.php" );
if ( $Htmledit_Tag != "Htmledit" )
{
    exit( );
}
echo "<HTML><HEAD><TITLE>插入Flash</TITLE>\r\n<META http-equiv=Content-Type content=\"text/html; charset=gb2312\">\r\n<Link rel=\"stylesheet\" type=\"text/css\" href=\"../css/pop.css\">\r\n<script>\r\nfunction check()\r\n{\r\nif (document.forms[0].ATTACHMENT.value==\"\")\t\r\n{\r\n\talert('请选择一个Flash再上传');\r\n\treturn false;\r\n\t\r\n\t}\t\r\n\t\r\n if (document.forms[0].ATTACHMENT.value!=\"\")\r\n   {\r\n     var file_temp=document.forms[0].ATTACHMENT.value,file_name;\r\n     var Pos;\r\n     Pos=file_temp.lastIndexOf(\"\\\\\");\r\n     file_name=file_temp.substring(Pos+1,file_temp.length);\r\n     document.forms[0].ATTACHMENT_NAME.value=file_name;\r\n     document.forms[0].filename.value=file_name;\r\n   \r\n     \r\n}\r\n\r\ndocument.forms[0].submit ();\r\n\r\n\t\t\r\n}\r\n</script>\r\n</HEAD>\r\n<BODY bgcolor=\"menu\">\r\n<FORM  action=\"uploadf.php?langtype=cn&a=up\" method=\"post\" encType=\"multipart/form-data\">\r\n<TABLE cellSpacing=5 width=\"100%\">\r\n<TR> \r\n<TD><span class=\"title\">上传Flash</span>\r\n<hr width=100%>\r\n文件后缀 : swf <br>\r\n您目前能够上传单个文件的大小为";
echo $Upload_max_size;
echo "</TD>\r\n</TR>\r\n<TR> \r\n<TD><INPUT name=ATTACHMENT type=file size=34></TD>\r\n</TR>\r\n<TR>\r\n<TD align=right><INPUT type=button value=上传 name=sAction onclick=\"check()\">\r\n <input type=\"hidden\" name=\"ATTACHMENT_NAME\" value=\"\"> \r\n                      <input type=\"hidden\" name=\"filename\"> \r\n                      <input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"";
echo $Upload_max_size;
echo "\">\r\n<INPUT onclick=\"javascript: window.close();\" type=button value=取消></TD></TR></TABLE>\r\n</FORM>\r\n</BODY></HTML>";
?>
