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
<?php 
include( "../../includes/db.inc.php" );
include( "../../includes/config.php" );
include( "../../includes/msql.php" );
//验证赋值
if(!isset($JeoaDb_tableprefix)){
    $JeoaDb_tableprefix="";
}
?>
</head>

<body class="bodycolor" onMouseover="borderize_on(event)" onMouseout="borderize_off(event)" onclick="borderize_on1(event)" topmargin="5">
    <table border="1" cellspacing="0" width="95%" class="small" cellpadding="3"  bordercolorlight="#000000" bordercolordark="#FFFFFF" align="center">
<?php 
    $msql->query( "select * from department  WHERE 1=1 order by DEPT_ID " );
while ( $msql->next_record( ) )
{
    $ID = $msql->f( "DEPT_ID" );
    $DEPT_NAME = $msql->f( "DEPT_NAME" );
    $DEPT_NO = $msql->f( "DEPT_ID" );
    ?>
    <tr class="TableControl">
         <td class="menulines" align="center" onclick="javascript:parent.user.location='user.php?DEPT_ID=<?php echo $ID;?>&DEPT_NM=<?php echo $DEPT_NAME;?>&toid=<?php echo $toid;?>&toname=<?php echo $toname;?>'"  style="cursor:hand">
         <?php echo $DEPT_NAME;?>
         </td>
    </tr>
    <?php
}
?>
<thead class="TableControl">
  <th bgcolor="#d6e7ef" align="center"><b>选择部门</b></th>
</thead>
</table>
</body>
</html>