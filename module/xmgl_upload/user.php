<?php
include( "../../includes/db.inc.php" );
include( "../../includes/config.php" );
include( "../../includes/msql.php" );
include( "../../includes/fsql.php" );
$dirs = $FLOW_ID."_".$mod;
@mkdir( "../../attachment/xmgl/".$dirs, 511 );
if ( $flag == "Addnew" && $attachfile != "" )
{
    $temp = $_FILES['attachfile'];
    $SourceFileName = $temp['tmp_name'];
    $FileName = $temp['name'];
    $msql->query( "select * from xmgl_uploadlist  where PRCS_ID='".$mod."' and FLOW_ID='$FLOW_ID' and Filename='$FileName'" );
    if ( $msql->next_record( ) )
    {
        succ( "请不要重复添加上传文件", "user.php?mod=".$mod."&FLOW_ID=$FLOW_ID" );
    }
    else
    {
        $fsql->query( "insert into xmgl_uploadlist values('0','".$FLOW_ID."','$mod','$FileName')" );
        @copy( $SourceFileName, "../../attachment/xmgl/".$dirs."/$FileName" );
    }
}
if ( $flag == "deleted" )
{
    $msql->query( "select * from xmgl_uploadlist  where ID='".$code."'" );
    if ( $msql->next_record( ) )
    {
        $delfile = $msql->f( "Filename" );
    }
    @unlink( "../../attachment/xmgl/".$dirs."/$delfile" );
    $msql->query( "delete from  xmgl_uploadlist where ID='".$code."'" );
    $mod = $mcode;
    $FLOW_ID = $fcode;
}
echo "<html>\r\n<head>\r\n<title></title>\r\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=gb2312\">\r\n<link rel=\"stylesheet\" type=\"text/css\" href=\"../../inc/style.css\">\r\n\r\n<style>\r\n.menulines{}\r\n</style>\r\n<script language=\"Javascript\">\r\nfunction deleted(unit_code,fn,mcd,fcd)\r\n{\r\n\t\r\n\tif(unit_code!=\"\")\r\n\t{\r\n\tmsg='确认要删除'+fn+'文件?';\r\n\t\r\n\tif (window.confirm(msg))\r\n\t{\r\n\tfrmDelete.flag.value=\"deleted\";\r\n\tfrmDelete.code.value=unit_code;\t\r\n\tfrmDelete.mcode.value=mcd;\t\r\n\tfrmDelete.fcode.value=fcd;\t\r\n\tfrmDelete.submit();\r\n\t}\r\n        }\r\n}\r\n\r\n\r\n</script>\r\n<SCRIPT>\r\n<!--\r\n\r\nvar menu_enter=\"\";\r\n\r\nfunction borderize_on(e)\r\n{\r\n color=\"#708DDF\";\r\n source3=event.srcElement\r\n\r\n if(source3.className==\"menulines\" && source3!=menu_enter)\r\n    source3.style.backgroundColor=color;\r\n}\r\n\r\nfunction borderize_on1(e)\r\n{\r\n for (i=0; i<document.all.length; i++)\r\n { document.all(i).style.borderColor=\"\";\r\n   document.all(i).style.backgroundColor=\"\";\r\n   document.all(i).style.color=\"\";\r\n   document.all(i).style.fontWeight=\"\";\r\n }\r\n\r\n color=\"#003FBF\";\r\n source3=event.srcElement\r\n\r\n if(source3.className==\"menulines\")\r\n { source3.style.borderColor=\"black\";\r\n   source3.style.backgroundColor=color;\r\n   source3.style.color=\"white\";\r\n   source3.style.fontWeight=\"bold\";\r\n }\r\n\r\n menu_enter=source3;\r\n}\r\n\r\nfunction borderize_off(e)\r\n{\r\n source4=event.srcElement\r\n\r\n if(source4.className==\"menulines\" && source4!=menu_enter)\r\n    {source4.style.backgroundColor=\"\";\r\n     source4.style.borderColor=\"\";\r\n    }\r\n}\r\n\r\n//-->\r\n</SCRIPT>\r\n<script Language=\"JavaScript\">\r\nvar parent_window = parent.dialogArguments;\r\n\r\nfunction add_user(user_id,user_name)\r\n{\r\n  TO_VAL=parent_window.form1.TO_ID.value;\r\n  if(TO_VAL.indexOf(user_id+\",\")<0 && TO_VAL.indexOf(user_id)!=0)\r\n  {\r\n    parent_window.form1.TO_ID.value+=user_id+\",\";\r\n    parent_window.form1.selectFile.value+=user_name+\",\";\r\n  }\r\n}\r\n</script>\r\n</head>\r\n\r\n<body class=\"bodycolor\" topmargin=\"5\">\r\n<table border='0' cellspacing='1' width='100%' class='small'  cellpadding='3' bgcolor='#d0d0c8'  align='center'>\r\n  <TBODY>\r\n  <TR>\r\n    <TD>\r\n      <FIELDSET>\r\n<form action=\"\" method=\"post\" name=\"frmDelete\">\r\n<table border=\"0\" cellspacing=\"0\" width=\"100%\" class=\"small\" cellpadding=\"3\"  bordercolorlight=\"#d0d0c8\" bordercolordark=\"#FFFFFF\"  onMouseover=\"borderize_on(event)\" onMouseout=\"borderize_off(event)\" onclick=\"borderize_on1(event)\">\r\n<tr class='TableContent'>\r\n  <td class='menulines' onclick=\"javascript:add_all();\" style=\"cursor:hand\" align='center'>以下全部上传</td>\r\n</tr>";
$msql->query( "select * from xmgl_uploadlist  where PRCS_ID='".$mod."' and FLOW_ID='$FLOW_ID' order by ID" );
while ( $msql->next_record( ) )
{
    $Filename = $msql->f( "Filename" );
    $ID = $msql->f( "ID" );
    echo "<tr class=\"TableControl\">\r\n\r\n  <td class=\"menulines\" align=\"center\">\r\n  <a href=\"javascript:deleted('";
    echo $ID;
    echo "','";
    echo $Filename;
    echo "','";
    echo $mod;
    echo "','";
    echo $FLOW_ID;
    echo "')\">";
    echo $Filename;
    echo "</a></td>\r\n    </tr>\r\n";
    $add_all_str .= "add_user('".$ID."','$Filename');\n ";
}
echo "\r\n<thead class=\"TableControl\">\r\n  <th bgcolor=\"#d6e7ef\" colspan=\"2\"><b>选择文件</b></th>\r\n</thead>\r\n</table>\r\n<input type=\"hidden\" name=\"flag\">\r\n<input type=\"hidden\" name=\"code\">\r\n<input type=\"hidden\" name=\"mcode\">  \r\n<input type=\"hidden\" name=\"fcode\">   \r\n</form>\r\n\r\n<script Language=\"JavaScript\">\r\nfunction add_all()\r\n{\r\n";
echo $add_all_str;
echo "  \r\n\r\n}\r\n</script>\r\n\r\n</body>\r\n</html>\r\n";
?>
