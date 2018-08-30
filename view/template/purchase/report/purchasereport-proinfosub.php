<?php
include '../../../../webreport/data/mysql_GenXmlData.php';

//订单条件
$purchaseCondition = "";
//开始日期
$beginDate = $_GET['beginYear'] . "-" . $_GET['beginMonth'] . "-1";
$purchaseCondition .= " and date_format(ab.createTime,'%Y%m%d') >= date_format('$beginDate','%Y%m%d')";

//结束日期
$endYearMonthNum = cal_days_in_month ( CAL_GREGORIAN, $_GET['endMonth'], $_GET['endYear'] );
$endDate = $_GET['endYear'] . "-" . $_GET['endMonth'] . "-" . $endYearMonthNum;
$purchaseCondition .= " and date_format(ab.createTime,'%Y%m%d') <= date_format('$endDate','%Y%m%d')";

//供应商
if(!empty($_GET['suppName'])){
	$purchaseCondition .= " and ab.suppName like '%".$_GET['suppName']."%'";
}

//物料信息
if(!empty($_GET['productNumb'])){
	$purchaseCondition .= " and ae.productNumb like '%".$_GET['productNumb']."%'";
}

//echo $purchaseCondition;
$QuerySQL = <<<QuerySQL
select
	productNumb as productCode,productName,
	sum(ae.amountAll) as proNum,
	(sum(ae.moneyAll)/sum(ae.amountAll)) as price,
	sum(ae.moneyAll) as subCost
from
	oa_purch_apply_equ ae
	left join
	oa_purch_apply_basic ab
		on(ab.id=ae.basicId)
where
	ab.isTemp = 0 and (((ab.state in (4, 7) and ab.ExaStatus = '完成') or (ab.state in (5, 8,10)))) and ae.amountAll > 0  $purchaseCondition
group by ae.productNumb,ae.productName
order by subCost desc,productNumb
QuerySQL;
GenAttrXmlData($QuerySQL, false);
?>
