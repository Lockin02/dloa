<html>
<head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=gb2312">
    <link rel="stylesheet" type="text/css" href="../../inc/style.css">
    <script language="JavaScript">
    var CUR_ID="1";
    function clickMenu(ID)
    {    
        targetelement=document.all(CUR_ID);    
        if(ID==CUR_ID)    
        {       
            if (targetelement.style.display=="none")           
                targetelement.style.display='';       
            else           
                targetelement.style.display="none";    
        }    
        else    
        {       
            targetelement.style.display="none";       
            targetelement=document.all(ID);       
            targetelement.style.display="";    
        }    
        CUR_ID=ID;
    }
    </script>
</head>
<body class="bodycolor"  topmargin="1" leftmargin="0">  
<table border="0" cellspacing="1" width="100%" class="small" bgcolor="#000000" cellpadding="3" align="center">     
    <tr class="TableHeader" style="cursor:hand" onclick="javascript:parent.user.location='query.php?ID=&toid=<?php echo $toid;?>&toname=<?php echo $toname;?>&formname=<?php echo $formname;?>&todept=<?php echo $todept;?>';">
        <td nowrap align="center">
            <b>��Ա��ѯ</b>
        </td>     
    </tr>
</table>
<table border="0" cellspacing="1" width="100%" class="small" bgcolor="#000000" cellpadding="3" align="center">     
    <tr class="TableHeader" onclick="clickMenu('1')" style="cursor:hand" title="��������б�">       
        <td nowrap align="center">
            <b>������ѡ��</b>
        </td>     
    </tr>  
</table>  
<table border="0" cellspacing="1" width="100%" class="small" cellpadding="3" id="1">    
    <tr class="TableControl">      
        <td>  
            <div>
            <?php include( "citation_user_top.php" );?>
            </div>      
        </td>    
    </tr>  
</table>  
<?php
if(!isset($todept)||!trim($todept)){
    ?>
<table border="0" cellspacing="1" width="100%" class="small" bgcolor="#000000" cellpadding="3" align="center">
    <tr class="TableHeader" onclick="clickMenu('0')" style="cursor:hand" title="��������б�">
        <td nowrap align="center">
            <b>����ɫѡ��</b>
        </td>
    </tr>
</table>
<table border="0" cellspacing="1" width="100%" class="small" bgcolor="#000000" cellpadding="3" id="0" style="display:none">
<?php
//��ȡ��½�û�����Ϣ
$sqlStr="select  USER_PRIV,DEPT_ID ,area from user where  USER_ID='".$USER_ID."'";
$msql->query( $sqlStr );
while ( $msql->next_record( ) ){
    $checkDept=$msql->f("DEPT_ID");
    $checkPriv=$msql->f("USER_PRIV");
    $checkArea=$msql->f("area");
}
$msql->query( "select USER_PRIV,PRIV_NAME from user_priv order by PRIV_NO" );
while ( $msql->next_record( ) )
{
    if($msql->f( "USER_PRIV" )=="1"&&$checkPriv!=1){
        continue;
    }
    echo "<tr class=\"TableControl\">      <td align=\"center\" onclick=\"javascript:parent.user.location='citation_priv_table.php?id=";
    echo $msql->f( "USER_PRIV" );
    echo "&toid=";
    echo $toid;
    echo "&toname=";
    echo $toname;
    echo "&formname=";
    echo $formname;
    echo "';\" style=\"cursor:hand\">";
    echo $msql->f( "PRIV_NAME" );
    echo "</td>    </tr>  ";
}
?>
</table>
<table border="0" cellspacing="1" width="100%" class="small" bgcolor="#000000" cellpadding="3" align="center">
    <tr class="TableHeader" onclick="clickMenu('2')" style="cursor:hand" title="��������б�">
        <td nowrap align="center">
            <b>���Զ����û���</b>
        </td>
    </tr>
</table>
<table border="0" cellspacing="1" width="100%" class="small" bgcolor="#000000" cellpadding="3" id="2" style="display:none">
<?php
$msql->query( "select USER_GROUP,GROUP_NAME from user_group where attribute_one='".$USER_ID."' order by GROUP_NO" );
while ( $msql->next_record( ) )
{
    echo "<tr class=\"TableControl\">      <td align=\"center\" onclick=\"javascript:parent.user.location='citation_attribute_table.php?id=";
    echo $msql->f( "USER_GROUP" );
    echo "&toid=";
    echo $toid;
    echo "&toname=";
    echo $toname;
    echo "&formname=";
    echo $formname;
    echo "';\" style=\"cursor:hand\">";
    echo $msql->f( "GROUP_NAME" );
    echo "</td>    </tr>  ";
}
?>
</table>
<table border="0" cellspacing="1" width="100%" class="small" bgcolor="#000000" cellpadding="3" align="center">
    <tr class="TableHeader" style="cursor:hand" onclick="javascript:parent.user.location='citation_online_table.php?ID=&toid=<?php echo $toid;?>&toname=<?php echo $toname;?>&formname=<?php echo $formname;?>';">
        <td nowrap align="center">
            <b>������Ա</b>
        </td>
    </tr>
</table>
    <?php
}
?>
</body>
</html>

