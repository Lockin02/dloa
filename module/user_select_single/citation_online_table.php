<?php
include( "../../includes/db.inc.php" );
include( "../../includes/config.php" );
include( "../../includes/msql.php" );
//$msql->query( "select * from user_count  where login_time like '".date( "Y-m-d" )."%'" );
$msql->query( "select * from login_log  where ON_LINE='1'" );
$timeout = 120;
$temp = "";
while ( $msql->next_record( ) )
{
 $temp .= trim( $msql->f( "USER_ID" ) ).",";
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
  }
  else
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
<body  topmargin="5" >
    <table style="line-height: 150%;font-size:9pt;color:#001A41" bordercolorlight="#CCCCCC" bordercolordark="#FFFFFF"
                border="1" cellpadding="0" cellspacing="0" width="100%" align="center">
                <tbody>
                    <tr bgcolor="#FED547">                     
                        <td nowrap="nowrap" class="table_font" align="center" colspan="2">选择在线人员</td>
                                          
                    </tr>
<?php
//读取登陆用户的信息
$sqlStr="select  USER_PRIV,DEPT_ID ,area from user where  USER_ID='".$USER_ID."'";
$msql->query( $sqlStr );
while ( $msql->next_record( ) ){
    $checkDept=$msql->f("DEPT_ID");
    $checkPriv=$msql->f("USER_PRIV");
    $checkArea=$msql->f("area");
}
$uid = "";
$cont_i = 0;
$uid = strtok( $temp, "," );
$table_name = " user u,department d";
while( $uid )
{
    $sql = "select u.USER_NAME,u.USER_ID,d.DEPT_NAME from ".$table_name." where u.DEL='0' and u.HAS_LEFT='0' and u.DEPT_ID=d.DEPT_ID and u.USER_ID='".$uid."'";
    $msql->query( $sql );
    while ( $msql->next_record( ) )
    {
        if($msql->f( "DEPT_NAME" )=="系统管理"&&$checkDept!=1){
            continue;
        }
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
} ;
echo "                \r\n                </tbody>\r\n            </table>\r\n\t\t\t\r\n</body>\r\n</html>\r\n";
?>
