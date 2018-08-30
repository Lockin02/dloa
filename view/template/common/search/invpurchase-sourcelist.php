<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
$condition = '';
if($_GET['ids']){
	$condition .= ' and i.id in(' .$_GET['ids'] . ')' ;
	unset($_GET['sourceType']);
}
if($_GET['objId']){
	$condition .= ' and d.objId in(' .$_GET['objId'] . ')' ;
}
if($_GET['sourceType']){
	$condition .= ' and d.objType = "'.$_GET['sourceType'].'"';
}
$QuerySQL = <<<QuerySQL
select
	c.id ,c.objCode ,c.objNo ,c.payDate ,c.supplierName ,c.invType,c.exaMan,c.ExaDT,c.createName ,c.createTime,c.status,
	c.ExaStatus,c.belongId,c.departments,c.purcontCode,c.formDate,c.salesman,c.formType,
	c.taxRate,c.formAssessment,c.formCount,
	if(c.formType = 'blue',d.hookNumber,-d.hookNumber) as hookNumber,
	if(c.formType = 'blue',d.hookAmount,-d.hookAmount) as hookAmount,
	if(c.formType = 'blue',d.unHookNumber,-d.unHookNumber) as unHookNumber,
	if(c.formType = 'blue',d.unHookAmount,-d.unHookAmount) as unHookAmount,
	if(c.formType = 'blue',d.amount,-d.amount) as amount,
	if(c.formType = 'blue',d.number,-d.number) as number,
	if(c.formType = 'blue',d.assessment,-d.assessment) as assessment,
	if(c.formType = 'blue',d.allCount,-d.allCount) as allCount,
	d.id as detailId,d.productName,d.productNo,d.productId,d.price,d.taxPrice,
	d.objId,d.contractCode,d.contractId,d.objId as sourceId,d.objCode as sourceCode,d.objType as sourceType

from
	oa_finance_invpurchase c left join oa_finance_invpurchase_detail d on c.id = d.invPurId where 1=1 $condition

	order by date_format(c.formDate,'%Y%m'),c.supplierId
QuerySQL;
GenAttrXmlData($QuerySQL, false);
?>
