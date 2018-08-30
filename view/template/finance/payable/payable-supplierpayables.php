<?php
include '../../../../webreport/data/mysql_GenXmlData.php';

//订单条件
$purchaseCondition = "";
//开始日期
$beginDate = $_GET['beginYear'] . "-" . $_GET['beginMonth'] . "-1";
$purchaseCondition .= " and date_format(createTime,'%Y%m%d') >= date_format('$beginDate','%Y%m%d')";

//结束日期
$endYearMonthNum = cal_days_in_month ( CAL_GREGORIAN, $_GET['endMonth'], $_GET['endYear'] );
$endDate = $_GET['endYear'] . "-" . $_GET['endMonth'] . "-" . $endYearMonthNum;
$purchaseCondition .= " and date_format(createTime,'%Y%m%d') <= date_format('$endDate','%Y%m%d')";

//供应商
if(!empty($_GET['supplierName'])){
	$purchaseCondition .= " and supplierName like '%".$_GET['supplierName']."%'";
}

//echo $purchaseCondition;
$QuerySQL = <<<QuerySQL
select
	supplierName,yearMonth,sum(payMoney) as payMoney,round(sum(invoiceMoney),2) as invoiceMoney,round(sum(invoiceMoney) - sum(payMoney),2) as differ
from
(
	select supplierName,date_format(formDate,'%Y-%m') as yearMonth,sum(if(formType = 'CWYF-03',-amount,amount)) as payMoney,0 as invoiceMoney from oa_finance_payables where 1=1 $purchaseCondition group by supplierName,date_format(formDate,'%Y%m')
	union
	select supplierName,date_format(formDate,'%Y-%m') as yearMonth,0 as payMoney,sum(if(formType = 'blue',amount,-amount)) as invoiceMoney from oa_finance_invpurchase where 1=1 $purchaseCondition group by supplierName,date_format(formDate,'%Y%m')
	union
	select supplierName,date_format(formDate,'%Y-%m') as yearMonth,0 as payMoney,sum(if(isRed = '0',amount,-amount)) as invoiceMoney from oa_finance_invother where 1=1 $purchaseCondition group by supplierName,date_format(formDate,'%Y%m')
) p
group by p.supplierName,p.yearMonth
order by p.supplierName,p.yearMonth
QuerySQL;
GenAttrXmlData($QuerySQL, false);
?>
