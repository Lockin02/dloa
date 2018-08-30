<?php
session_start( );
?>
<html>
<head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=gb2312">
    <link rel="stylesheet" type="text/css" href="../../inc/style.css">
    <script language="JavaScript">
    var CUR_ID="1";
    function clickMenu(ID){    
        targetelement=document.all(CUR_ID);    
        if(ID==CUR_ID)    {       
            if (targetelement.style.display=="none")           
                targetelement.style.display="";       
            else           
                targetelement.style.display="none";    
        }    
        else    {       
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
    <tr class="TableHeader" onclick="clickMenu('1')" style="cursor:hand" title="点击伸缩列表">       
        <td nowrap align="center"><b>按部门选择</b></td>     
    </tr>  
</table>  
<table border="0" cellspacing="1" width="100%" class="small" cellpadding="3" id="1">    
    <tr class="TableControl">      
        <td>  
            <div>  
            <?php include( "citation_user_top.php" ); ?>
            </div>      
        </td>    
    </tr>  
</table>
<?php
    if(!$todept||$todept==''){
?>
    <table border="0" cellspacing="1" width="100%" class="small" bgcolor="#000000" cellpadding="3" align="center">     
    <tr class="TableHeader" onclick="clickMenu('0')" style="cursor:hand" title="点击伸缩列表">       
        <td nowrap align="center"><b>按角色选择</b></td>     
    </tr>  
</table>  
<table border="0" cellspacing="1" width="100%" class="small" bgcolor="#000000" cellpadding="3" id="0" style="display:none">
<?php
//读取登陆用户的信息
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
    $USER_PRIV=$msql->f( "USER_PRIV" );
    $PRIV_NAME=$msql->f( "PRIV_NAME" );
    ?>    
    <tr class="TableControl">
          <td align="center" onclick="javascript:parent.user.location='citation_priv_table.php?id=<?php echo $USER_PRIV;?>&toid=<?php echo $toid;?>&toname=<?php echo $toname;?>&formname=<?php echo $formname;?>';" style="cursor:hand"><?php echo $PRIV_NAME;?></td>
    </tr>
    <?php
}
?>
</table>
<table border="0" cellspacing="1" width="100%" class="small" bgcolor="#000000" cellpadding="3" align="center">     
    <tr class="TableHeader" onclick="clickMenu('2')" style="cursor:hand" title="点击伸缩列表">       
        <td nowrap align="center"><b>按自定义用户组</b></td>     
    </tr>
</table>
<table border="0" cellspacing="1" width="100%" class="small" bgcolor="#000000" cellpadding="3" id="2" style="display:none">
<?php
$msql->query( "select USER_GROUP,GROUP_NAME from user_group where attribute_one='".$USER_ID."' order by GROUP_NO" );
while ( $msql->next_record( ) )
{
    $userGroupStr=$msql->f( "USER_GROUP" );
    $groupNameStr=$msql->f( "GROUP_NAME" );
    ?>
    <tr class="TableHeader" style="cursor:hand" onclick="javascript:parent.user.location='citation_attribute_table.php?id=<?php echo $userGroupStr;?>&toid=<?php echo $toid;?>&toname=<?php echo $toname;?>&formname=<?php echo $formname;?>'">
        <td nowrap align="center"><b><?php echo $groupNameStr;?></b></td>     
    </tr>
    <?php
}
?>
</table>
<table border="0" cellspacing="1" width="100%" class="small" bgcolor="#000000" cellpadding="3" align="center">     
    <tr class="TableHeader" style="cursor:hand" onclick="javascript:parent.user.location='citation_online_table.php?ID=&toid=<?php echo $toid; ?>&toname=<?php echo $toname;?>&formname=<?php echo $formname;?>'">
        <td nowrap align="center"><b>在线人员</b></td>     
    </tr>
</table>
<?php
    }
?>
<table border="0" cellspacing="1" width="100%" class="small" bgcolor="#000000" cellpadding="3" align="center">     
    <tr class="TableHeader" style="cursor:hand" onclick="javascript:parent.user.location='query.php?ID=&toid=<?php echo $toid; ?>&toname=<?php echo $toname;?>&formname=<?php echo $formname;?>&todept=<?php echo $todept;?>'">
        <td nowrap align="center"><b>人员查询</b></td>     
    </tr>
</table>
</body>
</html>

