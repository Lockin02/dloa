<?php
include( "../../includes/db.inc.php" );
include( "../../includes/config.php" );
include( "../../includes/msql.php" );
echo "<html>\r\n<head>\r\n<title></title>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=gb2312\">\r\n<link rel=\"stylesheet\" type=\"text/css\" href=\"../../inc/style.css\">\r\n\r\n<style>\r\n.menulines{}\r\n</style>\r\n\r\n<SCRIPT>\r\n<!--\r\n\r\nvar menu_enter=\"\";\r\n\r\nfunction borderize_on(e)\r\n{\r\n color=\"#708DDF\";\r\n source3=event.srcElement\r\n\r\n if(source3.className==\"menulines\" && source3!=menu_enter)\r\n    source3.style.backgroundColor=color;\r\n}\r\n\r\nfunction borderize_on1(e)\r\n{\r\n for (i=0; i<document.all.length; i++)\r\n { document.all(i).style.borderColor=\"\";\r\n   document.all(i).style.backgroundColor=\"\";\r\n   document.all(i).style.color=\"\";\r\n   document.all(i).style.fontWeight=\"\";\r\n }\r\n\r\n color=\"#003FBF\";\r\n source3=event.srcElement\r\n\r\n if(source3.className==\"menulines\")\r\n { source3.style.borderColor=\"black\";\r\n   source3.style.backgroundColor=color;\r\n   source3.style.color=\"white\";\r\n   source3.style.fontWeight=\"bold\";\r\n }\r\n\r\n menu_enter=source3;\r\n}\r\n\r\nfunction borderize_off(e)\r\n{\r\n source4=event.srcElement\r\n\r\n if(source4.className==\"menulines\" && source4!=menu_enter)\r\n    {source4.style.backgroundColor=\"\";\r\n     source4.style.borderColor=\"\";\r\n    }\r\n}\r\n\r\n//-->\r\n</SCRIPT>\r\n<script Language=\"JavaScript\">\r\nvar parent_window = parent.dialogArguments;\r\n\r\nfunction add_user(user_id,user_name)\r\n{\r\n  TO_VAL=parent_window._fmMADoc.TO_ID.value;\r\n  if(TO_VAL.indexOf(user_id)<0 && TO_VAL.indexOf(user_id)!=0)\r\n  {\r\n    parent_window._fmMADoc.TO_ID.value=user_id;\r\n    parent_window._fmMADoc.TO_NAME.value=user_name;\r\n  }\r\n}\r\n</script>\r\n\r\n<script Language=JavaScript>var parent_window = parent.dialogArguments;function add_user(user_id,user_name) {";
echo "TO_VAL=parent_window._fmMADoc.".$toid.".value;";
echo "if(TO_VAL.indexOf(user_id)<0 && TO_VAL.indexOf(user_id)!=0) {";
echo "parent_window._fmMADoc.".$toid.".value=user_id;";
echo "parent_window._fmMADoc.".$toname.".value=user_name;";
echo "}}</script></head>\r\n\r\n<body class=\"bodycolor\" topmargin=\"5\">\r\n\r\n\r\n<table border=\"1\" cellspacing=\"0\" width=\"100%\" class=\"small\" cellpadding=\"3\"  bordercolorlight=\"#000000\" bordercolordark=\"#FFFFFF\"  onMouseover=\"borderize_on(event)\" onMouseout=\"borderize_off(event)\" onclick=\"borderize_on1(event)\">\r\n";
$msql->query( "select * from user  where DEPT_ID='".$DEPT_ID."' order by CANBROADCAST" );
while ( $msql->next_record( ) )
{
    $USER_NAME = $msql->f( "USER_NAME" );
    $USERID = $msql->f( "USER_ID" );
    echo "<tr class=\"TableControl\">\r\n  <td class=\"menulines\" align=\"center\" onclick=\"javascript:add_user('";
    echo $USERID;
    echo "','";
    echo $USER_NAME;
    echo "');\"  style=\"cursor:hand\">";
    echo $USER_NAME;
    echo "</td>\r\n</tr>\r\n";
}
echo "<thead class=\"TableControl\">\r\n  <th bgcolor=\"#d6e7ef\" colspan=\"2\"><b>选择人员</b></th>\r\n</thead>\r\n</table>\r\n\r\n\r\n\r\n\r\n</body>\r\n</html>\r\n";
?>
