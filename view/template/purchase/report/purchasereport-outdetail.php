<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
$condition = "";
//物料信息
$oeCondition = " and 1=1";
$seCondition = " and 1=1";
$leCondition = " and 1=1";
$reCondition = " and 1=1";
if (! empty ( $_GET ['productCode'] )) {
//	$condition = $_GET ['productCode'];
	$oeCondition = " and oe.productCode='".$_GET ['productCode']."'";
	$seCondition ="  and se.productCode='".$_GET ['productCode']."'";
	$leCondition = " and le.productCode='".$_GET ['productCode']."'";
	$reCondition = " and re.productCode='".$_GET ['productCode']."'";
}
//echo $condition;
$QuerySQL = <<<QuerySQL
select psub.productCode,psub.productName,ifnull(ig.actNum,0) as actNum,
		psub.contractCode,psub.number,psub.executedNum,psub.notExeNum,psub.deliveryDate,
		id.auditDate,datediff(NOW(), id.auditDate) as stopDay,id.actNum as instockNum  from (
select o.contractCode,o.deliveryDate,
  			oe.`productId` ,oe.`number`,oe.`executedNum`,
            (oe.`number`-oe.`executedNum`) as notExeNum,oe.productCode,oe.productName
  			FROM oa_contract_equ oe inner join oa_contract_contract o
  				on(oe.contractId=o.id)    where o.state='2' and oe.isTemp='0' $oeCondition
)psub left join
(
   select SUM( actNum ) as actNum,productId,productCode,productName from `oa_stock_inventory_info` i
			where i.stockId<>-1  group by productId 
)ig on(ig.productId=psub.productId)
left join (
    select max(i.`auditDate`) as auditDate,ii.productId,ii.actNum from `oa_stock_instock` i
        inner join `oa_stock_instock_item` ii on(ii.mainId=i.id)where  i.isRed='0' group by ii.`productId` 
)id on(id.productId=psub.productId) 
where ifnull(ig.actNum,0) <>0 and psub.notExeNum<>0  order BY psub.productCode,psub.contractCode
QuerySQL;
GenAttrXmlData ( $QuerySQL, false );
?>
