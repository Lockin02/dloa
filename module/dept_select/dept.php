<?php
include( "../../includes/db.inc.php" );
include( "../../includes/config.php" );
include( "../../includes/msql.php" );
?>
<html>
<head>
<title>添加部门</title>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<link rel="stylesheet" type="text/css" href="../../inc/style.css">
<style>
.menulines{}
</style>
<SCRIPT>
var menu_enter="";
function borderize_on(e)
{
 color="#708DDF";
 source3=event.srcElement;

 if(source3.className=="menulines" && source3!=menu_enter)
 source3.style.backgroundColor=color;
}

function borderize_on1(e)
{
 color="#003FBF";
 source3=event.srcElement;

 if(source3.className=="menulines")
 { source3.style.borderColor="black";
   source3.style.backgroundColor=color;
   source3.style.color="white";
   source3.style.fontWeight="bold";
 }
 menu_enter=source3;
}
function setColor()
{
    name=parent_window.<?php echo $formname;?>.<?php echo $toname;?>.value.toString();
     for (i=0; i<document.all.length; i++)
    {
         if(document.all(i).className=="menulines" && name.indexOf(document.all(i).innerHTML)!=-1)
         { 
            document.all(i).style.borderColor="black";
            document.all(i).style.backgroundColor="#003FBF";
            document.all(i).style.color="white";
            document.all(i).fontWeight="bold";
         }else{
            document.all(i).style.borderColor="";
            document.all(i).style.backgroundColor="";
            document.all(i).style.color="";
            document.all(i).fontWeight="";
         }
    }
}
window.onload = setColor;

</SCRIPT>
<script Language=JavaScript>
var parent_window = parent.dialogArguments;
function add_dept(dept_id,dept_name)
{
    TO_VAL=parent_window.<?php echo $formname;?>.<?php echo $toid;?>.value;
    if(TO_VAL.indexOf(','+dept_id+',')<0 && TO_VAL.indexOf(dept_id+',')!=0 && (parent_window.<?php echo $formname;?>.<?php echo $toid;?>.value!='  ALL_DEPT'))
    {
        parent_window.<?php echo $formname;?>.<?php echo $toid;?>.value+=dept_id+',';
        parent_window.<?php echo $formname;?>.<?php echo $toname;?>.value+=dept_name+',';
    }else if(TO_VAL.indexOf(dept_id+",")==0)
    {
       parent_window.<?php echo $formname;?>.<?php echo $toid;?>.value=parent_window.<?php echo $formname;?>.<?php echo $toid;?>.value.replace(dept_id+",","");
       parent_window.<?php echo $formname;?>.<?php echo $toname;?>.value=parent_window.<?php echo $formname;?>.<?php echo $toname;?>.value.replace(dept_name+",","");

    }else if(TO_VAL.indexOf(","+dept_id+",")>0)
    {
       parent_window.<?php echo $formname;?>.<?php echo $toid;?>.value=parent_window.<?php echo $formname;?>.<?php echo $toid;?>.value.replace(","+dept_id+",",",");
       parent_window.<?php echo $formname;?>.<?php echo $toname;?>.value=parent_window.<?php echo $formname;?>.<?php echo $toname;?>.value.replace(","+dept_name+",",",");
    }
}
</script>
</head>
<body class="bodycolor" onclick="borderize_on1(event)" topmargin="5">
<table border="1" cellspacing="0" width="100%" class="small" cellpadding="3"  bordercolorlight="#000000" bordercolordark="#FFFFFF" onclick="borderize_on1(event)">
<tr class="TableContent">
  <td class="menulines" onclick="javascript:add_alls();" style="cursor:hand" align="center">以下全部添加</td>
</tr>
<?php
$sqlStr="select  DEPT_ID  from user where  USER_ID='".$USER_ID."'";
$msql->query( $sqlStr );
while ( $msql->next_record( ) ){
    $checkDept=$msql->f("DEPT_ID");
}

$msql->query( "select * from department  where delflag='0' order by Depart_x" );
$add_all_str = "";
while ( $msql->next_record( ) )
{
    if($msql->f( "DEPT_NAME" )=="系统管理"&&$checkDept!=1){
        continue;
    }
    $tmp='&nbsp;';
    for($i=0;$i<$msql->f('Dflag');$i++){
        $tmp.='&nbsp;';
    }
    if(($msql->f('Dflag'))){
        $tmp.='├';
    }
    $DEPT_NAME = $tmp.$msql->f( "DEPT_NAME" );
    $DEPTID = $msql->f( "DEPT_ID" );
    ?>
   <tr class="TableControl">
   <td class="menulines" align="left" onclick="javascript:add_dept('<?php echo $DEPTID;?>','<?php echo $DEPT_NAME;?>')" style="cursor:hand"><?php echo $DEPT_NAME;?></td>
</tr>
<?php 
$add_all_str .= "add_dept('".$DEPTID."','$DEPT_NAME'); ";
}
?>
<thead class="TableControl">
<th bgcolor="#d6e7ef" colspan="2"><b>选择部门</b></th>
</thead>
</table>
<script Language="JavaScript">
function add_alls()
{
    <?php echo $add_all_str;?>
    parent_window.<?php echo $formname;?>.<?php echo $toid;?>.value="ALL_DEPT";
    parent.close();   
}
</script>
<script Language="JavaScript">
function add_all()
{ 
  if(parent_window.<?php echo $formname;?>.<?php echo $toid;?>.value!="ALL_DEPT")
    parent.close();   
}
</script>
</body>
</html>