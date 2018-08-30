<html>
<head>
    <title>意见汇总表</title>
    <meta http-equiv="Content-Type" content="text/html; charset=gb2312">
    <link rel="stylesheet" type="text/css" href="<?php echo $this->compDir;?>inc/style.css">
    <style type="text/css">
    <!--
    .title {  font-family: "宋体"; font-size: 18pt; font-weight: bold}
    -->
    </style>
</head>
<body class="bodycolor">
<table border="0" cellspacing="1" width="550" class="small" bgcolor="#000000" cellpadding="3" align="center">
    <tr class="<?php echo $TableHeader;?>">
        <td nowrap align="center"><b>步骤号</b></td>
        <td nowrap align='center'><b>步骤名称</b></td>     
        <td nowrap align='center'><b>审批人</b></td>     
        <td nowrap align='center'><b>审批结果</b></td>     
        <td nowrap><b>审批意见</b></td>
        <td nowrap><b>审批时间</b></td>
    </tr>
    <tr class=TableLine1>
        <td nowrap align='center' colspan='6'><b>开始</b></td>
    </tr>
<?php
//要走的固定流程
$msql->query( "select *  from flow_step  where Wf_task_ID='$taskId' order by SmallID" );
$i=0;
$nj=0;
$pass=true;
while ( $msql->next_record( ) )
{
    $PRCS_ID = $msql->f( "ID" );
    $PRCS_Step = $msql->f( "Step" );
    $PRCS_NAME = $msql->f( "Item" );
    $PRCS_USER = $msql->f( "User" );
    $SmallID = $msql->f( "SmallID" );
    $taskid = $msql->f( "Wf_task_ID" );
    $iscrt = $msql->f( "Flag" );

    ?>
    <tr class=TableLine2>
        <td nowrap align='center' colspan='6'>
            <img border=0 src='<?php echo $this->compDir;?>images/arrow_down.gif' width='11' height='13'>
        </td>
    </tr>
    <tr class=TableLine1>
        <td nowrap align='center'>
            第<b><font color='red'><?php echo $SmallID;?></font></b>步
        </td>
        <td align='center'>
            <?php
            if($iscrt!=='0')
                echo $PRCS_NAME;
            else
                echo "<font color='red'>".$PRCS_NAME."</font>";
            ?>
        </td>
        <td nowrap align='center'>
    <?php
    $Content = "";
    $Endtime="";
    $Result="";
    //实际走的流程
    $fsql->query( "select *  from flow_step_partent where SmallID='$SmallID' and Wf_task_ID='$taskid' order by ID" );
    if ( $fsql->next_record( ) )
    {
        $Result = $fsql->f( "Result" );
        $START = $fsql->f( "START" );
        $Endtime = $fsql->f( "Endtime" );
        $Content= $fsql->f( "Content" );
        $PRCS_USER = $fsql->f("User");
    
        if ( $Result == "ok" )
        {
            $Result = "同意";
        }
        if ( $Result == "no" )
        {
            $Result = "<font color=\"red\"><b>不同意</b></font>";
            $pass=false;
        }
    }
    if ( $Result == "" )
    {
        $Result = "未审批";
    }
    $PRCS_USER=rtrim($PRCS_USER,",");
    $usernames = "";
    $fsql->query( "select USER_NAME from user  where USER_ID in(".towhere($PRCS_USER).")" );
    while ( $fsql->next_record( ) )
    {
        $usernames.= $fsql->f( "USER_NAME" )."/";
    }
    $usernames = rtrim($usernames,"/");
    ?>
            <?php echo $usernames;?>
        </td>
        <td nowrap align='center'>
            <?php echo $Result;?>
        </td>
        <td>
            <?php if($Result!="未审批") echo "<font color=green>".$Content."</font>";?>
        </td>
        <td>
            <?php if($Result!="未审批") echo "<font color=green>".$Endtime."</font>";?>
        </td>
    </tr>
    <?php
    ++$nj;
}
?>

    <tr class=TableLine2>
        <td nowrap align='center' colspan='6'>
            <img border=0 src='<?php echo $this->compDir;?>images/arrow_down.gif' width='11' height='13'>
        </td>
    </tr>
    <tr class=TableLine1>
        <td nowrap align='center' colspan='6'><b>结束</b></td>
    </tr>
</table>
<div align="center" style="margin-top:5px;"><input type="button" name="btn" value="关闭" class="BigButton" onclick="window.close();" ></div>
</body>
</html>