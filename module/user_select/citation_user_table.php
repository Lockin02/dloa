<?php
include( "../../includes/db.inc.php" );
include( "../../includes/config.php" );
include( "../../includes/msql.php" );
include( "../../includes/fsql.php" );
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
    if(TO_VAL.indexOf(user_id+",")==0)
    {
       parent_window.<?php echo $formname;?>.<?php echo $toid;?>.value=parent_window.<?php echo $formname;?>.<?php echo $toid;?>.value.replace(user_id+",","");
       parent_window.<?php echo $formname;?>.<?php echo $toname;?>.value=parent_window.<?php echo $formname;?>.<?php echo $toname;?>.value.replace(user_name+",","");
       borderize_off(targetelement);
    }else if(TO_VAL.indexOf(","+user_id+",")>0)
    {
       parent_window.<?php echo $formname;?>.<?php echo $toid;?>.value=parent_window.<?php echo $formname;?>.<?php echo $toid;?>.value.replace(","+user_id+",",",");
       parent_window.<?php echo $formname;?>.<?php echo $toname;?>.value=parent_window.<?php echo $formname;?>.<?php echo $toname;?>.value.replace(","+user_name+",",",");
       borderize_off(targetelement);
    }else
    {
        parent_window.<?php echo $formname;?>.<?php echo $toid;?>.value+=user_id+",";
        parent_window.<?php echo $formname;?>.<?php echo $toname;?>.value+=user_name+",";
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
  TO_VAL=parent_window.<?php echo $formname;?>.<?php echo $toid;?>.value;

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
  TO_VAL=parent_window.<?php echo $formname;?>.<?php echo $toid;?>.value;
  for (step_i=0; step_i<document.all.length; step_i++)
  {
    if(document.all(step_i).className=="menulines")
    {
       user_id=document.all(step_i).id;
       user_name=document.all(step_i).name;

       if(TO_VAL.indexOf(","+user_id+",")<0 && TO_VAL.indexOf(user_id+",")!=0)
       {
         parent_window.<?php echo $formname;?>.<?php echo $toid;?>.value+=user_id+",";
         parent_window.<?php echo $formname;?>.<?php echo $toname;?>.value+=user_name+",";
         borderize_on(document.all(step_i));
       }
    }
  }
}

function add_cader()
{
  var TO_VAL=parent_window.<?php echo $formname;?>.<?php echo $toid;?>.value;
  var T_count=parseInt(HDNcount_i.value);
  var i=0;
  for (var step_i=0; step_i<document.all.length; step_i++)
  {
      if(i>=T_count) return;
    if(document.all(step_i).className=="menulines")
    {
        i++;
       user_id=document.all(step_i).id;
       user_name=document.all(step_i).name;

       if(TO_VAL.indexOf(","+user_id+",")<0 && TO_VAL.indexOf(user_id+",")!=0)
       {
         parent_window.<?php echo $formname;?>.<?php echo $toid;?>.value+=user_id+",";
         parent_window.<?php echo $formname;?>.<?php echo $toname;?>.value+=user_name+",";
         borderize_on(document.all(step_i));
       }
    }
  }

}

function del_all()
{
    parent_window.<?php echo $formname;?>.<?php echo $toid;?>.value="";
    parent_window.<?php echo $formname;?>.<?php echo $toname;?>.value="";
    for (step_i=0; step_i<document.all.length; step_i++)
    {
        if(document.all(step_i).className=="menulines")
        {
            borderize_off(document.all(step_i));
        }
    }
}
-->
</script>
</head>

<body  topmargin="5"  onload="begin_set()">
    <table style="line-height: 150%;font-size:9pt;color:#001A41" bordercolorlight="#CCCCCC" bordercolordark="#FFFFFF" border="1" cellpadding="0" cellspacing="0" width="100%" align="center">
        <tbody>
<?php
    $msql->query( "select DEPT_NAME from department where Depart_x='".$id."' " );
    $msql->next_record( );
?>
            <tr bgcolor="#FED547">                      
                <td nowrap="nowrap" class="table_font" align="center" >
<?php echo $msql->f( "DEPT_NAME" );?>
                </td>
            </tr>
<?php
    $cont_i = 0;
    $t = " ";
    if ( $id != "" )
    {
        $sql = "select u.USER_NAME,u.USER_ID from user u,department d where u.HAS_LEFT='0' and u.DEL='0' and u.DEPT_ID=d.DEPT_ID and d.Depart_x like '".$id."%' and LENGTH(d.Depart_x)>".strlen( $id )." order by u.USER_NAME";//order by d.Depart_x ";
        $fsql->query( $sql );
        if ( 0 < $fsql->num_rows( ) )
        {
?>
           <tr class="TableContent">
            <td onclick="javascript:add_all();" style="cursor:hand" align="center" title="包括子部门的人员">全部添加</td>
           </tr>
<?php
        }
    }    
?>           
        <tr class="TableContent">
            <td onclick="javascript:add_cader();" style="cursor:hand" align="center" title="添加本部门的人员">添加本部</td>
        </tr>
        <tr class="TableContent">
          <td onclick="javascript:del_all();" style="cursor:hand" align="center" >全部删除</td>
        </tr>
<?php
if ( $id == "" )
{
    $sql = "select USER_NAME,USER_ID from user where  HAS_LEFT='0' and DEL='0' order by USER_NAME ASC";
}
else
{
    $sql = "select u.USER_NAME,u.USER_ID from user u,department d where  u.HAS_LEFT='0' and u.DEL='0' and u.DEPT_ID=d.DEPT_ID and d.Depart_x='".$id."' order by u.USER_NAME";//d.Depart_x ";
}
$msql->query( $sql );
while ( $msql->next_record( ) ){
    ++$cont_i;
?>
        <!--************** 在此传值给左边的详细信息barg_customer_part.php(ID号,客户编号)*******-->
        <tr id="<?php echo $msql->f( "USER_ID" );?>" name="<?php  echo $msql->f( "USER_NAME" );?>" onclick="javascript:click_user('<?php echo $msql->f( "USER_ID" );?>')" class="menulines" style="cursor:hand" align="center">
            <td align="center" nowrap="nowrap">
                <?php echo $msql->f( "USER_NAME" );?>&nbsp;
            </td>
        </tr>
<?php    
}
if($cont_i==0)
{
?>
        <tr class="menulines">
            <td style="cursor:hand" align="center"><b>此部门暂没有成员</b></td>
        </tr>
<?php
}
while ( 0 < $fsql->num_rows( ) && $fsql->next_record( ) )
{
?>
        <tr id="<?php echo $fsql->f( "USER_ID" );?>" name="<?php echo $fsql->f( "USER_NAME" );?>"  onclick="javascript:click_user('<?php echo $fsql->f( "USER_ID" );?>')" class="menulines" style="cursor:hand;display:none " align="center">
            <td align="center" nowrap="nowrap">
<?php echo $fsql->f( "USER_NAME" );?>&nbsp;
            </td>
        </tr>
<?php
}
?>
    <input name="HDNcount_i" type="hidden" value="<?php echo $cont_i;?>">
    </tbody>
</table>
</body>
</html>
