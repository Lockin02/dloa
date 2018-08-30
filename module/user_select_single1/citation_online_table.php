<?php
include( "../../includes/db.inc.php" );
include( "../../includes/config.php" );
include( "../../includes/msql.php" );
$msql->query( "select * from user_count  where login_time like '".date( "Y-m-d" )."%'" );
$timeout = 120;
$temp = "";
while ( $msql->next_record( ) )
{
    if ( $msql->f( "User_ID" ) != $USER_ID )
    {
            continue;
    }
    else
    {
        if ( time( ) < $msql->f( "User_time" ) )
        {
                continue;
        }
        else
        {
            $temp .= trim( $msql->f( "User_ID" ) ).",";
        }
    }
}
echo "<html>\r\n<head>\r\n<link rel=\"stylesheet\" type=\"text/css\" href=\"../../inc/style.css\">\r\n<script src=\"../../general/daily/yjgl/select.js\" type=\"text/javascript\"></script>\r\n<script language=\"javascript\">\r\n<!--\r\nvar parent_window = parent.dialogArguments;\r\n\r\nfunction click_user(user_id)\r\n{\r\n  TO_VAL=parent_window.form2.";
echo $toid;
echo ".value;\r\n  targetelement=document.all(user_id);\r\n  user_name=targetelement.name;\r\n if(TO_VAL.indexOf(user_id)<0 && TO_VAL.indexOf(user_id)!=0) \r\n  {\r\n    \r\n    parent_window.form2.";
echo $toid;
echo ".value=user_id;\r\n    parent_window.form2.";
echo $toname;
echo ".value=user_name;\r\n    borderize_on(targetelement);\r\n  }\r\n}\r\n\r\nfunction borderize_on(targetelement)\r\n{\r\n color=\"#003FBF\";\r\n targetelement.style.borderColor=\"black\";\r\n targetelement.style.backgroundColor=color;\r\n targetelement.style.color=\"white\";\r\n targetelement.style.fontWeight=\"bold\";\r\n}\r\n\r\nfunction borderize_off(targetelement)\r\n{\r\n  targetelement.style.backgroundColor=\"\";\r\n  targetelement.style.borderColor=\"\";\r\n  targetelement.style.color=\"\";\r\n  targetelement.style.fontWeight=\"\";\r\n}\r\n\r\n\r\n-->\r\n</script>\r\n</head>\r\n\r\n<body  topmargin=\"5\" >\r\n\r\n\t<table style=\"line-height: 150%;font-size:9pt;color:#001A41\" bordercolorlight=\"#CCCCCC\" bordercolordark=\"#FFFFFF\"\r\n                border=\"1\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" align=\"center\">\r\n                <tbody>\r\n                    <tr bgcolor=\"#FED547\">                     \r\n                        <td nowrap=\"nowrap\" class=\"table_font\" align=\"center\" colspan=\"2\">选择在线人员</td>\r\n                                          \r\n                    </tr>\r\n\t\t\t\t\t";
$uid = "";
$cont_i = 0;
$uid = strtok( $temp, "," );
do
{
    do
    {
        $table_name = " user u,department d";
    } while ( $uid == "" );
    $sql = "select u.USER_NAME,u.USER_ID,d.DEPT_NAME from ".$table_name." where u.HAS_LEFT='0' and u.DEPT_ID=d.DEPT_ID and u.USER_ID='".$uid."' ";
    $msql->query( $sql );
    while ( $msql->next_record( ) )
    {
        ++$cont_i;
        echo "\t\t\t\t<tr id=\"";
        echo $msql->f( "USER_ID" );
        echo "\" name=\"";
        echo $msql->f( "USER_NAME" );
        echo "\"  onclick=\"javascript:click_user('";
        echo $msql->f( "USER_ID" );
        echo "')\" class=\"menulines\" style=\"cursor:hand\" align=\"center\">\r\n  <td nowrap=\"nowrap\">";
        echo $msql->f( "DEPT_NAME" );
        echo "</td><td>";
        echo $msql->f( "USER_NAME" );
        echo "</td>\r\n</tr>\r\n\t\t\r\n";
    }
    $uid = strtok( "," );
} while( 1 );
echo "                \r\n                </tbody>\r\n            </table>\r\n\t\t\t\r\n</body>\r\n</html>\r\n";
?>
