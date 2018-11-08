<?php
include( "../../../../includes/db.inc.php" );
include( "../../../../includes/config.php" );
include( "../../../../includes/msql.php" );
include( "../../../../includes/fsql.php" );
include( "../../../../includes/util.php" );
include("../../../../includes/getUSER_DEPT_ID.php");

//print_r($_POST);

$ID = isset($ID)?$ID:"";
$id_rl = $meetingsearch[0][$ID_RELATION];

$Reason = isset($Reason)?$Reason:"";
$rs_rl = $meetingsearch[0][$Reason_RELATION];

$Amount = isset($Amount)?$Amount:"";
$amount_rl = $meetingsearch[0][$Amount_RELATION];

$deptid = isset($deptid)?$deptid:"";
$Debtor = isset($Debtor)?$Debtor:"";
$deptname = isset($deptname)?$deptname:"";
$DebtorName = isset($DebtorName)?$DebtorName:"";
$PayDT_DATE1 = isset($PayDT_DATE1)?$PayDT_DATE1:"";
$PayDT_DATE2 = isset($PayDT_DATE2)?$PayDT_DATE2:"";
$ReceiptDT_DATE1 = isset($ReceiptDT_DATE1)?$ReceiptDT_DATE1:"";
$ReceiptDT_DATE2 = isset($ReceiptDT_DATE2)?$ReceiptDT_DATE2:"";
$Status = isset($Status)?$Status:"";
$ProjectNo=isset($ProjectNo)?$ProjectNo:"";
$mnamefield=" and l.isTemp = 0 ";
if ( trim( $ID ) )
{
    $mnamefield .= " and l.ID ".$id_rl." '$ID'";
}
if ( trim( $Reason ) )
{
    $mnamefield .= " and l.Reason ".$rs_rl." '$Reason'";
}
if ( trim( $Amount ) )
{
    $mnamefield .= " and l.Amount ".$amount_rl." '$Amount'";
}
if ( trim( $deptname ) )
{
    $tmpDeptStr="";
    $sql="select DEPT_ID from department  where DEPT_NAME like '%$deptname%' ";
    $msql->query($sql);
    while($msql->next_record()){
        if($msql->f("DEPT_ID"))
            $tmpDeptStr.=$msql->f("DEPT_ID").",";
    }
    $mnamefield .= " and d.DEPT_ID in(".towhere($tmpDeptStr).")";
}
if ( trim( $DebtorName ) )
{
    $tmpUserStr="";
    $sql="select USER_ID from user where USER_NAME like '%$DebtorName%' ";
    $msql->query($sql);
    while($msql->next_record()){
        if($msql->f("USER_ID"))
            $tmpUserStr.=$msql->f("USER_ID").",";
    }
    $mnamefield .= " and l.Debtor in( ". towhere($tmpUserStr).")";
}

if(isset($StatusArr))
{
    $mnamefield .= " and l.Status in( ".towhere(implode(",",$StatusArr)).")";
}else
    $mnamefield .= " and l.Status in('部门审批','财务审核','出纳支付','已支付','已还款','还款中')";

if ( trim( $ReceiptDT_DATE1 ) )
{
    $mnamefield .= " and l.ReceiptDT >= '".$ReceiptDT_DATE1." 00:00:00'";
}
if ( trim( $ReceiptDT_DATE2 ) )
{
    $mnamefield .= " and l.ReceiptDT <= '".$ReceiptDT_DATE2." 23:59:59'";
}
if ( trim( $PayDT_DATE1 ) )
{
    $mnamefield .= " and l.PayDT >= '".$PayDT_DATE1." 00:00:00'";
}
if ( trim( $PayDT_DATE2 ) )
{
    $mnamefield .= " and l.PayDT <= '".$PayDT_DATE2." 23:59:59'";
}
if(trim($ProjectNo)){
    $mnamefield .= " and ProjectNo like '%$ProjectNo%' ";
}

$orderby = " order by ".$loanorder[0][$ORDER];
if(!isset($_LIST_CHECK))
    $_LIST_CHECK = array();
$SLT_LIST = $_LIST_CHECK;
if(empty($SLT_LIST) || !in_array("ID",$SLT_LIST))
{
    array_unshift($SLT_LIST,"ID");
}
$sltFields = implode(",",$SLT_LIST);
if(in_array("DEPT_ID",$SLT_LIST))//("ProjectName","Reason",$sltFields))
{
    $sltFields = str_replace("DEPT_ID","d.DEPT_NAME",$sltFields);
}
?>
<html>
<head>
    <title>报销记录查询</title>
    <meta http-equiv="Content-Type" content="text/html; charset=gb2312">
    <link rel="stylesheet" type="text/css" href="../../../../inc/style.css">
    <style>
        td{height:25}
    </style>
    <script>
    function showWin(url)
    {
        var myleft=(screen.availWidth-500)/2;
        window.open(url,"","height=400,width=650,status=yes,toolbar=no,menubar=no,location=no,scrollable=yes,top=150,left="+myleft+",resizable=yes");   
    }
    </script>
