<html>
<head>
<title>添加角色</title>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<link rel="stylesheet" type="text/css" href="../../inc/style.css">
<style>
.menulines{}
</style>
<?php 
include( "../../includes/db.inc.php" );
include( "../../includes/config.php" );
include( "../../includes/msql.php" );
?>
<SCRIPT>
<!--

var menu_enter="";

function borderize_on(e)
{
 color="#708DDF";
 source3=event.srcElement

 if(source3.className=="menulines" && source3!=menu_enter)
    source3.style.backgroundColor=color;
}

function borderize_on1(e)
{
 for (i=0; i<document.all.length; i++)
 { document.all(i).style.borderColor="";
   document.all(i).style.backgroundColor="";
   document.all(i).style.color="";
   document.all(i).style.fontWeight="";
 }

 color="#003FBF";
 source3=event.srcElement

 if(source3.className=="menulines")
 { source3.style.borderColor="black";
   source3.style.backgroundColor=color;
   source3.style.color="white";
   source3.style.fontWeight="bold";
 }

 menu_enter=source3;
}

function borderize_off(e)
{
 source4=event.srcElement

 if(source4.className=="menulines" && source4!=menu_enter)
    {source4.style.backgroundColor="";
     source4.style.borderColor="";
    }
}

//-->
</SCRIPT>
<script Language=JavaScript>
var parent_window = parent.dialogArguments;
function add_dept(dept_id,dept_name){
    TO_VAL=parent_window.form1.<?php echo $toid;?>.value;
    if(TO_VAL.indexOf(','+dept_id+',')<0 && TO_VAL.indexOf(dept_id+',')!=0 && (parent_window.form1.<?php echo $toid;?>.value!='ALL_PRIV'))
  {
      parent_window.form1.<?php echo $toid;?>.value+=dept_id+',';
      parent_window.form1.<?php echo $toname;?>.value+=dept_name+',';
  }
}
</script>
</head>

<body class="bodycolor" onMouseover="borderize_on(event)" onMouseout="borderize_off(event)" onclick="borderize_on1(event)" topmargin="5">

<table border="1" cellspacing="0" width="100%" class="small" cellpadding="3"  bordercolorlight="#000000" bordercolordark="#FFFFFF"  onMouseover="borderize_on(event)" onMouseout="borderize_off(event)" onclick="borderize_on1(event)">
<tr class="TableContent">
  <td class="menulines" onclick="javascript:add_alls();" style="cursor:hand" align="center">以下全部添加</td>
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
$msql->query( "select * from user_priv  where 1 order by USER_PRIV" );
$add_all_str="";
while ( $msql->next_record( ) )
{
    if($msql->f( "USER_PRIV" )=="1"&&$checkPriv!=1){
        continue;
    }
    $DEPT_NAME = $msql->f( "PRIV_NAME" );
    $DEPTID = $msql->f( "USER_PRIV" );   
    ?>
    <tr class="TableControl">
        <td class="menulines" align="center" onclick="javascript:add_dept('<?php echo $DEPTID;?>','<?php echo $DEPT_NAME;?>');"  style="cursor:hand"><?php echo $DEPT_NAME;?>
        </td>
    </tr>
    <?php
    $add_all_str .= "add_dept('".$DEPTID."','$DEPT_NAME');\n ";
}
?>
<thead class="TableControl">
  <th bgcolor="#d6e7ef" colspan="2"><b>选择角色</b></th>
</thead>
</table>
<script Language="JavaScript">
function add_alls()
{
<?php echo $add_all_str;?>
 parent_window.form1.<?php echo $toid; ?>.value="ALL_PRIV";
parent.close();   

}
</script>
<script Language="JavaScript">
function add_all()
{
  if(parent_window.form1.<?php echo $toid; ?>.value!="ALL_PRIV")
  {
      }
  parent_window.form1.<?php echo $toid; ?>.value="ALL_PRIV";
  parent.close();  
}
</script>
</body>
</html>