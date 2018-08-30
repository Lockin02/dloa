<?php
include( "../../includes/db.inc.php" );
include( "../../includes/config.php" );
include( "../../includes/msql.php" );
include( "../../includes/fsql.php" );
$fsql->query( "select GROUP_NAME,FUNC_ID_STR from user_group where USER_GROUP='".$id."'" );
$fsql->next_record( );
$Temp_strtok = $fsql->f( "FUNC_ID_STR" );
$newuu = "";
$uu = explode( ",", rtrim($Temp_strtok,","));
$u = 0;
for ( ; $u < count( $uu ); ++$u )
{
    $msql->query( "select * from user where DEL='0' and HAS_LEFT='0' and USER_ID='".$uu[$u]."'" );
    if ( $msql->next_record( ) )
    {
        $newuu .= $uu[$u].",";
    }
}
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="../../inc/style.css">
<script src="../../general/daily/yjgl/select.js" type="text/javascript"></script>
<script language="javascript">
<!--
var parent_window = parent.dialogArguments;
function click_user(user_id)
{
  TO_VAL=parent_window.<?php echo $formname;?>.<?php echo $toid;?>.value;
  targetelement=document.all(user_id);
  user_name=targetelement.name;
 if(TO_VAL!=user_id) 
  {
    
    parent_window.<?php echo $formname;?>.<?php echo $toid;?>.value=user_id;
    parent_window.<?php echo $formname;?>.<?php echo $toname;?>.value=user_name;
    borderize_on(targetelement);
    borderize_clear(user_id);  
  }else
  {
    parent_window.<?php echo $formname;?>.<?php echo $toid;?>.value="";
    parent_window.<?php echo $formname;?>.<?php echo $toname;?>.value="";
    borderize_off(targetelement);  
  }
}
function borderize_off(targetelement)
{
 targetelement.style.borderColor="";
 targetelement.style.backgroundColor="";
 targetelement.style.color="";
 targetelement.style.fontWeight="";
}

function borderize_on(targetelement)
{
 color="#003FBF";
 targetelement.style.borderColor="black";
 targetelement.style.backgroundColor=color;
 targetelement.style.color="white";
 targetelement.style.fontWeight="bold";
}

function borderize_clear(user_id)
{  
    targetelements = document.getElementsByTagName("tr");
    for(var i=0;i<targetelements.length;i++)
    {
        if(targetelements[i].getAttribute("id")!=user_id)
        {            
            targetelements[i].style.backgroundColor="";  
            targetelements[i].style.borderColor="";  
            targetelements[i].style.color="";  
            targetelements[i].style.fontWeight="";
        }
    }
}


-->
</script>
</head>

<body  topmargin="5">
    <table style="line-height: 150%;font-size:9pt;color:#001A41" bordercolorlight="#CCCCCC" bordercolordark="#FFFFFF"
                border="1" cellpadding="0" cellspacing="0" width="100%" align="center">
                <tbody>
<?php
$cont_i = 0;
$t = " ";
do
{
    if ( $id == "" )
        break;
    $Temp_strtok = "";
    $Temp_user = "";
    $Temp_strtok = $newuu;
    if ( $Temp_strtok != "" )
    {
        echo "                    <tr bgcolor=\"#FED547\">                   \r\n                        <td nowrap=\"nowrap\" class=\"table_font\" align=\"center\" colspan=\"2\">";
        echo $fsql->f( "GROUP_NAME" );
        echo "</td>\r\n                                          \r\n                    </tr>\r\n\t\t\t\t\t\r\n\r\n";
        $Temp_user = strtok( $Temp_strtok, "," );
        do
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
        } while( $Temp_user );
    }
} while( 0 );
echo "                \r\n                </tbody>\r\n            </table>\r\n</body>\r\n</html>\r\n";
?>
