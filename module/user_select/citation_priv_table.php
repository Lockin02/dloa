<html>
<head>
<link rel="stylesheet" type="text/css" href="../../inc/style.css">
<?php 
include( "../../includes/db.inc.php" );
include( "../../includes/config.php" );
include( "../../includes/msql.php" );
?>
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
function click_user111(user_id)
{
  TO_VAL=parent_window.<?php echo $formname;?>.TO_ID.value;
  targetelement=document.all(user_id);
  user_name=targetelement.name;
 if(TO_VAL.indexOf(user_id)<0 && TO_VAL.indexOf(user_id)!=0) 
  {
    
    parent_window.<?php echo $formname;?>.<?php echo $toid;?>.value=user_id;
    parent_window.<?php echo $formname;?>.<?php echo $toname;?>.value=user_name;
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

<body  topmargin="5" >
    <table style="line-height: 150%;font-size:9pt;color:#001A41" bordercolorlight="#CCCCCC" bordercolordark="#FFFFFF"
                border="1" cellpadding="0" cellspacing="0" width="100%" align="center">
                <tbody>
                                <tr bgcolor="#FED547">                     
                        <td nowrap="nowrap" class="table_font" align="center" colspan="2">
<?php 
$msql->query( "select PRIV_NAME from user_priv where USER_PRIV='".$id."' " );
$msql->next_record( );
echo $msql->f( "PRIV_NAME" );
?>
                        </td>                                        
                    </tr>
                  <tr class="TableContent">
  <td onclick="javascript:add_cader();" style="cursor:hand" align="center"  colspan="2" title="添加所有角色的人员">添加所有</td>
</tr>
<tr class="TableContent">
  <td onclick="javascript:del_all();" style="cursor:hand" align="center"  colspan="2"  >全部删除</td>
</tr>                    
<?php 
$table_name = " user u,department d,user_priv p";
$cont_i = 0;
$t = " ";
if ( $id == "" )
{
    $sql = "select '错误' as DEPT_NAME,'参数有误' as USER_NAME";
}
else
{
    $sql = "select u.USER_NAME,u.USER_ID,d.DEPT_NAME,p.PRIV_NAME from ".$table_name.( " where  u.HAS_LEFT='0' and u.DEL='0' and u.DEPT_ID=d.DEPT_ID and u.USER_PRIV=p.USER_PRIV and p.USER_PRIV='".$id."' order by d.DEPT_NO,u.USER_NAME ASC" );
}
$msql->query( $sql );
while ( $msql->next_record( ) )
{
    ++$cont_i;
?>
        <tr id="<?php echo $msql->f( "USER_ID" );?>" name="<?php echo $msql->f( "USER_NAME" );?>"  onclick="javascript:click_user('<?php echo $msql->f( "USER_ID" );?>')" class="menulines" style="cursor:hand" align="center">
                  
                         <td align="center" nowrap="nowrap">
                            <?php echo $msql->f( "DEPT_NAME" );?>                           
                        </td>
                        <td align="center" nowrap="nowrap">
                            <?php echo $msql->f( "USER_NAME" );?>&nbsp;                           
                        </td>
                    </tr>
<?php
}

if($cont_i==0){?>
    <tr class="menulines">
    <td style="cursor:hand" align="center" colspan="2"><b>此角色暂没有成员</b></td></tr>
<?php }
?> 
<input name="HDNcount_i" type="hidden" value=6>                
                </tbody>
            </table>
</body>
</html> 