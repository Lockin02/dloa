<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
set_time_limit(0);

$thisDate = $_GET['thisDate'];
$moneyLimit=$_GET['moneyLimit'];

$condition = '';
$stock = $_GET['stock'];
if($stock){
	$condition .= " and c.stockId = '$stock'";
}

$productNo = $_GET['productNo'];
if($productNo){
	$condition .= " and c.productNo = '$productNo'";
}
if ($moneyLimit) {
    $subPrice= " c.balanceAmount + if(i.actNum is null,0,i.actNum*c.price) ";
    $price= "  c.price";
}else{
    $subPrice= " 0";
    $price= " 0 as price";
}

$sql = <<<QuerySQL
select
	c.productId,
	c.productNo,
	c.productName,
	c.productModel,
	$price,
	c.stockName,
	c.clearingNum + if(i.actNum is null,0,i.actNum) as stockNum,
	$subPrice as stockMoney,
	ot.lasAuditDate
from
	oa_finance_stockbalance c
		left join
	(
		select ii.stockId,ii.productId,sum(ii.actNum) as actNum
		from
		(
			select
				it.inStockId as stockId,it.productId,if(i.isRed = 0,it.actNum ,-it.actNum) as actNum
			from
				oa_stock_instock i
					inner join
				oa_stock_instock_item it
					on i.id = it.mainId
			where docStatus = 'YSH' and date_format(i.auditDate,'%Y%m') = date_format("$thisDate",'%Y%m') and i.auditDate <= "$thisDate"

			union all

			select
				it.stockId,it.productId,if(i.isRed = 0, - it.actOutNum ,actOutNum) as actNum
			from
				oa_stock_outstock i
					inner join
				oa_stock_outstock_item it
					on i.id = it.mainId
			where docStatus = 'YSH' and date_format(i.auditDate,'%Y%m') = date_format("$thisDate",'%Y%m') and i.auditDate <= "$thisDate"

			union all

			select
				it.importStockId as stockId,it.productId,it.allocatNum as actNum
			from
				oa_stock_allocation i
					inner join
				oa_stock_allocation_item it
					on i.id = it.mainId
			where docStatus = 'YSH' and date_format(i.auditDate,'%Y%m') = date_format("$thisDate",'%Y%m') and i.auditDate <= "$thisDate"

			union all

			select
				it.exportStockId as stockId,it.productId,- it.allocatNum as actNum
			from
				oa_stock_allocation i
					inner join
				oa_stock_allocation_item it
					on i.id = it.mainId
			where docStatus = 'YSH' and date_format(i.auditDate,'%Y%m') = date_format("$thisDate",'%Y%m') and i.auditDate <= "$thisDate"
		) ii
		group by ii.stockId,ii.productId
	) i on c.productId = i.productId and c.stockId = i.stockId
	left join
	(
		select
			max(i.auditDate) as lasAuditDate,it.productId,it.inStockId
		from
			oa_stock_instock i
				inner join
			oa_stock_instock_item it
				on i.id = it.mainId
		where i.docStatus = 'YSH' and i.isRed = 0
		group by it.productId,it.inStockId
	) ot on c.productId = ot.productId and c.stockId = ot.inStockId
where date_format(c.thisDate,'%Y%m') = date_format("$thisDate",'%Y%m') $condition
group by c.stockId,c.productId
order by c.balanceAmount + if(i.actNum is null,0,i.actNum*c.price) desc
QuerySQL;
GenAttrXmlData($sql,false);