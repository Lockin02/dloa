<?php
include( "../../includes/db.inc.php" );
include( "../../includes/config.php" );
include( "../../includes/msql.php" );
$Urldir = "gwqc";
if ( $SID == 1 )
{
    $Urldir = "gwjs";
}
?>
<html>
<head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=gb2312">
    <link rel="stylesheet" type="text/css" href="../../inc/style.css">
    <SCRIPT><!--
    var menu_enter="";
    function borderize_on(e){
     color="#708DDF"; 
     source3=event.srcElement 
     if(source3.className=="menulines" && source3!=menu_enter)    
     source3.style.backgroundColor=color;
    }
    function borderize_on1(e){ 
        for (i=0; i<document.all.length; i++) { 
            document.all(i).style.borderColor="";   
            document.all(i).style.backgroundColor="";   
            document.all(i).style.color="";   
            document.all(i).style.fontWeight=""; 
        } 
        color="#003FBF"; 
        source3=event.srcElement 
        if(source3.className=="menulines") { 
            source3.style.borderColor="black";   
            source3.style.backgroundColor=color;   
            source3.style.color="white";   
            source3.style.fontWeight="bold"; 
        } 
        menu_enter=source3;
    }
    function borderize_off(e){ 
        source4=event.srcElement 
        if(source4.className=="menulines" && source4!=menu_enter){
            source4.style.backgroundColor="";     
            source4.style.borderColor="";    
        }
    }
    //--></SCRIPT>
    <script Language="JavaScript">
    var parent_window = parent.dialogArguments;
    function add_user(user_id,user_name,partename){
        /*\t  
        TO_VAL=parent_window.form1.Flow_ID.value;  
        if(TO_VAL.indexOf(user_id)<0 && TO_VAL.indexOf(user_id)!=0)  {    
        parent_window.form1.Flow_ID.value=user_id;     
        parent_window.form1.TO_NAME1.value=user_name;    
        parent_window.form1.TO_NAME2.value=partename+'-->'+user_name;  
        }  
        */
        parent_window.document.location.href="../../general/workflow2/<?php echo $Urldir;?>/index.php?flowid="+user_id;
        window.close();
    }
    </script>
</head>
<body class="bodycolor" topmargin="5">
    <table border="1" cellspacing="0" width="100%" class="small" cellpadding="3"  bordercolorlight="#000000" bordercolordark="#FFFFFF"  onMouseover="borderize_on(event)" onMouseout="borderize_off(event)" onclick="borderize_on1(event)">
        <?php
$msql->query( "select * from flow_type  where ClassID='".$CLASSID."' order by FLOW_ID" );
while ( $msql->next_record( ) )
{
    $USER_NAME = $msql->f( "FLOW_NAME" );
    $USERID = $msql->f( "FLOW_ID" );
    echo "<tr class=\"TableControl\">\r\n  <td class=\"menulines\" align=\"center\" onclick=\"add_user('";
    echo $USERID;
    echo "','";
    echo $USER_NAME;
    echo "','";
    echo $CLASS_NM;
    echo "');\"  style=\"cursor:hand\">";
    echo $USER_NAME;
    echo "</td>\r\n</tr>\r\n";
}
echo "<thead class=\"TableControl\">\r\n  <th bgcolor=\"#d6e7ef\" colspan=\"2\"><b>选择模块</b></th>\r\n</thead>\r\n</table>\r\n\r\n\r\n\r\n\r\n</body>\r\n</html>\r\n";
?>
