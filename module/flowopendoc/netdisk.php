<?php
echo "<HTML>\r\n<HEAD>\r\n<TITLE>";
echo $ATTACHMENT_NAME;
echo "文档在线阅读</TITLE>\r\n<meta http-equiv=\"content-type\" content=\"text/html;charset=gb2312\">\r\n<SCRIPT LANGUAGE=\"JavaScript\" src=\"tangerocx.js\"></SCRIPT>\r\n\r\n<script>\r\nfunction myload()\r\n{\r\n\r\n  TANGER_OCX_SetInfo();\r\n  window.title=\" Office 文档在线阅读\";\r\n}\r\n\r\nfunction MY_SetMarkModify(flag)\r\n{\r\n  if(flag)\r\n  {\r\n     mflag1.className=\"TableHeader2\";\r\n     mflag2.className=\"TableHeader1\";\r\n  }\r\n  else\r\n  {\r\n     mflag1.className=\"TableHeader1\";\r\n     mflag2.className=\"TableHeader2\";\r\n  }\r\n  TANGER_OCX_SetMarkModify(flag);\r\n}\r\n\r\nfunction MY_ShowRevisions(flag)\r\n{\r\n  if(flag)\r\n  {\r\n     sflag1.className=\"TableHeader2\";\r\n     sflag2.className=\"TableHeader1\";\r\n  }\r\n  else\r\n  {\r\n     sflag1.className=\"TableHeader1\";\r\n     sflag2.className=\"TableHeader2\";\r\n  }\r\n  TANGER_OCX_ShowRevisions(flag);\r\n}\r\n\r\nfunction lock_ref()\r\n{\r\n  return;\r\n}\r\n</script>\r\n</HEAD>\r\n\r\n<BODY class=\"bodycolor\" leftmargin=\"0\" topmargin=\"5\" onLoad=\"javascript:myload()\" onunload=\"javascript:close_doc()\">\r\n\r\n<FORM NAME=\"form1\" METHOD=post ACTION=\"\" ENCTYPE=\"multipart/form-data\">\r\n\r\n<table width=100% height=100% class=\"small\" cellspacing=\"1\" cellpadding=\"3\" align=\"center\">\r\n<tr width=100%>\r\n<td valign=top width=80>\r\n  <table border=\"0\" cellspacing=\"1\" width=\"100%\" class=\"small\" bgcolor=\"#000000\" cellpadding=\"3\" align=\"center\">\r\n\r\n  </table>\r\n</td>\r\n<td width=100% valign=\"top\">\r\n<object id=\"TANGER_OCX\" classid=\"clsid:C9BC4DFF-4248-4a3c-8A49-63A7D317F404\" codebase=\"../../general/esystem/OfficeControl.cab#version=4,0,0,8\" width=\"100%\" height=\"100%\">              \r\n         <param name=\"ProductCaption\" value=\"宁夏红集团\">\r\n         <param name=\"ProductKey\" value=\"9B0A1BCF9E10FADEF87957A04EC56E44DCF4105C\">\r\n<param name=\"IsNoCopy\" value=\"1\">\r\n<param name=\"BorderStyle\" value=\"1\">\r\n<param name=\"BorderColor\" value=\"14402205\">\r\n<param name=\"TitlebarColor\" value=\"14402205\">\r\n<param name=\"TitlebarTextColor\" value=\"0\">\r\n<param name=\"Caption\" value=\"";
echo $ATTACHMENT_NAME;
echo "\">\r\n<param name=\"IsShowToolMenu\" value=\"-1\">\r\n<param name=\"IsHiddenOpenURL\" value=\"0\">\r\n\r\n\r\n<SPAN STYLE=\"color:red\"><br>不能装载文档控件。请在检查浏览器的选项中检查浏览器的安全设置。</SPAN>\r\n</object>\r\n</td>\r\n</tr>\r\n</table>\r\n\r\n<script language=\"JScript\" for=TANGER_OCX event=\"OnDocumentClosed()\">\r\nTANGER_OCX_OnDocumentClosed()\r\n</script>\r\n\r\n<script language=\"JScript\">\r\nvar TANGER_OCX_str;\r\nvar TANGER_OCX_obj;\r\n\r\nvar close_op_flag=1;\r\n\r\nfunction close_doc()\r\n{\r\n   document.all(\"TANGER_OCX\").setAttribute(\"IsNoCopy\",false);\r\n   if(close_op_flag!=1)\r\n   {\r\n     msg='是否保存对  \\'";
echo $ATTACHMENT_NAME;
echo "\\'  的修改？';\r\n     if(window.confirm(msg))\r\n        TANGER_OCX_SaveDoc(0);\r\n   }\r\n}\r\n</script>\r\n\r\n<script language=\"JScript\" for=TANGER_OCX event=\"OnDocumentOpened(TANGER_OCX_str,TANGER_OCX_obj)\">\r\nTANGER_OCX_OnDocumentOpened(TANGER_OCX_str,TANGER_OCX_obj)\r\n</script>\r\n\r\n<SPAN ID=\"TANGER_OCX_op\" style=\"display:none\">";
echo $OP;
echo "</SPAN>\r\n<SPAN ID=\"TANGER_OCX_filename\" style=\"display:none\">";
echo $ATTACHMENT_NAME;
echo "</SPAN>\r\n<SPAN ID=\"TANGER_OCX_attachName\" style=\"display:none\">";
echo $ATTACHMENT_NAME;
echo "</SPAN>\r\n<SPAN ID=\"TANGER_OCX_attachURL\" style=\"display:none\">read_doc.php?filename=";
echo $ATTACHMENT_NAME;
echo "&uploaddir=";
echo $ATTACHMENT_ID;
echo "</SPAN>\r\n<SPAN ID=\"TANGER_OCX_user\" style=\"display:none\">";
echo $USERNAME;
echo "</SPAN>\r\n\r\n\r\n</FORM>\r\n\r\n\r\n</BODY>\r\n</HTML>\r\n";
?>
