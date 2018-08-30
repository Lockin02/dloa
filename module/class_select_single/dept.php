<?php
include( "../../includes/db.inc.php" );
include( "../../includes/config.php" );
include( "../../includes/msql.php" );
?>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<link rel="stylesheet" type="text/css" href="../../inc/style.css">
<script>
<!--

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
 for (i=0; i<document.all.length; i++)
 { document.all(i).style.borderColor="";
   document.all(i).style.backgroundColor="";
   document.all(i).style.color="";
   document.all(i).style.fontWeight="";
 }

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

function borderize_off(e)
{
 source4=event.srcElement;

 if(source4.className=="menulines" && source4!=menu_enter)
    {
        source4.style.backgroundColor="";
        source4.style.borderColor="";
    }
}

//-->
</script>
</head>

<body class="bodycolor" onMouseover="borderize_on(event)" onMouseout="borderize_off(event)" topmargin="5">
<!--<body class="bodycolor" onMouseover="borderize_on(event)" onMouseout="borderize_off(event)" onclick="borderize_on1(event)" topmargin="5">-->
    <table border="1" cellspacing="0" width="95%" class="small" cellpadding="3"  bordercolorlight="#000000" bordercolordark="#FFFFFF" align="center">
<?php
$msql->query( "select * from wf_class  where 1 order by class_id" );
while ( $msql->next_record( ) )
{
    $ID = $msql->f( "class_id" );
    $DEPT_NAME = $msql->f( "name" );
    echo "<tr class=\"TableControl\">\r\n  <td class=\"menulines\" align=\"center\" onclick=\"javascript:parent.user.location='user.php?CLASSID=";
    echo $ID;
    echo "&CLASS_NM=";
    echo $DEPT_NAME;
    echo "&toid=";
    echo $toid;
    echo "&toname=";
    echo isset($toname)?$toname:"";
    echo "&SID=";
    echo isset($SID)?$SID:"";
    echo "';\" style=\"cursor:hand\">";
    echo $DEPT_NAME;
    echo "</td>\r\n</tr>\r\n";
}
?>
<thead class="TableControl">
  <th bgcolor="#d6e7ef" align="center"><b>选择类别</b></th>
</thead>
</table>
</body>
</html>

