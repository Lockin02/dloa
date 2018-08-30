<?php
include( "../../includes/db.inc.php" );
include( "../../includes/config.php" );
include( "../../includes/msql.php" );
echo "<html>\r\n<head>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=gb2312\">\r\n<link rel=\"stylesheet\" type=\"text/css\" href=\"../../inc/style.css\">\r\n<script src=\"../../general/daily/yjgl/select.js\" type=\"text/javascript\"></script>\r\n</head>\r\n<script Language=\"JavaScript\">\r\nvar parent_window = parent.dialogArguments;\r\n\r\n\r\nfunction click_user(user_id)\r\n{\r\n  TO_VAL=parent_window.form2.";
echo $toid;
echo ".value;\r\n  targetelement=document.all(user_id);\r\n  user_name=targetelement.name;\r\n if(TO_VAL.indexOf(user_id)<0 && TO_VAL.indexOf(user_id)!=0) \r\n  {\r\n    \r\n    parent_window.form2.";
echo $toid;
echo ".value=user_id;\r\n    parent_window.form2.";
echo $toname;
echo ".value=user_name;\r\n    borderize_on(targetelement);\r\n  }\r\n}\r\n\r\nfunction borderize_on(targetelement)\r\n{\r\n color=\"#003FBF\";\r\n targetelement.style.borderColor=\"black\";\r\n targetelement.style.backgroundColor=color;\r\n targetelement.style.color=\"white\";\r\n targetelement.style.fontWeight=\"bold\";\r\n}\r\n\r\nfunction borderize_off(targetelement)\r\n{\r\n  targetelement.style.backgroundColor=\"\";\r\n  targetelement.style.borderColor=\"\";\r\n  targetelement.style.color=\"\";\r\n  targetelement.style.fontWeight=\"\";\r\n}\r\n\r\n\r\nfunction check_USER_NAME()\r\n{\r\n\tif(form2.USER_NAME.value==\"\")\r\n\t{\r\n\t\talert(\"请输入姓名！\");\r\n\t\tform2.USER_NAME.focus();\r\n\t\treturn false;\r\n\t}\r\n\telse\r\n\t{\r\n\t\tform2.submit();\r\n\t}\r\n}\r\n</script>\r\n\r\n<body class=\"panel\" topmargin=\"5\" leftmargin=\"2\" onload=\"document.form2.USER_NAME.focus();\">\r\n<center>\r\n <form action=\"\" name=\"form2\" method=\"post\">\r\n  <label class=\"small\">姓名:</label><input type=\"text\" name=\"USER_NAME\" size=\"6\" class=\"SmallInput\" value=\"\">&nbsp;<input type=\"botton\" name=\"Submit\" onClick=\"check_USER_NAME();\" value=\"查询\" class=\"SmallButton\">\r\n  <input type=\"hidden\" name=\"ID\" value=\"\">\r\n </form>\r\n</center>\r\n<table border=\"1\" cellspacing=\"0\" width=\"100%\" class=\"small\" cellpadding=\"3\"  bordercolorlight=\"#000000\" bordercolordark=\"#FFFFFF\">\r\n\r\n";
if ( $USER_NAME == "" )
{
    $sql = "select USER_ID,USER_NAME from user where 0";
}
else
{
    $sql = "select u.USER_ID,u.USER_NAME,d.DEPT_NAME from user u,department d where u.DEPT_ID=d.DEPT_ID and u.USER_NAME like '%".$USER_NAME."%' order by d.Depart_x ASC";
}
$msql->query( $sql );
while ( $msql->next_record( ) )
{
    ++$cont_i;
    echo "\t\t\t\t\t\t\r\n\t\t<!--************** 在此传值给左边的详细信息barg_customer_part.php(ID号,客户编号)*******-->\r\n                <tr id=\"";
    echo $msql->f( "USER_ID" );
    echo "\" name=\"";
    echo $msql->f( "USER_NAME" );
    echo "\"  onclick=\"javascript:click_user('";
    echo $msql->f( "USER_ID" );
    echo "')\" class=\"menulines\" style=\"cursor:hand\" align=\"center\">\r\n         \t\t\t\t<td align=\"center\" nowrap=\"nowrap\">\r\n\t\t\t\t\t\t\t";
    echo $msql->f( "DEPT_NAME" );
    echo "                           \r\n                        </td>\r\n                        <td align=\"center\" nowrap=\"nowrap\">\r\n\t\t\t\t\t\t\t";
    echo $msql->f( "USER_NAME" )."&nbsp;";
    echo "                           \r\n                        </td>\r\n                    </tr>\r\n";
}
echo "\r\n</table>\r\n</body>\r\n</html>";
?>
