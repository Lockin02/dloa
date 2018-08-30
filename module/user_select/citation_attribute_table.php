<?php
include( "../../includes/db.inc.php" );
include( "../../includes/config.php" );
include( "../../includes/msql.php" );
include( "../../includes/fsql.php" );
if (!isset($_GET["id"]) || $_GET["id"] == "" )
    return;
$fsql->query( "select GROUP_NAME,FUNC_ID_STR from user_group where USER_GROUP='".$_GET["id"]."'" );
$fsql->next_record( );
$Temp_strtok = $fsql->f( "FUNC_ID_STR" );
$newuu = "";
$uu = explode( ",", $Temp_strtok );
$u = 0;
for ( ; $u < count( $uu ) - 1; ++$u )
{
    $msql->query( "select * from user where DEL='0' and USER_ID='".$uu[$u]."' and HAS_LEFT='0'" );
    if ( $msql->next_record( ) )
    {
        $newuu .= $uu[$u].",";
    }
}
echo "<html>\r\n<head>\r\n<link rel=\"stylesheet\" type=\"text/css\" href=\"../../inc/style.css\">\r\n<script src=\"../../general/daily/yjgl/select.js\" type=\"text/javascript\"></script>\r\n<script language=\"javascript\">\r\n<!--\r\nvar parent_window = parent.dialogArguments;\r\nfunction click_user(user_id)\r\n{\r\n  TO_VAL=parent_window.$formname.";
echo $toid;
echo ".value;\r\n  targetelement=document.all(user_id);\r\n  user_name=targetelement.name;\r\n\r\n  if(TO_VAL.indexOf(\",\"+user_id+\",\")>0 || TO_VAL.indexOf(user_id+\",\")==0)\r\n  {\r\n    if(TO_VAL.indexOf(user_id+\",\")==0)\r\n    {\r\n       parent_window.$formname.";
echo $toid;
echo ".value=parent_window.$formname.";
echo $toid;
echo ".value.replace(user_id+\",\",\"\");\r\n       parent_window.$formname.";
echo $toname;
echo ".value=parent_window.$formname.";
echo $toname;
echo ".value.replace(user_name+\",\",\"\");\r\n       borderize_off(targetelement);\r\n    }\r\n    if(TO_VAL.indexOf(\",\"+user_id+\",\")>0)\r\n    {\r\n       parent_window.$formname.";
echo $toid;
echo ".value=parent_window.$formname.";
echo $toid;
echo ".value.replace(\",\"+user_id+\",\",\",\");\r\n       parent_window.$formname.";
echo $toname;
echo ".value=parent_window.$formname.";
echo $toname;
echo ".value.replace(\",\"+user_name+\",\",\",\");\r\n       borderize_off(targetelement);\r\n    }\r\n  }\r\n  else\r\n  {\r\n    parent_window.$formname.";
echo $toid;
echo ".value+=user_id+\",\";\r\n    parent_window.$formname.";
echo $toname;
echo ".value+=user_name+\",\";\r\n    borderize_on(targetelement);\r\n  }\r\n}\r\nfunction click_user111(user_id)\r\n{\r\n  TO_VAL=parent_window.$formname.";
echo $toid;
echo ".value;\r\n  targetelement=document.all(user_id);\r\n  user_name=targetelement.name;\r\n if(TO_VAL.indexOf(user_id)<0 && TO_VAL.indexOf(user_id)!=0) \r\n  {\r\n    \r\n    parent_window.$formname.";
echo $toid;
echo ".value=user_id;\r\n    parent_window.$formname.";
echo $toname;
echo ".value=user_name;\r\n    borderize_on(targetelement);\r\n  }\r\n}\r\n\r\nfunction borderize_on(targetelement)\r\n{\r\n color=\"#003FBF\";\r\n targetelement.style.borderColor=\"black\";\r\n targetelement.style.backgroundColor=color;\r\n targetelement.style.color=\"white\";\r\n targetelement.style.fontWeight=\"bold\";\r\n}\r\n\r\nfunction borderize_off(targetelement)\r\n{\r\n  targetelement.style.backgroundColor=\"\";\r\n  targetelement.style.borderColor=\"\";\r\n  targetelement.style.color=\"\";\r\n  targetelement.style.fontWeight=\"\";\r\n}\r\n\r\n\r\n-->\r\n</script>\r\n</head>\r\n\r\n<body  topmargin=\"5\">\r\n\t<table style=\"line-height: 150%;font-size:9pt;color:#001A41\" bordercolorlight=\"#CCCCCC\" bordercolordark=\"#FFFFFF\"\r\n                border=\"1\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" align=\"center\">\r\n                <tbody>\r\n\t\t\t\t";
$cont_i = 0;
$t = " ";
$Temp_strtok = "";
$Temp_user = "";
$Temp_strtok = $newuu;
if ( $Temp_strtok != "" )
{
    echo "                    <tr bgcolor=\"#FED547\">                   \r\n                        <td nowrap=\"nowrap\" class=\"table_font\" align=\"center\" colspan=\"2\">";
    echo $fsql->f( "GROUP_NAME" );
    echo "</td>\r\n                                          \r\n                    </tr>\r\n\t\t\t\t\t\r\n\r\n";
    $Temp_user = strtok( $Temp_strtok, "," );
    while( $Temp_user )
    {
        $msql->query( "select u.USER_ID,u.USER_NAME,d.DEPT_NAME from user u,department d where u.DEL='0' and u.HAS_LEFT='0' and u.DEPT_ID=d.DEPT_ID and u.USER_ID='".$Temp_user."' order by d.Depart_x ASC" );
        $msql->next_record( );
        ++$cont_i;
        echo "\t\t\t\t\t\t\r\n\t\t<!--************** 在此传值给左边的详细信息barg_customer_part.php(ID号,客户编号)*******-->\r\n\t\t<tr id=\"";
        echo $msql->f( "USER_ID" );
        echo "\" name=\"";
        echo $msql->f( "USER_NAME" );
        echo "\"  onclick=\"javascript:click_user('";
        echo $msql->f( "USER_ID" );
        echo "')\" class=\"menulines\" style=\"cursor:hand\" align=\"center\">\r\n                  \r\n         \t\t\t\t<td align=\"center\" nowrap=\"nowrap\">\r\n\t\t\t\t\t\t\t";
        echo $msql->f( "DEPT_NAME" );
        echo "                           \r\n                        </td>\r\n                        <td align=\"center\" nowrap=\"nowrap\">\r\n\t\t\t\t\t\t\t";
        echo $msql->f( "USER_NAME" )."&nbsp;";
        echo "                           \r\n                        </td>\r\n                    </tr>\r\n";
        $Temp_user = strtok( "," );
    } ;
}
echo "                \r\n                </tbody>\r\n            </table>\r\n</body>\r\n</html>\r\n";
?>
