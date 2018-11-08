<?php
include( "../../../../includes/db.inc.php" );
include( "../../../../includes/config.php" );
include( "../../../../includes/msql.php" );
include( "../../../../includes/fsql.php" );
include( "../../../../includes/util.php" );
include( "../../../../includes/common.inc.php" );
$QR_year=isset($_POST['QR_year']) ? $_POST['QR_year']:date("Y");
$QR_month=isset($_POST['QR_month']) ? $_POST['QR_month']:date("m");
$monthBgDate=$QR_year."-".$QR_month."-01";
$dates = date("t",strtotime($monthBgDate));
$monthEnDate=$QR_year."-".$QR_month."-".$dates;
$TO_NAME_Balance=isset($TO_NAME_Balance)?$TO_NAME_Balance:"";
$where = "";
if(isset($_POST["TO_NAME_Balance"]) && trim($_POST["TO_NAME_Balance"])){
    $tmpToUser="";
    $sql="select USER_ID from user where USER_NAME like '%".$_POST["TO_NAME_Balance"]."%' ";
    $msql->query($sql);
    while($msql->next_record()){
        if($msql->f("USER_ID")!="")
            $tmpToUser.=$msql->f("USER_ID").",";
    }
    $where = " and USER_ID in(".towhere($tmpToUser).")";
}

if(isset($DEPT_ID)&&isset($checkType)&&$checkType=="dept"){
	if(in_array($_SESSION['PRIV_NAME'],$projectCostChecker)||in_array($_SESSION['USER_JOBSID'],$projectCostChecker)){
		//$where .= " and DEPT_ID in( '$DEPT_ID',".implode(',',(array)$NetAppLimitDeptI).") ";
	}else {
		//$where .= " and DEPT_ID='$DEPT_ID' ";
	}
}
?>
<html>
<head>
    <title>个人借款余额概况表</title>
    <meta http-equiv="Content-Type" content="text/html; charset=gb2312">
    <link rel="stylesheet" type="text/css" href="../../../../inc/style.css">
    <script language="javascript">
    function exToExcel()
    {
        var elTable = document.getElementById("tbData"); //要导出的table id。
        var oRangeRef = document.body.createTextRange(); 
        oRangeRef.moveToElementText(elTable); 
        oRangeRef.execCommand("Copy");
        var appExcel = new ActiveXObject("Excel.Application");
        appExcel.Workbooks.Add().Worksheets.Item(1).Paste(); 
        appExcel.Visible = true; 
        appExcel = null;
    }
    </script>
</head>
<body class="bodycolor" topmargin="5">
<div>
    <table border="0" cellspacing="0" cellpadding="3" class="small" width="100%">
        <tr>    
            <td class="Big">
                <img src="../../../../images/notify_open.gif">
                <b><font color="#000000">个人借款余额概况</font></b>
                <br>    
            </td>  
        </tr>
    </table>        
    <br />        
<table border="1" width="80%" cellpadding="2" cellspacing="0" align="center" class="small" style="font-size:10pt;color:#000000;line-height: 150% " bordercolorlight="#FFFFFF" bordercolordark="#9FBFE3">  
<form action="" name="form2" method="post">                          
    <tr class="<?php echo $TableHeader;?>">
        <td nowrap class="TableData">姓名：</td>
        <td class="TableData" colspan="3">
            <input type="text" id="TO_NAME_Balance"  name="TO_NAME_Balance" class="SmallInput"  value="<?php echo $TO_NAME_Balance;?>" size="30">
            年份：
            <select name="QR_year" class="BigSelect">
                <?php
                    $bgyear=2009;
                    $enyear=date("Y");
                    for($i=$bgyear; $i<=$enyear; $i++){
                        ?>
                        <option value="<?php echo $i;?>" <?php if($QR_year==$i) echo "selected";?> ><?php echo $i;?></option>
                        <?php
                    }
                ?>
            </select>
            月份：
            <select name="QR_month" class="BigSelect">
                <?php
                    for($i=1; $i<=12; $i++){
                        ?>
                        <option value="<?php echo $i;?>" <?php if($QR_month==$i) echo "selected";?> ><?php echo $i;?></option>
                        <?php
                    }
                ?>
            </select>&nbsp;&nbsp;
            <input type="submit" value="查询" class="BigInput">&nbsp;&nbsp;
            <input type="button" value="导出到Excel" class="BigInput" onclick="exToExcel()" />&nbsp;&nbsp;
            <input type="button" value="打印" class="BigInput" onclick="window.print()" />&nbsp;&nbsp;
        </td>
    </tr>    
