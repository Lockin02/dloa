<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
?>
<?php
$QuerySQL = <<<QuerySQL
select soi.productCode,soi.productName,soi.pattern,soi.unitName,
		soi.inStockCode as stockCode,soi.inStockName as stockName,
		sum(soi.actNum) as iproductNum,
		soi.price as iprice,
		soi.subPrice as icost,
		sum(soo.actOutNum) as oproductNum,
		soo.cost as oprice,
		soo.subCost as ocost
	from oa_stock_instock_item soi,oa_stock_outstock_item soo 
		where soo.productId=soi.productId  and soo.stockId=soi.inStockId  group by soi.productCode,soi.inStockId 
QuerySQL;
GenAttrXmlData($QuerySQL, false);
?>