</head>
<body class="bodycolor" topmargin="5">
<table border="0" width="100%" cellspacing="0" cellpadding="3" class="small">
  <tr>
    <td class="Big">
        <img src="../../../../images/menu/infofind.gif" WIDTH="22" HEIGHT="20"><b><font color="#000000"> 查询结果 - 借款记录查询</font></b><br>
    </td>
  </tr>
</table>
<?php
$crow = $msql->getrow( "select count(distinct l.ID) from loan_list l,user u,department d where u.USER_ID=l.Debtor and u.DEPT_ID=d.DEPT_ID ".$mnamefield.$orderby);
$pages = $crow["count(distinct l.ID)"];
$totalpage = ceil( $pages / $offset );
$totalgroup = ceil( $totalpage / $offset );
if (!isset($page) || $page == "" )
{
    $page = 1;
}
include( "../../../../inc/page.php" );
if ($pages>0):
?>
<br />
<div align="center">
<table border="0" width="98%" cellpadding="3" cellspacing="1" align="center" bgcolor="#d0d0c8" class="small">
    <tr class="<?php echo $TableHeader;?>">
<?php  if ( in_array("ID",$_LIST_CHECK)):?>
        <td width="5%" align='center'><b>ID</b></td>
<?php   endif;
        if ( in_array("DEPT_ID",$_LIST_CHECK)):?>
       <td width="6%" align='center'><b>部门</b></td>
<?php   endif;
        if ( in_array("Debtor",$_LIST_CHECK)):?>
       <td width="5%" align='center'><b>报销人</b></td>
<?php  endif;
        if ( in_array("Amount",$_LIST_CHECK)):?>
       <td width="8%" align='center'><b>借款金额</b></td>
<?php   endif;
       if ( in_array("Reason",$_LIST_CHECK)):?>
       <td width="28%" align='center'><b>原因</b></td>
<?php   endif;
        if ( in_array("PayDT",$_LIST_CHECK)):?>
       <td width="10%" align='center'><b>支付时间</b></td>
<?php   endif;
        if ( in_array("ReceiptDT",$_LIST_CHECK)):?>
       <td width="10%" align='center'><b>还款时间</b></td>
<?php   endif;
        if ( in_array("Status",$_LIST_CHECK)):?>
       <td width="8%" align='center'><b>状态</b></td>
<?php   endif;?>
       <td width="15%" align="center"><b>操 作</b></td>
    </tr>
<?php
    $sltSql = "select ".$sltFields." from loan_list l,user u,department d where u.USER_ID=l.Debtor and u.DEPT_ID=d.DEPT_ID ".$mnamefield.$orderby." limit $pagelimit,$offset";
//    print_r($sltSql);die();
    $fsql->query($sltSql);
    $x = 0;
    while ( $fsql->next_record( ) )
    {
        $ID = $fsql->f( "ID" );
        
?>
    <tr class="TableLine<?php echo $x%2+1;?>" onmouseover="this.className='TableHightLine'" onmouseout="this.className='TableLine<?php echo $x%2+1;?>'">
<?php
        foreach($_LIST_CHECK as $k=>$fname)
        {
            
            $align="center";
            switch($fname)
            {
                case "Debtor":
                    $feildValue = getUserNameById($fsql->f($fname));
                    break;
                case "Amount":
                    $feildValue = num2Low($fsql->f("Amount"));
                    $align="right";
                    break;
                case "DEPT_ID":
                    $feildValue = $fsql->f("DEPT_NAME");
                    break;
                default:
                    $feildValue = $fsql->f($fname);
                    break;
            };
?>        
        <td align='<?php echo $align;?>'><?php echo $feildValue;?></td>
<?php
        }
        $x++;
?>        
        <td nowrap align="center" nowrap>
            <a href="#" onclick="showWin('bill.php?ID=<?php echo $ID;?>')">详情</a>&nbsp;
            <a href="#" onclick="showWin('examine_view.php?ID=<?php echo $ID;?>')">审批意见</a>&nbsp;
            <a href="#" onclick="showWin('repayment_view.php?ID=<?php echo $ID;?>')">还款情况</a>&nbsp;
        </td>
    </tr>
<?php
    }
?>
</table>
</div>
<?php
    require("../../../../module/PageController/pager.php");
    else:
        oa_mesg( "无符合条件的记录" );
    endif;
?>
<br />
<center><input type="button" class="BigButton" value="返回" onclick="location.href='index.php';" class="BigButton"></center>
</body>
</html>