</form>
</table> 
<table border="1" width="80%" cellpadding="2" id="tbData" cellspacing="0" align="center" class="small" style="font-size:10pt;color:#000000;line-height: 150% " bordercolorlight="#FFFFFF" bordercolordark="#9FBFE3">
        <tr class="<?php echo $TableHeader2;?>">
            <td nowrap align="center">姓名</td>     
            <td nowrap align="center">个人余额</td>                    
            <td nowrap align="center">累计未报销数额</td>                       
            <td nowrap align="center">银行支付</td>       
            <td nowrap align="center">上月欠借款余额</td>             
            <td nowrap align="center">本月借款数额</td>
            <td nowrap align="center">本月还款数额</td>  
            <td nowrap align="center">公司余额</td>   
        </tr>
<?php
$totalY=0;
//print_r("select USER_NAME,USER_ID from user where DEL='0' and HAS_LEFT='0' ".$where." order by USER_NAME");die();
$fsql->query("select USER_NAME,USER_ID from user where DEL='0' and HAS_LEFT='0' ".$where." order by USER_NAME");
$x = 0;
$lastMonth = getLastMonth10();
while($fsql->next_record())
{
    $x++;
    $uid = $fsql->f("USER_ID");
    /****************累计未报销单额*******************/
    $addUpRow = $msql->getrow("select sum(Amount) AS amount from cost_summary_list where CostMan = '".$uid."' and Status not in('编辑','完成') and ( (isNotReced='0' and isProject='1') or isProject='0') and TO_DAYS(InputDate) < TO_DAYS('$monthEnDate') ");
    $addUpNotCost = num2Low($addUpRow["amount"]);
    //上月借款余额=已支付状态+还款中的金额
    $sql="select sum(Amount) as amounts from loan_list where Debtor='".$uid."' and Status in ('已支付','还款中','已还款') and isTemp = 0 and TO_DAYS(PayDT) < TO_DAYS('$monthBgDate') ";
    $msql->query($sql);
    $msql->next_record();
    $preLoanPay=$msql->f("amounts");
    $sql="select sum(r.Money) as rm from loan_list l , loan_repayment r  where l.ID=r.loan_ID and  l.Debtor='".$uid."' and l.Status in('还款中','已还款' ) and l.isTemp = 0 and TO_DAYS(r.CreateDT) < TO_DAYS('$monthBgDate')";
    $msql->query($sql);
    $msql->next_record();
    $preloanReping=$msql->f("rm");
    $preLoanBalance=$preLoanPay-$preloanReping;
    //本月借款数额
    $loan = $msql->getrow( "select sum(Amount) from loan_list where Status in('已支付','还款中','已还款') and isTemp = 0 and to_days(PayDT) >=to_days('$monthBgDate') and to_days(PayDT) <=to_days('$monthEnDate') and Debtor ='".$uid."' ");
    $loan = num2Low($loan["sum(Amount)"]);
    //本月还款数额
    $return = $msql->getrow( "select sum(r.Money) as rm from  loan_list l, loan_repayment r  where l.ID=r.loan_ID and  l.Debtor='".$uid."' and l.Status in ('还款中','已还款') and l.isTemp=0 and TO_DAYS(r.CreateDT) >= TO_DAYS('$monthBgDate') and TO_DAYS(r.CreateDT) <= TO_DAYS('$monthEnDate') ");
    $return = $return["rm"];
    $leveMoney=$preLoanBalance+$loan-$return; //公司余额(本期/剩余借款余额) = 上期借款余额 + 本月借款数额 - 本月还款数额
    $totalY+=$leveMoney;
    /****************个人余额*******************/
    $PersonalRest=$preLoanBalance+$loan-$return-$addUpNotCost; //上期借款余额 + 本月借款数额 - 本月还款数额 - 累计未报销数额
    if($PersonalRest<0)
        $PersonalRest=0;
    /****************银行支付*******************/
    $bankPay=-($leveMoney-$return);
    if($bankPay<0)
        $bankPay=0;
?>
        <tr class="TableLine<?php echo $x%2+1;?>" onmouseover="this.className='TableHightLine'" onmouseout="this.className='TableLine<?php echo $x%2+1;?>'">
            <td nowrap align="center"><?php echo $fsql->f("USER_NAME");?></td>     
            <td nowrap align="right"><?php echo num_to_maney_format($PersonalRest);?></td>
            <td nowrap align="right"><?php echo num_to_maney_format($addUpNotCost);?></td>                                                    
            <td nowrap align="right"><?php echo num_to_maney_format($bankPay);?></td>          
            <td nowrap align="right"><?php echo num_to_maney_format($preLoanBalance);?></td>
            <td nowrap align="right"><?php echo num_to_maney_format($loan);?></td>  
            <td nowrap align="right"><?php echo num_to_maney_format($return);?></td> 
            <td nowrap align="right"><?php echo num_to_maney_format($leveMoney);?></td> 
        </tr>
<?php  
}
?>        
    </table>
</div>                             
</body>
</html>