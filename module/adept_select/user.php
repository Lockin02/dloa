<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<link rel="stylesheet" type="text/css" href="../../inc/style.css">
<style>
.menulines{}
</style>

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
function add_user(user_id,user_name) 
{
    TO_VAL=parent_window.form1.<?php echo $toid;?> .value;
    if(TO_VAL.indexOf(','+user_id+',')<0 && TO_VAL.indexOf(user_id+',')!=0&& TO_VAL!="ALL_DEPT" ) {
        parent_window.form1.<?php echo $toid;?> .value+=user_id+',';
        parent_window.form1.<?php echo $toname;?>.value+=user_name+',';
    }
}
</script>
</head>
<?php 
include( "../../includes/db.inc.php" );
include( "../../includes/config.php" );
include( "../../includes/msql.php" );
//读取登陆用户的信息
$sqlStr="select  DEPT_ID  from user where  USER_ID='".$USER_ID."'";
$msql->query( $sqlStr );
while ( $msql->next_record( ) ){
    $checkDept=$msql->f("DEPT_ID");
}
?>
<body class="bodycolor" topmargin="5">


<table border="1" cellspacing="0" width="100%" class="small" cellpadding="3"  bordercolorlight="#000000" bordercolordark="#FFFFFF"  onMouseover="borderize_on(event)" onMouseout="borderize_off(event)" onclick="borderize_on1(event)">
<tr class='TableContent'>
  <td class='menulines' onclick="javascript:add_all_member();" style="cursor:hand" align='center'>全体部门(群发)</td>
</tr><tr class='TableContent'>
  <td class='menulines' onclick="javascript:add_all();" style="cursor:hand" align='center'>以下全部添加</td>
</tr>
<?php 
$msql->query( "select * from department where 1=1 order by Depart_x" );
$add_all_member="";
while ( $msql->next_record( ) )
{
    if($msql->f( "DEPT_NAME" )=="系统管理"&&$checkDept!=1){
        continue;
    }
    $AUSER_NAME = $msql->f( "DEPT_NAME" );
    $AUSERID = $msql->f( "DEPT_ID" );
    $add_all_member .= "add_user('".$AUSERID."','$AUSER_NAME');\n ";
}
$msql->query( "select * from department where Depart_x like '".$id."%' order by DEPT_ID" );
$add_all_str="";
while ( $msql->next_record( ) )
{
    $USER_NAME = $msql->f( "DEPT_NAME" );
    $USERID = $msql->f( "DEPT_ID" ); 
    ?>
    <tr class="TableControl">
         <td class="menulines" align="center" onclick="javascript:add_user('<?php echo $USERID;?>','<?php echo $USER_NAME;?>');"  style="cursor:hand"><?php echo $USER_NAME; ?></td></tr>
    <?php
    $add_all_str .= "add_user('".$USERID."','$USER_NAME');\n ";
}
?>


<script Language="JavaScript">
function add_all_member()
{
   <?php echo $add_all_member;?>
   parent_window.form1.<?php echo $toid; ?>.value="ALL_DEPT";
   parent.close();
}

function add_all()
{
 <?php echo $add_all_str;?>  
}
</script>
</body>
</html>