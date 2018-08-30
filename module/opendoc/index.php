<?php
include( "../../includes/db.inc.php" );
include( "../../includes/config.php" );
include( "../../includes/msql.php" );
$filename_info = explode( ".", $ATTACHMENT_NAME );
$File_Type = strtolower( $filename_info[count( $filename_info ) - 1] );
$query = "SELECT * FROM unit ";
$rid = $msql->GetRow( $query );
$uploadfile = "../../attachment/".$ATTACHMENT_ID."/$ATTACHMENT_NAME";
$DOCType = "编辑";
if ( $DocType == 1 )
{
    $DOCType = "编辑模板";
    if ( file_exists( $uploadfile ) == NULL )
    {
        copy( "../../attachment/mod/newdoc.doc", $uploadfile );
    }
}
if ( $DocType == 2 )
{
    $DOCType = "红头模板";
    if ( file_exists( $uploadfile ) == NULL )
    {
        copy( "../../attachment/mod/newdoc.doc", $uploadfile );
    }
}
if ( $DocType == 3 )
{
    $DOCType = "编辑红头";
    $query = "SELECT * FROM flow_step WHERE Pid='".$pid."'";
    $DOC_ID = $msql->GetRow( $query );
    $DOCFILE = "../../attachment/mod/H".$DOC_ID['formid'].".doc";
    $NDOCFILE = "../../attachment/file_cab/".$pid."/$pid.doc";
    $hDOCFILE = "../../attachment/file_cab/".$pid."/h$pid.doc";
    if ( file_exists( $hDOCFILE ) == NULL )
    {
        copy( $DOCFILE, $hDOCFILE );
    }
    $query = "SELECT * FROM flow_item WHERE formID='".$DOC_ID['formid']."'";
    $Item_ID = $msql->GetRow( $query );
    $Flow_item = explode( ",", $Item_ID[title] );
    $Hc_text = "setBookMark();";
}
echo "<HTML>\r\n<HEAD>\r\n<TITLE>在线编辑</TITLE>\r\n<meta http-equiv=\"content-type\" content=\"text/html;charset=gb2312\">\r\n<link rel=\"stylesheet\" type=\"text/css\" href=\"../../inc/style.css\">\r\n<SCRIPT LANGUAGE=\"JavaScript\" src=\"tangerocx.js\"></SCRIPT>\r\n<SCRIPT LANGUAGE=\"JavaScript\" src=\"../../inc/mytable.js\"></SCRIPT>\r\n<SCRIPT LANGUAGE=\"JavaScript\">\r\nfunction lock_ref(op)\r\n{\r\n  var xmlHttpObj=getXMLHttpObj();\r\n  xmlHttpObj.open(\"GET\",\"flock.php?ID=";
echo $uploadfile;
echo "&OP=\"+op,true);\r\n\r\n  xmlHttpObj.send(null);\r\n  setTimeout(\"lock_ref()\",180000);\r\n}\r\n</SCRIPT>\r\n<script LANGUAGE=\"javascript\" >\r\nfunction setBookMark()\r\n{\r\n\t";
$f = 0;
for ( ; $f < count( $Flow_item ) - 1; ++$f )
{
    $query = "SELECT * FROM  flow_run_data WHERE RUN_ID='".$pid."' and ITEM_DATA='$Flow_item[$f]'";
    $ITEM_ID = $msql->GetRow( $query );
    if ( $Flow_item[$f] != "正文区" )
    {
            continue;
    }
    else
    {
        echo "if(TANGER_OCX_OBJ.ActiveDocument.BookMarks.Exists(\"".$Flow_item[$f]."\")) TANGER_OCX_OBJ.SetBookMarkValue(\"$Flow_item[$f]\",\"$ITEM_ID[POST_DATA]\");";
    }
}
if ( file_exists( $NDOCFILE ) != NULL )
{
    echo "\r\n\tif(TANGER_OCX_OBJ.ActiveDocument.Bookmarks.Exists('正文区'))\r\n        {\r\n        TANGER_OCX_OBJ.ActiveDocument.Application.Selection.GoTo(-1,0,0,'正文区'); \r\n        TANGER_OCX_OBJ.AddTemplateFromURL('".$NDOCFILE."');\r\n        TANGER_OCX_OBJ.ActiveDocument.Bookmarks('正文区').Delete(); \r\n        }\r\n        ";
}
echo "\t//TANGER_OCX_OBJ.SaveToURL(\"test.doc\");\r\n\r\n\r\n     \r\n  \r\n}\r\n</script>\r\n<script>\r\nfunction myload()\r\n{\r\n  var coll = window.opener.document.all.tags(\"input\");\r\n  var my_flag1=0;\r\n  for (i=0; i<coll.length; i++)\r\n  {\r\n    if(coll[i].value== \"";
echo $DOCType;
echo "\")\r\n    {\r\n       my_flag1=1;\r\n       break;\r\n    }\r\n  }\r\n\r\n  if(!my_flag1)\r\n      window.close();\r\n      setTimeout(\"lock_ref()\",180000); \r\n\r\n  TANGER_OCX_SetInfo();\r\n \r\n\r\n}\r\n\r\nfunction MY_SetMarkModify(flag)\r\n{\r\n  if(flag)\r\n  {\r\n     mflag1.className=\"TableHeader2\";\r\n     mflag2.className=\"TableHeader1\";\r\n  }\r\n  else\r\n  {\r\n     mflag1.className=\"TableHeader1\";\r\n     mflag2.className=\"TableHeader2\";\r\n  }\r\n  TANGER_OCX_SetMarkModify(flag);\r\n}\r\n\r\nfunction MY_ShowRevisions(flag)\r\n{\r\n  if(flag)\r\n  {\r\n     sflag1.className=\"TableHeader2\";\r\n     sflag2.className=\"TableHeader1\";\r\n  }\r\n  else\r\n  {\r\n     sflag1.className=\"TableHeader1\";\r\n     sflag2.className=\"TableHeader2\";\r\n  }\r\n  TANGER_OCX_ShowRevisions(flag);\r\n}\r\n</script>\r\n</HEAD>\r\n\r\n<BODY class=\"bodycolor\" leftmargin=\"0\" topmargin=\"0\" onLoad=\"javascript:myload()\" onunload=\"javascript:close_doc()\" ";
echo base64_decode( $SHOWCODE );
echo ">\r\n\r\n<form  name=\"form1\" id=\"form1\" method=\"post\" action=\"upload.dxp.php\" enctype=\"multipart/form-data\">\r\n\r\n<table width=100% height=100% class=\"small\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">\r\n<tr width=100%>\r\n<td valign=top width=80>\r\n  <table border=\"0\" cellspacing=\"1\" width=\"100%\" class=\"small\" bgcolor=\"#000000\" cellpadding=\"3\" align=\"center\">\r\n     <tr class=\"";
echo $TableHeader;
echo "\">\r\n       <td nowrap align=\"center\">文件操作</td>\r\n     </tr>\r\n     <tr class=\"TableHeader1\" onclick=\"TANGER_OCX_SaveDoc(0)\" style=\"cursor:hand\">\r\n       <td nowrap align=\"center\">保存文件</td>\r\n     </tr>\r\n     <tr class=\"TableHeader1\" onclick=\"TANGER_OCX_SaveDoc(1)\" style=\"cursor:hand\">\r\n       <td nowrap align=\"center\">保存并关闭</td>\r\n     </tr>\r\n     <tr class=\"TableHeader1\" onclick=\"TANGER_OCX_ChgLayout()\" style=\"cursor:hand\">\r\n       <td nowrap align=\"center\">页面设置</td>\r\n     </tr>\r\n     <tr class=\"TableHeader1\" onclick=\"TANGER_OCX_PrintDoc()\" style=\"cursor:hand\">\r\n       <td nowrap align=\"center\">打印</td>\r\n     </tr>\r\n     ";
if ( strtolower( $File_Type ) == "doc" )
{
    echo "   <tr class=\"";
    echo $TableHeader;
    echo "\">\r\n     <td nowrap align=\"center\">文件编辑</td>\r\n   </tr>\r\n     <!--tr onclick=\"MY_SetMarkModify(true)\" style=\"cursor:hand\">\r\n       <td nowrap class=\"TableHeader2\" align=\"center\" id=\"mflag1\">保留痕迹</td>\r\n     </tr>\r\n     <tr onclick=\"MY_SetMarkModify(false)\" style=\"cursor:hand\">\r\n       <td nowrap class=\"TableHeader1\" align=\"center\" id=\"mflag2\">不留痕迹</td>\r\n     </tr-->\r\n     <tr onclick=\"MY_ShowRevisions(true)\" style=\"cursor:hand\">\r\n       <td nowrap class=\"TableHeader2\" align=\"center\" id=\"sflag1\">显示痕迹</td>\r\n     </tr>\r\n     <tr onclick=\"MY_ShowRevisions(false)\" style=\"cursor:hand\">\r\n       <td nowrap class=\"TableHeader1\" align=\"center\" id=\"sflag2\">隐藏痕迹</td>\r\n     </tr>\r\n     <tr onclick=\"TANGER_OCX_AddDocHeader('";
    echo $rid[UNIT_NAME];
    echo "')\" style=\"cursor:hand\">\r\n   \r\n     \r\n       <td nowrap class=\"TableHeader1\" align=\"center\">文件套红</td>\r\n     </tr>\r\n     <tr onclick=\"AddPictureFromLocal()\" style=\"cursor:hand\">\r\n       <td nowrap class=\"TableHeader1\" align=\"center\">插入图片</td>\r\n     </tr>\r\n     ";
}
do
{
    if ( strtolower( $File_Type ) == "doc" )
        break;
} while( 0 );
echo "   <tr class=\"";
echo $TableHeader;
echo "\">\r\n     <td nowrap align=\"center\">电子认证</td>\r\n   </tr>\r\n     <tr class=\"TableHeader1\" onclick=\"DoCheckSign()\" style=\"cursor:hand\">\r\n       <td nowrap align=\"center\">验证签名及印章</td>\r\n     </tr>\r\n     <tr class=\"TableHeader1\" onclick=\"DoHandSign2()\" style=\"cursor:hand\">\r\n       <td nowrap align=\"center\">全屏手写签名</td>\r\n     </tr>\r\n     <tr class=\"TableHeader1\" onclick=\"DoHandDraw2()\" style=\"cursor:hand\">\r\n       <td nowrap align=\"center\">全屏手工绘图</td>\r\n     </tr>\r\n     <tr class=\"TableHeader1\" onclick=\"DoHandSign()\" style=\"cursor:hand\">\r\n       <td nowrap align=\"center\">插入手写签名</td>\r\n     </tr>\r\n\r\n     <tr class=\"TableHeader1\" onclick=\"DoHandDraw()\" style=\"cursor:hand\">\r\n       <td nowrap align=\"center\">插入手工绘图</td>\r\n     </tr>\r\n     <tr class=\"TableHeader1\" onclick=\"AddSignFromLocal()\"style=\"cursor:hand\">\r\n       <td nowrap align=\"center\">添加本地印章</td>\r\n     </tr>\r\n      <tr class=\"TableHeader1\" >\r\n       <td nowrap align=\"center\">\r\n       <select id=\"HeadSelect\">\r\n";
$msql->query( "select * from  flow_pub  where Flag='H' order by ID" );
while ( $msql->next_record( ) )
{
    $th_id = "../../general/templet/mod/".$msql->f( "ID" ).".doc";
    $th_name = $msql->f( "pub_name" );
    echo "<option value=".$th_id.">$th_name</option>";
}
echo "  </select>\r\n<br>\r\n<button class=\"TableHeader1\" onclick='var selObj = document.all(\"HeadSelect\");var tempurl=selObj.options[selObj.selectedIndex].value;TANGER_OCX_DoPaiBan(tempurl)'>添加所选红头</button>\r\n     </td></tr>\r\n \r\n     <tr class=\"TableHeader1\" >\r\n       <td nowrap align=\"center\">\r\n      <select id=\"SignSelect\">\r\n       ";
$msql->query( "select * from  print_type  where 1 order by TYPE_ID" );
while ( $msql->next_record( ) )
{
    $Type_name = $msql->f( "TYPE_NAME" );
    $Esp_name = $msql->f( "ESP_NAME" );
    echo "<option value=\"../../general/templet/mod/".$Esp_name."\">$Type_name</option>";
}
echo "</select>\r\n<br>\r\n<button class=\"TableHeader1\" onclick='var selObj = document.all(\"SignSelect\");var tempurl=selObj.options[selObj.selectedIndex].value;AddSignFromURL(tempurl)'>添加所选章</button>\r\n</td></tr>\r\n";
echo "  </table>\r\n</td>\r\n<td width=100% valign=\"top\">\r\n<script src=\"/includes/ntko/ntkooffice.js\"></script>\r\n</td>\r\n</tr>\r\n</table>\r\n\r\n<script language=\"JScript\" for=TANGER_OCX event=\"OnDocumentClosed()\">\r\nTANGER_OCX_OnDocumentClosed()\r\n</script>\r\n\r\n<script language=\"JScript\">\r\nvar TANGER_OCX_str;\r\nvar TANGER_OCX_obj;\r\n\r\nfunction close_doc()\r\n{\r\n   document.all(\"TANGER_OCX\").setAttribute(\"IsNoCopy\",false);\r\n   if(TANGER_OCX_bDocOpen)\r\n   {\r\n     msg='是否保存对  \\'";
echo $ATTACHMENT_NAME;
echo "\\'  的修改？';\r\n     if(window.confirm(msg))\r\n        TANGER_OCX_SaveDoc(0);\r\n   }\r\n    lock_ref('1');\r\n}\r\n</script>\r\n ";
if ( strtolower( $File_Type ) == "doc" )
{
    echo "<script language=\"JScript\" for=TANGER_OCX event=\"OnDocumentOpened(TANGER_OCX_str,TANGER_OCX_obj)\">\r\nTANGER_OCX_OnDocumentOpened(TANGER_OCX_str,TANGER_OCX_obj);\r\n\r\nTANGER_OCX_SetMarkModify(true);\r\nTANGER_OCX_ShowRevisions(false);\r\nTANGER_OCX_OBJ.ActiveDocument.Application.username='";
    echo $USERNAME;
    echo "';\r\nTANGER_OCX_OBJ.ActiveDocument.PrintRevisions = false;\r\n";
    echo $Hc_text;
    echo "</script>\r\n<script language=\"JScript\" for=\"TANGER_OCX\" event=\"OnSignSelect(issign,signinfo)\">\r\n//TANGER_OCX_OnSignSelect(issign,signinfo)\r\n</script>\r\n";
}
do
{
    if ( strtolower( $File_Type ) == "xls" )
        break;
} while( 0 );
echo "<script language=\"JScript\" for=TANGER_OCX event=\"OnDocumentOpened(TANGER_OCX_str,TANGER_OCX_obj)\">\r\nTANGER_OCX_OnDocumentOpened(TANGER_OCX_str,TANGER_OCX_obj);\r\n \r\n</script>\r\n<script language=\"JScript\" for=\"TANGER_OCX\" event=\"OnSignSelect(issign,signinfo)\">\r\n//TANGER_OCX_OnSignSelect(issign,signinfo)\r\n</script>\r\n";
echo "<span id=\"TANGER_OCX_op\" style=\"display:none\">";
echo $OP;
echo "</span>\r\n<span id=\"TANGER_OCX_filename\" style=\"display:none\">";
echo $ATTACHMENT_NAME;
echo "</span>\r\n<span id=\"TANGER_OCX_attachName\" style=\"display:none\">";
echo $ATTACHMENT_NAME;
echo "</span>\r\n<span id=\"TANGER_OCX_attachURL\" style=\"display:none\">read_doc.php?OP=";
echo $OP;
echo "&filename=";
echo $ATTACHMENT_NAME;
echo "&uploaddir=";
echo $uploadfile;
echo "</span>\r\n<span id=\"TANGER_OCX_user\" style=\"display:none\">";
echo $USERNAME;
echo "</span>\r\n\r\n<input style=\"display:none\" type=\"file\" name=\"ATTACHMENT\">\r\n<input type=\"hidden\" name=\"ATTACHMENT_ID\" value=\"";
echo $ATTACHMENT_ID;
echo "\">\r\n<input type=\"hidden\" name=\"ATTACHMENT_NAME\" value=\"";
echo $ATTACHMENT_NAME;
echo "\">\r\n</form>\r\n\r\n</body>\r\n</html>\r\n";
?>
