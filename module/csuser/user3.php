<?php
include( "../../includes/db.inc.php" );
include( "../../includes/config.php" );
include( "../../includes/msql.php" );
include( "../../includes/fsql.php" );
?>
<html>
<head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=gb2312">
    <link rel="stylesheet" type="text/css" href="../../inc/style.css">
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
     { 
        source3.style.borderColor="black";
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
        {
            source4.style.backgroundColor="";
            source4.style.borderColor="";
        }
    }
    //-->
    </SCRIPT>
    <script Language="JavaScript">
    var parent_window = parent.dialogArguments;

    function add_user(user_id,user_name)
    {
      FH_VAL=parent_window.form1.FH_ID.value;
      
        parent_window.form1.FH_ID.value=user_id;
        parent_window.form1.FH_NAME.value=user_name;
     window.close();
    }
    </script>
</head>
<body class="bodycolor" topmargin="5">
<table border="1" cellspacing="0" width="100%" class="small" cellpadding="3"  bordercolorlight="#000000" bordercolordark="#FFFFFF"  onMouseover="borderize_on(event)" onMouseout="borderize_off(event)" onclick="borderize_on1(event)">
<?php
$msql->query( "select * from xmsb_liucheng  where flag='3' order by ID" );
while ( $msql->next_record( ) )
{
    $USERID = $msql->f( "name" );
    $query = "select *  from user  where USER_ID='".$USERID."'";
    $ToNameVote = $fsql->GetRow( $query );
    $dequery = "select *  from department  where DEPT_ID='".$ToNameVote['DEPT_ID']."'";
    $DEToNameVote = $fsql->GetRow( $dequery );
    $FH_USERS = $DEToNameVote['DEPT_NAME'].":".$ToNameVote['USER_NAME'];
    echo "<tr class=\"TableControl\">\r\n  <td class=\"menulines\" align=\"center\" onclick=\"javascript:add_user('";
    echo $USERID;
    echo "','";
    echo $FH_USERS;
    echo "');\"  style=\"cursor:hand\">";
    echo $FH_USERS;
    echo "</td>\r\n</tr>\r\n\r\n";
}
echo "<thead class=\"TableControl\">\r\n  <th bgcolor=\"#d6e7ef\" colspan=\"2\"><b>选择人员</b></th>\r\n</thead>\r\n</table>\r\n\r\n\r\n\r\n\r\n</body>\r\n</html>\r\n";
?>
