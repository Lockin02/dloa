<?php
include '../../../../webreport/data/mysql_GenXmlData.php';

//订单条件
$purchaseCondition = "";
//价格表条件
$priceCondition = "";
//开始日期
if($_GET['thisMonth'] > 9){
	$thisYearMonth =  $_GET['thisYear'] . $_GET['thisMonth'];
}else{
	$thisYearMonth =  $_GET['thisYear'] ."0". $_GET['thisMonth'];
}

//供应商
if(!empty($_GET['suppName'])){
	$purchaseCondition .= " and c.suppName like '%".$_GET['suppName']."%'";
}

//物料信息
if(!empty($_GET['productNumb'])){
	$purchaseCondition .= " and e.productNumb like '%".$_GET['productNumb']."%'";
}
//echo $purchaseCondition;
$QuerySQL = <<<QuerySQL
select
	c.suppName,c.suppId,c.productId,c.productNumb as productCode,c.productName,c.sendName,
	c.amountAll,
	c.moneyAll,
	c.prePrice,
	c.thisDate,
	p.prePrice as beforePrice,
	(c.prePrice - p.prePrice )*c.amountAll as moneyAdd
from
	(
	select
		c.suppName,c.suppId,e.productId,e.productNumb,e.productName,c.sendName,
		sum(e.amountAll) as amountAll,
		sum(e.moneyAll) as moneyAll,
		round(sum(e.moneyAll) / sum(e.amountAll),6) as prePrice,
		date_format(c.createTime,'%Y-%m-%d') as thisDate
	from
		oa_purch_apply_basic c
		inner join
		oa_purch_apply_equ e
			on c.id = e.basicId
	where
		c.isTemp = 0 and (((c.state in (4, 7) and c.ExaStatus = '完成') or (c.state in (5, 8,10)))) and e.amountAll > 0 and date_format(c.createTime ,'%Y%m') = $thisYearMonth $purchaseCondition
	group by
		c.suppName,e.productNumb,date_format(c.createTime,'%Y%m%d')
	) c
	inner join
	(
	select
		c.productId,c.thisDate,c.prePrice
	from
		(
		select
			productId,thisDate,sum(moneyAll) / sum(amountAll) as prePrice
		from oa_purch_product_price
		where date_format(thisDate,'%Y%m') < $thisYearMonth $priceCondition
			group by productId,thisDate
		order by productId asc,thisDate desc
		) c
		group by c.productId
	) p
		on p.productId = c.productId
where if(p.prePrice is null,p.prePrice is not null,c.prePrice < p.prePrice)
order by c.productNumb,c.suppName,c.thisDate
QuerySQL;
GenAttrXmlData($QuerySQL, false);
?>
