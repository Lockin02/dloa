<?php
session_start( );
echo "<html>\r\n<head>\r\n<title></title>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=gb2312\">\r\n<link rel=\"stylesheet\" type=\"text/css\" href=\"../../inc/style.css\">\r\n<script language=\"JavaScript\">\r\nvar CUR_ID=\"1\";\r\nfunction clickMenu(ID)\r\n{\r\n    targetelement=document.all(CUR_ID);\r\n    if(ID==CUR_ID)\r\n    {\r\n       if (targetelement.style.display==\"none\")\r\n           targetelement.style.display='';\r\n       else\r\n           targetelement.style.display=\"none\";\r\n    }\r\n    else\r\n    {\r\n       targetelement.style.display=\"none\";\r\n       targetelement=document.all(ID);\r\n       targetelement.style.display=\"\";\r\n    }\r\n\r\n    CUR_ID=ID;\r\n}\r\n</script>\r\n</head>\r\n\r\n<body class=\"bodycolor\"  topmargin=\"1\" leftmargin=\"0\">\r\n\r\n  <table border=\"0\" cellspacing=\"1\" width=\"100%\" class=\"small\" bgcolor=\"#000000\" cellpadding=\"3\" align=\"center\">\r\n     <tr class=\"TableHeader\" onclick=\"clickMenu('1')\" style=\"cursor:hand\" title=\"点击伸缩列表\">\r\n       <td nowrap align=\"center\"><b>按部门选择</b></td>\r\n     </tr>\r\n  </table>\r\n  <table border=\"0\" cellspacing=\"1\" width=\"100%\" class=\"small\" cellpadding=\"3\" id=\"1\">\r\n    <tr class=\"TableControl\">\r\n      <td>\r\n\r\n  <div>\r\n  ";
include( "citation_user_top.php" );
echo "  </div>\r\n\r\n      </td>\r\n    </tr>\r\n  </table>\r\n  <table border=\"0\" cellspacing=\"1\" width=\"100%\" class=\"small\" bgcolor=\"#000000\" cellpadding=\"3\" align=\"center\">\r\n     <tr class=\"TableHeader\" onclick=\"clickMenu('0')\" style=\"cursor:hand\" title=\"点击伸缩列表\">\r\n       <td nowrap align=\"center\"><b>按角色选择</b></td>\r\n     </tr>\r\n  </table>\r\n  <table border=\"0\" cellspacing=\"1\" width=\"100%\" class=\"small\" bgcolor=\"#000000\" cellpadding=\"3\" id=\"0\" style=\"display:none\">\r\n\t";
$msql->query( "select USER_PRIV,PRIV_NAME from user_priv order by PRIV_NO" );
while ( $msql->next_record( ) )
{
    echo "\t<tr class=\"TableControl\">\r\n      \t<td align=\"center\" onclick=\"javascript:parent.user.location='citation_priv_table.php?id=";
    echo $msql->f( "USER_PRIV" );
    echo "&toid=";
    echo $toid;
    echo "&toname=";
    echo $toname;
    echo "';\" style=\"cursor:hand\">";
    echo $msql->f( "PRIV_NAME" );
    echo "</td>\r\n    </tr>\r\n  ";
}
echo "        \r\n</table>\r\n<table border=\"0\" cellspacing=\"1\" width=\"100%\" class=\"small\" bgcolor=\"#000000\" cellpadding=\"3\" align=\"center\">\r\n     <tr class=\"TableHeader\" onclick=\"clickMenu('2')\" style=\"cursor:hand\" title=\"点击伸缩列表\">\r\n       <td nowrap align=\"center\"><b>按自定义用户组</b></td>\r\n     </tr>\r\n</table>\r\n<table border=\"0\" cellspacing=\"1\" width=\"100%\" class=\"small\" bgcolor=\"#000000\" cellpadding=\"3\" id=\"2\" style=\"display:none\">\r\n";
$msql->query( "select USER_GROUP,GROUP_NAME from user_group where attribute_one='".$USER_ID."' order by GROUP_NO" );
while ( $msql->next_record( ) )
{
    echo "\t<tr class=\"TableControl\">\r\n      \t<td align=\"center\" onclick=\"javascript:parent.user.location='citation_attribute_table.php?id=";
    echo $msql->f( "USER_GROUP" );
    echo "&toid=";
    echo $toid;
    echo "&toname=";
    echo $toname;
    echo "';\" style=\"cursor:hand\">";
    echo $msql->f( "GROUP_NAME" );
    echo "</td>\r\n    </tr>\r\n  ";
}
echo "</table>\r\n<table border=\"0\" cellspacing=\"1\" width=\"100%\" class=\"small\" bgcolor=\"#000000\" cellpadding=\"3\" align=\"center\">\r\n     <tr class=\"TableHeader\" style=\"cursor:hand\" onclick=\"javascript:parent.user.location='citation_online_table.php?ID=&toid=";
echo $toid;
echo "&toname=";
echo $toname;
echo "';\">\r\n       <td nowrap align=\"center\"><b>在线人员</b></td>\r\n     </tr>\r\n</table>\r\n<table border=\"0\" cellspacing=\"1\" width=\"100%\" class=\"small\" bgcolor=\"#000000\" cellpadding=\"3\" align=\"center\">\r\n     <tr class=\"TableHeader\" style=\"cursor:hand\" onclick=\"javascript:parent.user.location='query.php?ID=&toid=";
echo $toid;
echo "&toname=";
echo $toname;
echo "';\">\r\n       <td nowrap align=\"center\"><b>人员查询</b></td>\r\n     </tr>\r\n</table>\r\n</body>\r\n</html>\r\n";
?>
