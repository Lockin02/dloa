<?php
include( "../../includes/db.inc.php" );
include( "../../includes/config.php" );
include( "../../includes/msql.php" );
?>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="../../inc/style.css">
    <script src="../../general/daily/yjgl/select.js" type="text/javascript"></script>
    <script language="javascript"><!--
    var parent_window = parent.dialogArguments;
    function click_user(user_id,user_name)
    {  
        TO_VAL=parent_window.<?php echo $formname;?>.<?php echo $toid;?>.value;  
        targetelement=document.all(user_id);  
        //user_name=targetelement.name; 
        if(TO_VAL!=user_id)   
        {        
            parent_window.<?php echo $formname;?>.<?php echo $toid;?>.value=user_id;    
            parent_window.<?php echo $formname;?>.<?php echo $toname;?>.value=user_name;    
            borderize_on(targetelement);
            borderize_clear(user_id);  
        }
        else
        {
            parent_window.<?php echo $formname;?>.<?php echo $toid;?>.value="";
            parent_window.<?php echo $formname;?>.<?php echo $toname;?>.value="";
            borderize_off(targetelement);  
        }
    }
    function borderize_off(targetelement)
    {
     targetelement.style.borderColor="";
     targetelement.style.backgroundColor="";
     targetelement.style.color="";
     targetelement.style.fontWeight="";
    }
    function borderize_on(targetelement)
    { 
        color="#003FBF"; 
        targetelement.style.borderColor="black"; 
        targetelement.style.backgroundColor=color; 
        targetelement.style.color="white"; 
        targetelement.style.fontWeight="bold";
    }
    function borderize_clear(user_id)
    {  
        targetelements = document.getElementsByTagName("tr");
        for(var i=0;i<targetelements.length;i++)
        {
            if(targetelements[i].getAttribute("id")!=user_id)
            {            
                targetelements[i].style.backgroundColor="";  
                targetelements[i].style.borderColor="";  
                targetelements[i].style.color="";  
                targetelements[i].style.fontWeight="";
            }
        }
    }
    function begin_set()
    {  
        TO_VAL=parent_window.<?php echo $formname;?>.<?php echo $toid;?>.value;  
        for (step_i=0; step_i<document.all.length; step_i++)  
        {    
            if(document.all(step_i).className=="menulines")    
            {       
                user_id=document.all(step_i).id;       
                if(TO_VAL.indexOf(user_id)<0 || TO_VAL.indexOf(user_id)!=0)          
                    borderize_on(document.all(step_i));    
            }  
        }
    }
    --></script>
</head>
<body  topmargin="5" >
<table style="line-height: 150%;font-size:9pt;color:#001A41" bordercolorlight="#CCCCCC" bordercolordark="#FFFFFF"                border="1" cellpadding="0" cellspacing="0" width="100%" align="center">                
    <tbody>
    <?php
$msql->query( "select DEPT_NAME from department where Depart_x='".$id."' " );
$msql->next_record( );
echo "\r\n\t<tr bgcolor=\"#FED547\">\r\n\t\t<td nowrap=\"nowrap\" class=\"table_font\" align=\"center\" >";
echo $msql->f( "DEPT_NAME" );
echo "\r\n\t\t</td>\r\n\t</tr>";
$table_name = " user u,department d";
$cont_i = 0;
$t = " ";
if ( $id == "" )
{
    $sql = "select u.USER_NAME,u.USER_ID from user u where u.DEL='0' and u.HAS_LEFT='0' order by u.USER_NAME ASC";
}
else
{
    $sql = "select u.USER_NAME,u.USER_ID from ".$table_name.( " where  u.DEL='0' and u.HAS_LEFT='0' and u.DEPT_ID=d.DEPT_ID and d.Depart_x='".$id."' order by u.USER_NAME ASC" );
}
$msql->query( $sql );
while ( $msql->next_record( ) )
{
    ++$cont_i;
    echo "\t\t\t\t\t\t\r\n\t\t<!--************** 在此传值给左边的详细信息barg_customer_part.php(ID号,客户编号)*******-->\r\n\t\t<tr id=\"";
    echo $msql->f( "USER_ID" );
    echo "\" name=\"";
    echo $msql->f( "USER_NAME" );
    echo "\"  onclick=\"javascript:click_user('";
    echo $msql->f( "USER_ID" );
    echo "','";
    echo $msql->f( "USER_NAME" );
    echo "')\" class=\"menulines\" style=\"cursor:hand\" align=\"center\">\r\n                                          <td align=\"center\" nowrap=\"nowrap\">\r\n\t\t\t\t\t\t\t";
    echo $msql->f( "USER_NAME" )."&nbsp;";
    echo "                           \r\n                        </td>\r\n                    </tr>\r\n";
}
if($cont_i == 0)
    echo "<tr class=\"menulines\">\r\n  <td style=\"cursor:hand\" align=\"center\"><b>此部门暂没有成员</b></td>\r\n</tr>\r\n";
echo "                \r\n                </tbody>\r\n            </table>\r\n</body>\r\n</html>\r\n";
?>
