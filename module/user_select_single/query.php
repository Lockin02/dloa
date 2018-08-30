<?php
include( "../../includes/db.inc.php" );
include( "../../includes/config.php" );
include( "../../includes/msql.php" );
?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=gb2312">
    <link rel="stylesheet" type="text/css" href="../../inc/style.css">
    <script src="../../general/daily/yjgl/select.js" type="text/javascript"></script>
</head>
<script Language="JavaScript">
var parent_window = parent.dialogArguments;
function click_user(user_id,user_name)
{
  TO_VAL=parent_window.<?php echo $formname;?>.<?php echo $toid;?>.value;
  targetelement=document.all(user_id);
  //user_name=targetelement.name;
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

function check_USER_NAME()
{
    if(form1.USER_NAME.value=="")
    {
        return false;
    }
    else
    {
        form1.submit();
    }
}
</script>

<body class="panel" topmargin="5" leftmargin="2" onload="document.form1.USER_NAME.focus();">
<center>
<form action="" name="form1" method="post">
    <label class="small">姓名:</label>
    <input type="text" name="USER_NAME" size="6" class="SmallInput" value=""/>
    <input type="button" name="Submit" onClick="check_USER_NAME();" value="查询" class="BigButton"/>
    <input type="hidden" name="ID" value=""/>
</form>
</center>
<hr/>
<?php
if (!array_key_exists("USER_NAME",$_POST) || $_POST["USER_NAME"] == "" )
    return;
?>
<table border="1" cellspacing="0" width="100%" class="small" cellpadding="3"  bordercolorlight="#000000" bordercolordark="#FFFFFF">
<?php
//读取登陆用户的信息
$sqlStr="select  USER_PRIV,DEPT_ID ,area from user where  USER_ID='".$USER_ID."'";
$msql->query( $sqlStr );
while ( $msql->next_record( ) ){
    $checkDept=$msql->f("DEPT_ID");
    $checkPriv=$msql->f("USER_PRIV");
    $checkArea=$msql->f("area");
}
if(isset($todept)&&trim($todept)){
    $sql = sprintf( "select u.USER_ID,u.USER_NAME,d.DEPT_NAME from user u,department d where u.DEL='0' and u.HAS_LEFT='0' and u.DEPT_ID=d.DEPT_ID and u.USER_NAME regexp '%s' and d.dept_id in ($todept) order by d.DEPT_NO,u.USER_NAME ASC",mysql_real_escape_string($_POST["USER_NAME"]));
}else{
    $sql = sprintf( "select u.USER_ID,u.USER_NAME,d.DEPT_NAME from user u,department d where u.DEL='0' and u.HAS_LEFT='0' and u.DEPT_ID=d.DEPT_ID and u.USER_NAME regexp '%s' order by d.DEPT_NO,u.USER_NAME ASC",mysql_real_escape_string($_POST["USER_NAME"]));
}

$msql->query( $sql );
$cont_i=0;
while ( $msql->next_record( ) )
{
    if($msql->f( "DEPT_NAME" )=="系统管理"&&$checkDept!=1){
        continue;
    }
    ++$cont_i;
    echo "\t\t\t\t\t\t\r\n\t\t<!--************** 在此传值给左边的详细信息barg_customer_part.php(ID号,客户编号)*******-->\r\n                <tr id=\"";
    echo $msql->f( "USER_ID" );
    echo "\" name=\"";
    echo $msql->f( "USER_NAME" );
    echo "\"  onclick=\"javascript:click_user('";
    echo $msql->f( "USER_ID" );
    echo "','";
    echo $msql->f( "USER_NAME" );
    echo "')\" class=\"menulines\" style=\"cursor:hand\" align=\"center\">\r\n         \t\t\t\t<td align=\"center\" nowrap=\"nowrap\">\r\n\t\t\t\t\t\t\t";
    echo $msql->f( "DEPT_NAME" );
    echo "                           \r\n                        </td>\r\n                        <td align=\"center\" nowrap=\"nowrap\">\r\n\t\t\t\t\t\t\t";
    echo $msql->f( "USER_NAME" )."&nbsp;";
    echo "                           \r\n                        </td>\r\n                    </tr>\r\n";
}
?>
</table>
</body>
</html>