<?php
/*********************/
/*                   */
/*  Dezend for PHP5  */
/*         NWS       */
/*      Nulled.WS    */
/*                   */
/*********************/

include( "../../includes/db.inc.php" );
include( "../../includes/config.php" );
include( "../../includes/msql.php" );
echo "<html>\r\n<head>\r\n<link rel=\"stylesheet\" type=\"text/css\" href=\"../../inc/style.css\">\r\n<script src=\"../../general/daily/yjgl/select.js\" type=\"text/javascript\"></script>\r\n<script language=\"javascript\">\r\n<!--\r\nvar parent_window = parent.dialogArguments;\r\n\r\nfunction borderize_on(targetelement)\r\n{\r\n color=\"#003FBF\";\r\n targetelement.style.borderColor=\"black\";\r\n targetelement.style.backgroundColor=color;\r\n targetelement.style.color=\"white\";\r\n targetelement.style.fontWeight=\"bold\";\r\n}\r\n\r\nfunction borderize_off(targetelement)\r\n{\r\n  targetelement.style.backgroundColor=\"\";\r\n  targetelement.style.borderColor=\"\";\r\n  targetelement.style.color=\"\";\r\n  targetelement.style.fontWeight=\"\";\r\n}\r\n\r\nfunction begin_set()\r\n{\r\n  var TO_VAL=parent_window.$formname.";
echo $toid;
echo ".value;\r\n\r\n  for (var step_i=0; step_i<document.all.length; step_i++)\r\n  {\r\n    if(document.all(step_i).className==\"menulines\")\r\n    {\r\n       user_id=document.all(step_i).id;\r\n       if(TO_VAL.indexOf(\",\"+user_id+\",\")>0 || TO_VAL.indexOf(user_id+\",\")==0)\r\n          borderize_on(document.all(step_i));\r\n    }\r\n  }\r\n}\r\n\r\nfunction add_all()\r\n{\r\n  var TO_VAL=parent_window.$formname.";
echo $toid;
echo ".value;\r\n  for (var step_i=0; step_i<document.all.length; step_i++)\r\n  {\r\n    if(document.all(step_i).className==\"menulines\")\r\n    {\r\n       user_id=document.all(step_i).id;\r\n       user_name=document.all(step_i).name;\r\n\r\n       if(TO_VAL.indexOf(\",\"+user_id+\",\")<0 && TO_VAL.indexOf(user_id+\",\")!=0)\r\n       {\r\n         parent_window.$formname.";
echo $toid;
echo ".value+=user_id+\",\";\r\n         parent_window.$formname.";
echo $toname;
echo ".value+=user_name+\",\";\r\n         borderize_on(document.all(step_i));\r\n       }\r\n    }\r\n  }\r\n}\r\n\r\nfunction del_all()\r\n{\r\n  for (var step_i=0; step_i<document.all.length; step_i++)\r\n  {\r\n    TO_VAL=parent_window.$formname.";
echo $toid;
echo ".value;\r\n    if(document.all(step_i).className==\"menulines\")\r\n    {\r\n       user_id=document.all(step_i).id;\r\n       user_name=document.all(step_i).name;\r\n       if(TO_VAL.indexOf(user_id+\",\")==0)\r\n       {\r\n          parent_window.$formname.";
echo $toid;
echo ".value=parent_window.$formname.";
echo $toid;
echo ".value.replace(user_id+\",\",\"\");\r\n          parent_window.$formname.";
echo $toname;
echo ".value=parent_window.$formname.";
echo $toname;
echo ".value.replace(user_name+\",\",\"\");\r\n          borderize_off(document.all(step_i));\r\n       }\r\n       if(TO_VAL.indexOf(\",\"+user_id+\",\")>0)\r\n       {\r\n          parent_window.$formname.";
echo $toid;
echo ".value=parent_window.$formname.";
echo $toid;
echo ".value.replace(\",\"+user_id+\",\",\",\");\r\n          parent_window.$formname.";
echo $toname;
echo ".value=parent_window.$formname.";
echo $toname;
echo ".value.replace(\",\"+user_name+\",\",\",\");\r\n          borderize_off(document.all(step_i));\r\n       }\r\n    }\r\n  }\r\n}\r\n-->\r\n</script>\r\n</head>\r\n\r\n<body  topmargin=\"5\"  onload=\"begin_set()\">\r\n\t<table style=\"line-height: 150%;font-size:9pt;color:#001A41\" bordercolorlight=\"#CCCCCC\" bordercolordark=\"#FFFFFF\"\r\n                border=\"1\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" align=\"center\">\r\n                <tbody>\r\n\t\t\t\t\r\n<tr>\r\n\t<td onclick=\"javascript:add_all();\" style=\"cursor:hand\" align=\"center\" bgcolor=\"#FED547\">所有人员</td>  \r\n</tr>\r\n";
$table_name = " user ";
$cont_i = 0;
$t = " ";
$sql = "select USER_NAME, USER_ID from user where DEL='0' and HAS_LEFT='0' order by USER_NAME ASC";
$msql->query( $sql );
if ( !$msql->next_record( ) )
{
    break;
}
else
{
    do
    {
        ++$cont_i;
        echo "\t\t\t\t\t\t\r\n\t\t<!--************** 在此传值给左边的详细信息barg_customer_part.php(ID号,客户编号)*******-->\r\n\t\t<tr id=\"";
        echo $msql->f( "USER_ID" );
        echo "\" name=\"";
        echo $msql->f( "USER_NAME" );
        echo "\"  onclick=\"javascript:click_user('";
        echo $msql->f( "USER_ID" );
        echo "')\" class=\"menulines\" style=\"cursor:hand;display:none \" align=\"center\">\r\n                                          <td align=\"center\" nowrap=\"nowrap\">\r\n\t\t\t\t\t\t\t";
        echo $msql->f( "USER_NAME" )."&nbsp;";
        echo "                           \r\n                        </td>\r\n                    </tr>\r\n";
    } while ( $msql->next_record( ) );
}
echo "<tr class=\"TableContent\">\r\n  <td onclick=\"javascript:del_all();\" style=\"cursor:hand\" align=\"center\" >全部删除</td>\r\n</tr>                \r\n                </tbody>\r\n            </table>\r\n</body>\r\n</html>\r\n";
?>
