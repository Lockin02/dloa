<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
$condition = '';
if(!empty($_GET['formDateBegin'])){
	$condition .= ' and c.formDate >= "'.$_GET['formDateBegin'].'" ';
}
if(!empty($_GET['formDateEnd'])){
	$condition .= ' and c.formDate <= "'.$_GET['formDateEnd'].'" ';
}
if(!empty($_GET['supplierId'])){
	$condition .= ' and c.supplierId = '.$_GET['supplierId'].' ';
}
if(!empty($_GET['objNo'])){
	$condition .= ' and c.objNo = "'.$_GET['objNo'].'" ';
}
if(!empty($_GET['salesmanId'])){
	$condition .= ' and c.salesmanId = "'.$_GET['salesmanId'].'" ';
}
if(!empty($_GET['exaManId'])){
	$condition .= ' and c.exaManId = "'.$_GET['exaManId'].'" ';
}
if(!empty($_GET['status'])){
	$condition .= ' and c.status = '.$_GET['status'].' ';
}
if(!empty($_GET['formType'])){
	$condition .= ' and c.formType = "'.$_GET['formType'].'" ';
}
if(!empty($_GET['invType'])){
	$condition .= ' and c.invType = "'.$_GET['invType'].'" ';
}
if(!empty($_GET['formType'])){
	$condition .= ' and c.formType = "'.$_GET['formType'].'" ';
}
if(!empty($_GET['productNo'])){
	$condition .= ' and d.productNo = "'.$_GET['productNo'].'" ';
}
if(!empty($_GET['ExaStatus'])){
	$condition .= ' and c.ExaStatus = "'.$_GET['ExaStatus'].'" ';
}
//echo $condition;
$QuerySQL = <<<QuerySQL
select
	c.id ,c.objCode ,c.objNo ,c.payDate ,c.supplierName ,c.invType,c.exaMan,c.ExaDT,c.createName ,c.createTime,c.status,if(c.status = 1,'ÒÑ¹³»ü','Î´¹³»ü') as statusCN,
	if(c.ExaStatus = 1,'ÒÑÉóºË','Î´ÉóºË') as ExaStatusCN,c.ExaStatus,c.belongId,c.departments,c.purcontCode,c.formDate,c.salesman,c.formType,
	c.taxRate,c.formAssessment,c.formCount,
	if(c.formType = 'blue',d.hookNumber,-d.hookNumber) as hookNumber,
	if(c.formType = 'blue',round(d.hookAmount,2),-round(d.hookAmount,2)) as hookAmount,
	if(c.formType = 'blue',d.unHookNumber,-d.unHookNumber) as unHookNumber,
	if(c.formType = 'blue',round(d.unHookAmount,2),-round(d.unHookAmount,2)) as unHookAmount,
	if(c.formType = 'blue',round(d.amount,2),-round(d.amount,2)) as amount,
	if(c.formType = 'blue',d.number,-d.number) as number,
	d.price,
	d.taxPrice,
	if(c.formType = 'blue',round(d.assessment,2),-round(d.assessment,2)) as assessment,
	if(c.formType = 'blue',round(d.allCount,2),-round(d.allCount,2)) as allCount,
	d.id as detailId,d.productName,d.productNo,d.productId,
	d.objId,d.contractCode,d.contractId,d.objId as sourceId,d.objCode as sourceCode,d.objType as sourceType,d.unit

from
	(select
		c.id ,c.objCode ,c.objNo ,c.payDate ,c.supplierName ,c.supplierId,c.exaMan,c.exaManId,c.ExaDT,c.createName ,c.createTime,c.status,
     	c.ExaStatus,c.belongId,c.departments,c.formDate,c.salesman,c.salesmanId,c.formType,c.purcontId,c.purcontCode,c.payStatus,
		c.amount,c.taxRate,c.formAssessment,c.formCount,d.dataName as invType
	from oa_finance_invpurchase c left join oa_system_datadict d on c.invType = d.dataCode where d.parentCode = 'FPLX' ) c left join oa_finance_invpurchase_detail d on c.id = d.invPurId
	where 1=1
	$condition
	order by c.formDate desc,c.id,c.createTime,d.productId asc
QuerySQL;
GenAttrXmlData($QuerySQL, false);
?>
