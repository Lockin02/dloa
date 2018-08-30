<?php
include( "../../includes/db.inc.php" );
include( "../../includes/config.php" );
include( "../../includes/msql.php" );
echo "<html>\r\n<head>\r\n<title></title>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=gb2312\">\r\n<link rel=\"stylesheet\" type=\"text/css\" href=\"../../inc/style.css\">\r\n<style>\r\n.menulines{}\r\n</style>\r\n\r\n<SCRIPT>\r\n<!--\r\n\r\nvar menu_enter=\"\";\r\n\r\nfunction borderize_on(e)\r\n{\r\n color=\"#708DDF\";\r\n source3=event.srcElement\r\n\r\n if(source3.className==\"menulines\" && source3!=menu_enter)\r\n    source3.style.backgroundColor=color;\r\n}\r\n\r\nfunction borderize_on1(e)\r\n{\r\n for (i=0; i<document.all.length; i++)\r\n { document.all(i).style.borderColor=\"\";\r\n   document.all(i).style.backgroundColor=\"\";\r\n   document.all(i).style.color=\"\";\r\n   document.all(i).style.fontWeight=\"\";\r\n }\r\n\r\n color=\"#003FBF\";\r\n source3=event.srcElement\r\n\r\n if(source3.className==\"menulines\")\r\n { source3.style.borderColor=\"black\";\r\n   source3.style.backgroundColor=color;\r\n   source3.style.color=\"white\";\r\n   source3.style.fontWeight=\"bold\";\r\n }\r\n\r\n menu_enter=source3;\r\n}\r\n\r\nfunction borderize_off(e)\r\n{\r\n source4=event.srcElement\r\n\r\n if(source4.className==\"menulines\" && source4!=menu_enter)\r\n    {source4.style.backgroundColor=\"\";\r\n     source4.style.borderColor=\"\";\r\n    }\r\n}\r\n\r\n//-->\r\n</SCRIPT></head>\r\n\r\n<body class=\"bodycolor\" onMouseover=\"borderize_on(event)\" onMouseout=\"borderize_off(event)\" onclick=\"borderize_on1(event)\" topmargin=\"5\">\r\n\r\n\r\n    <table border=\"1\" cellspacing=\"0\" width=\"95%\" class=\"small\" cellpadding=\"3\"  bordercolorlight=\"#000000\" bordercolordark=\"#FFFFFF\" align=\"center\">\r\n";
$msql->query( "select * from department  where 1 order by DEPT_NO" );
while ( $msql->next_record( ) )
{
    $ID = $msql->f( "DEPT_ID" );
    $DEPT_NAME = $msql->f( "DEPT_NAME" );
    $DEPT_NO = $msql->f( "DEPT_NO" );
    echo "<tr class=\"TableControl\">\r\n  <td class=\"menulines\" align=\"center\" onclick=\"javascript:parent.user.location='user.php?DEPTID=";
    echo $DEPT_NO;
    echo "&DEPT_NM=";
    echo $DEPT_NAME;
    echo "';\" style=\"cursor:hand\">";
    echo $DEPT_NAME;
    echo "</td>\r\n</tr>\r\n";
}
echo "<thead class=\"TableControl\">\r\n  <th bgcolor=\"#d6e7ef\" align=\"center\"><b>选择部门</b></th>\r\n</thead>\r\n</table>\r\n</body>\r\n</html>\r\n";
?>
