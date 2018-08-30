<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<link rel="stylesheet" type="text/css" href="../../inc/style.css">
<script src="../../general/daily/yjgl/select.js" type="text/javascript"></script>
</head>
<?php 
include( "../../includes/db.inc.php" );
include( "../../includes/config.php" );
include( "../../includes/msql.php" );
?>
<script Language="JavaScript">
var parent_window = parent.dialogArguments;

function click_user(user_id)
{
    TO_VAL=parent_window.form1.<?php echo $toid; ?>.value;
    targetelement=document.all(user_id);
    user_name=targetelement.getAttribute('name');
    if(TO_VAL.indexOf(user_id+",")==0)
    {
       parent_window.form1.<?php echo $toid; ?>.value=parent_window.form1.<?php echo $toid; ?>.value.replace(user_id+",","");
       parent_window.form1.<?php echo $toname; ?>.value=parent_window.form1.<?php echo $toname; ?>.value.replace(user_name+",","");
       borderize_off(targetelement);
    }else if(TO_VAL.indexOf(","+user_id+",")>0)
    {
       parent_window.form1.<?php echo $toid; ?>.value=parent_window.form1.<?php echo $toid; ?>.value.replace(","+user_id+",",",");
       parent_window.form1.<?php echo $toname; ?>.value=parent_window.form1.<?php echo $toname; ?>.value.replace(","+user_name+",",",");
       borderize_off(targetelement);
    }else
    {
        parent_window.form1.<?php echo $toid; ?>.value+=user_id+",";
        parent_window.form1.<?php echo $toname; ?>.value+=user_name+",";
        borderize_on(targetelement);
    }
}

function borderize_on(targetelement)
{
 color="#003FBF";
 targetelement.style.borderColor="black";
 targetelement.style.backgroundColor=color;
 targetelement.style.color="white";
 targetelement.style.fontWeight="bold";
}

function borderize_off(targetelement)
{
  targetelement.style.backgroundColor="";
  targetelement.style.borderColor="";
  targetelement.style.color="";
  targetelement.style.fontWeight="";
}

function begin_set()
{
  TO_VAL=parent_window.form1.<?php echo $toid; ?>.value;

  for (step_i=0; step_i<document.all.length; step_i++)
  {
    if(document.all(step_i).className=="menulines")
    {
       user_id=document.all(step_i).id;
       if(TO_VAL.indexOf(","+user_id+",")>0 || TO_VAL.indexOf(user_id+",")==0)
          borderize_on(document.all(step_i));
    }
  }
}

function add_all()
{
  TO_VAL=parent_window.form1.<?php echo $toid; ?>.value;
  for (step_i=0; step_i<document.all.length; step_i++)
  {
    if(document.all(step_i).className=="menulines")
    {
       user_id=document.all(step_i).id;
       user_name=document.all(step_i).name;

       if(TO_VAL.indexOf(","+user_id+",")<0 && TO_VAL.indexOf(user_id+",")!=0)
       {
         parent_window.form1.<?php echo $toid; ?>.value+=user_id+",";
         parent_window.form1.<?php echo $toname; ?>.value+=user_name+",";
         borderize_on(document.all(step_i));
       }
    }
  }
}

function del_all()
{
    parent_window.form1.<?php echo $toid; ?>.value="";
    parent_window.form1.<?php echo $toname; ?>.value="";
    for (step_i=0; step_i<document.all.length; step_i++)
    {
        if(document.all(step_i).className=="menulines")
        {
            borderize_off(document.all(step_i));
        }
    }
}
function check_USER_NAME()
{
    if(form1.USER_NAME.value=="")
    {
        alert("请输入姓名！");
        form1.USER_NAME.focus();
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
  <label class="small">姓名:</label><input type="text" name="USER_NAME" id="USER_NAME" size="10" class="SmallInput" value="">&nbsp;<input type="button" name="Submit" onClick="check_USER_NAME();" value="查询" class="SmallButton">
  <input type="hidden" name="ID" value="">
 </form>
</center>
<hr/>
<?php 
if (!array_key_exists("USER_NAME",$_POST) || $_POST["USER_NAME"] == "" )
    return;
?>
<table border="1" cellspacing="0" width="100%" class="small" cellpadding="3"  bordercolorlight="#000000" bordercolordark="#FFFFFF">
    <tr class="TableContent">
      <td onclick="javascript:add_all();" style="cursor:hand" align="center" colspan="2">全部添加</td>
    </tr>
    <tr class="TableContent">
      <td onclick="javascript:del_all();" style="cursor:hand" align="center" colspan="2">全部删除</td>
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
if($todept&&$todept!=''){
    $tempsql=" and d.dept_id in (".$todept.")";
}else{
    $tempsql='';
}
$sql = sprintf( "select u.USER_ID,u.USER_NAME,d.DEPT_NAME from user u,department d where u.DEL='0' $tempsql and u.HAS_LEFT='0' and u.DEPT_ID=d.DEPT_ID and u.USER_NAME regexp '%s' order by d.DEPT_NO,u.USER_NAME ASC",mysql_real_escape_string($_POST["USER_NAME"]));
$msql->query( $sql );
while ( $msql->next_record( ) )
{
    if($msql->f( "DEPT_NAME" )=="系统管理"&&$checkDept!=1){
        continue;
    }
    $userIdStr=$msql->f( "USER_ID" );
    $userNameStr=$msql->f( "USER_NAME" );
    $deptNameStr=$msql->f( "DEPT_NAME" );
?>
<!--************** 在此传值给左边的详细信息barg_customer_part.php(ID号,客户编号)*******-->
    <tr id="<?php echo $userIdStr;?>" name="<?php echo $userNameStr;?>"  onclick="javascript:click_user('<?php echo $userIdStr;?>')" class="menulines" style="cursor:hand" align="center">
         <td align="center" nowrap="nowrap">
            <?php echo $deptNameStr;?>                           
        </td>
        <td align="center" nowrap="nowrap">
            <?php echo $userNameStr;?>&nbsp;                           
        </td>
    </tr>
<?php
}
?>
</table>
</body>
</html>