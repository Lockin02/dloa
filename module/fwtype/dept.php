<?php
include( "../../includes/db.inc.php" );
include( "../../includes/config.php" );
include( "../../includes/msql.php" );
echo "<html>\r\n<head>\r\n<title>���</title>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=gb2312\">\r\n<link rel=\"stylesheet\" type=\"text/css\" href=\"../../inc/style.css\">\r\n<style>\r\n.menulines{}\r\n</style>\r\n\r\n<SCRIPT>\r\n<!--\r\n\r\nvar menu_enter=\"\";\r\n\r\nfunction borderize_on(e)\r\n{\r\n color=\"#708DDF\";\r\n source3=event.srcElement\r\n\r\n if(source3.className==\"menulines\" && source3!=menu_enter)\r\n    source3.style.backgroundColor=color;\r\n}\r\n\r\nfunction borderize_on1(e)\r\n{\r\n for (i=0; i<document.all.length; i++)\r\n { document.all(i).style.borderColor=\"\";\r\n   document.all(i).style.backgroundColor=\"\";\r\n   document.all(i).style.color=\"\";\r\n   document.all(i).style.fontWeight=\"\";\r\n }\r\n\r\n color=\"#003FBF\";\r\n source3=event.srcElement\r\n\r\n if(source3.className==\"menulines\")\r\n { source3.style.borderColor=\"black\";\r\n   source3.style.backgroundColor=color;\r\n   source3.style.color=\"white\";\r\n   source3.style.fontWeight=\"bold\";\r\n }\r\n\r\n menu_enter=source3;\r\n}\r\n\r\nfunction borderize_off(e)\r\n{\r\n source4=event.srcElement\r\n\r\n if(source4.className==\"menulines\" && source4!=menu_enter)\r\n    {source4.style.backgroundColor=\"\";\r\n     source4.style.borderColor=\"\";\r\n    }\r\n}\r\n\r\n//-->\r\n</SCRIPT>\r\n<script Language=JavaScript>var parent_window = parent.dialogArguments;function add_dept(dept_id,dept_name){";
echo "TO_VAL=parent_window.form1.".$toid.".value;";
echo "if(TO_VAL.indexOf(','+dept_id+',')<0 && TO_VAL.indexOf(dept_id+',')!=0 && (parent_window.form1.".$toid.".value!='ALL_DEPT'))\r\n  {";
echo "parent_window.form1.".$toid.".value+=dept_id+',';";
echo "parent_window.form1.".$toname.".value+=dept_name+',';";
echo "}}</script>\r\n\r\n\r\n\r\n</head>\r\n\r\n<body class=\"bodycolor\" onMouseover=\"borderize_on(event)\" onMouseout=\"borderize_off(event)\" onclick=\"borderize_on1(event)\" topmargin=\"5\">\r\n\r\n<table border=\"1\" cellspacing=\"0\" width=\"100%\" class=\"small\" cellpadding=\"3\"  bordercolorlight=\"#000000\" bordercolordark=\"#FFFFFF\"  onMouseover=\"borderize_on(event)\" onMouseout=\"borderize_off(event)\" onclick=\"borderize_on1(event)\">\r\n<tr class=\"TableContent\">\r\n  <td class=\"menulines\" onclick=\"javascript:add_alls();\" style=\"cursor:hand\" align=\"center\">����ȫ�����</td>\r\n</tr>\r\n\r\n";
$msql->query( "select * from select_type  where Flag='".$id."'" );
while ( $msql->next_record( ) )
{
    $DEPT_NAME = $msql->f( "TYPE_NAME" );
    $DEPT_ID = $msql->f( "TYPE_ID" );
    echo "<tr class=\"TableControl\">\r\n  <td class=\"menulines\" align=\"center\" onclick=\"javascript:add_dept('";
    echo $DEPT_ID;
    echo "','";
    echo $DEPT_NAME;
    echo "');\"  style=\"cursor:hand\">";
    echo $DEPT_NAME;
    echo "</td>\r\n</tr>\r\n";
    $add_all_str .= "add_dept('".$DEPT_ID."','$DEPT_NAME');\n ";
}
echo "\r\n<thead class=\"TableControl\">\r\n  <th bgcolor=\"#d6e7ef\" colspan=\"2\"><b>ѡ�����</b></th>\r\n</thead>\r\n</table>\r\n\r\n<script Language=\"JavaScript\">\r\nfunction add_alls()\r\n{\r\n";
echo $add_all_str;
echo "parent_window.form1.";
echo $toid;
echo ".value=\"ALL_DEPT\";\r\nparent.close();   \r\n\r\n}\r\n</script>\r\n<script Language=\"JavaScript\">\r\nfunction add_all()\r\n{\r\n  if(parent_window.form1.";
echo $toid;
echo ".value!=\"ALL_DEPT\")\r\n  {\r\n      }\r\n  parent_window.form1.";
echo $toid;
echo ".value=\"ALL_DEPT\";\r\n  parent.close();  \r\n}\r\n</script>\r\n\r\n</body>\r\n</html>\r\n";
?>
