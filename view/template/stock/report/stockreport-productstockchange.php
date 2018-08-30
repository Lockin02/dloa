<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
set_time_limit(0);

$thisYearMonth1 = $_GET['thisYear1'].str_pad($_GET['thisMonth1'],2,0,STR_PAD_LEFT);
$thisYearMonth2 = $_GET['thisYear2'].str_pad($_GET['thisMonth2'],2,0,STR_PAD_LEFT);

if($_GET['productNo']){
	$condition = " and c.productNo = '".$_GET['productNo']."'";
}

$sql = <<<QuerySQL
select
	c.productId,c.productNo,c.productName,c.productModel,c.balanceAmount,c.clearingNum as actNum1,
	s.clearingNum as actNum2,c.clearingNum - s.clearingNum as diffNum,
	if(c.balanceAmount is null,0,c.balanceAmount) - if(s.balanceAmount is null,0,s.balanceAmount) as diffMoney,
	c.stockName
from
	oa_finance_stockbalance c
	left join
	oa_finance_stockbalance s
		on c.productId = s.productId and c.stockId = s.stockId
where date_format(c.thisDate,'%Y%m') = "$thisYearMonth1"  and date_format(s.thisDate,'%Y%m') = "$thisYearMonth2" $condition
order by if(c.balanceAmount is null,0,c.balanceAmount) - if(s.balanceAmount is null,0,s.balanceAmount) desc
QuerySQL;
GenAttrXmlData($sql,false);