<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
$condition = '';
if(!empty($_GET['formDateBegin'])){
	$condition .= ' and c.formDate >= "'.$_GET['formDateBegin'].'" ';
}
if(!empty($_GET['formDateEnd'])){
	$condition .= ' and c.formDate <= "'.$_GET['formDateEnd'].'" ';
}
if(!empty($_GET['supplierName'])){
	$condition .= ' and c.supplierName like "%'.$_GET['supplierName'].'%" ';
}
if(!empty($_GET['invoiceNo'])){
	$condition .= ' and c.invoiceNo = "'.$_GET['invoiceNo'].'" ';
}
if(!empty($_GET['salesmanId'])){
	$condition .= ' and c.salesmanId = "'.$_GET['salesmanId'].'" ';
}
if(!empty($_GET['exaManId'])){
	$condition .= ' and c.exaManId = "'.$_GET['exaManId'].'" ';
}
if(!empty($_GET['invType'])){
	$condition .= ' and c.invType = "'.$_GET['invType'].'" ';
}
if(!empty($_GET['formType'])){
	$condition .= ' and c.formType = "'.$_GET['formType'].'" ';
}
if(!empty($_GET['productName'])){
	$condition .= ' and det.productName like "%'.$_GET['productName'].'%" ';
}
if(!empty($_GET['ExaStatus'])){
	$condition .= ' and c.ExaStatus = "'.$_GET['ExaStatus'].'" ';
}
//echo $condition;
$QuerySQL = <<<QuerySQL
select
    c.id ,c.invoiceCode ,c.invoiceNo ,c.formDate ,c.formNumber,c.supplierName ,c.supplierId ,
    if(c.ExaStatus = 1,'ÒÑÉóºË','Î´ÉóºË') as ExaStatusCN,
    IF(c.isRed = 0, c.formAssessment, -c.formAssessment) AS formAssessment,
    IF(c.isRed = 0, c.formCount, -c.formCount) AS formCount,
    IF(c.isRed = 0, c.amount, -c.amount) AS amount,
    c.address ,c.payDate ,c.bank ,c.isRed ,c.taxRate ,c.hookMoney ,
    c.subjects ,c.currency ,c.sourceType ,c.menuNo ,c.excRate ,
    c.headId ,c.head ,c.departmentsId ,c.departments ,c.acountId ,c.acount ,c.salesmanId ,c.salesman ,
    c.ExaStatus ,c.ExaDT ,c.exaMan ,c.exaManId ,c.status ,c.belongId ,c.remark ,c.createId ,c.createName ,
    c.createTime ,c.updateId ,c.updateName ,c.updateTime,
    d.dataName as invType,
    det.productName,det.number,det.price,det.taxPrice,det.allCount,det.assessment,det.objCode as sourceCode
from
    oa_finance_invother c
    left join ( select  dataCode,dataName from oa_system_datadict where parentCode = 'FPLX') d on c.invType = d.dataCode
    left join oa_finance_invother_detail det on c.id = det.mainId
where 1=1
$condition
order by c.formDate desc
QuerySQL;
GenAttrXmlData($QuerySQL, false);