<?php
@session_start( );
include( $Htmledit_Path."/../includes/db.inc.php" );
include( $Htmledit_Path."/../includes/config.php" );
if ( $Htmledit_Tag != "Htmledit" )
{
    exit( );
}
echo "<html>\r\n<head><TITLE>插入图片</TITLE>\r\n<META http-equiv=Content-Type content=\"text/html; charset=gb2312\">\r\n<Link rel=\"stylesheet\" type=\"text/css\" href=\"../css/pop.css\">\r\n\r\n<script Language=\"JavaScript\">\r\nfunction check()\r\n{\r\nif (document.form1.ATTACHMENT.value==\"\")\t\r\n{\r\n\talert('请选择一张图片再上传');\r\n\treturn false;\r\n\t\r\n\t}\t\r\n\t\r\n if (document.form1.ATTACHMENT.value!=\"\")\r\n   {\r\n     var file_temp=document.form1.ATTACHMENT.value,file_name;\r\n     var Pos;\r\n     Pos=file_temp.lastIndexOf(\"\\\\\");\r\n     file_name=file_temp.substring(Pos+1,file_temp.length);\r\n     document.form1.ATTACHMENT_NAME.value=file_name;\r\n     document.form1.filename.value=file_name;\r\n   \r\n}\r\n\r\nform1.submit ();\r\n\r\n\t\t\r\n}\r\n</script>\r\n</HEAD>\r\n<BODY bgcolor=\"menu\">\r\n<form enctype=\"multipart/form-data\" action=\"upload.php\" method=\"post\" name=\"form1\">\r\n<table width=\"100%\"  border=\"0\" cellspacing=\"5\">\r\n  <tr>\r\n    <td><span class=\"title\">上传图片</span><hr width=100%>\r\n    文件后缀 : gif, png, jpg<br>\r\n    您目前能够上传单个文件的大小为";
echo $Upload_max_size;
echo " </td>\r\n  </tr>\r\n  <tr>\r\n    <td><INPUT  type=\"file\" name=\"ATTACHMENT\" size=32 title=\"选择图片文件\" value=\"\">\r\n      </td>\r\n  </tr>\r\n  <tr>\r\n    <td align=\"right\"><INPUT type=button value=上传 name=sAction  onclick=\"check();\">\r\n                      <input type=\"hidden\" name=\"ATTACHMENT_NAME\" value=\"\"> \r\n                      <input type=\"hidden\" name=\"filename\"> \r\n                      <input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"";
echo $Upload_max_size;
echo "\">\r\n      <INPUT name=\"button\" type=button onclick=\"javascript: window.close();\" value=取消></td>\r\n  </tr>\r\n</table>\r\n</form>\r\n</BODY></HTML>";
?>
