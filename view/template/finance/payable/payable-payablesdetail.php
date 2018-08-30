<?php
include '../../../../webreport/data/mysql_GenXmlData.php';

$beginYearMonthNum = cal_days_in_month ( CAL_GREGORIAN, $_GET['beginMonth'], $_GET['beginYear'] ); //这个月有多少天
$monthBeginDate = $_GET['beginYear'] . "-" . $_GET['beginMonth'] . "-1";//月开始日期
$endYearMonthNum = cal_days_in_month ( CAL_GREGORIAN, $_GET['endMonth'], $_GET['endYear'] ); //这个月有多少天
$monthEndDate = $_GET['endYear'] . "-" . $_GET['endMonth'] . "-" . $endYearMonthNum. " 23:59:59";//月结束日期
$condition .= " and c.formDate between '".$monthBeginDate ."' and '" . $monthEndDate ."'";

if(!empty($_GET['supplierName'])){
	$condition .= ' and c.supplierName like "%'.$_GET['supplierName'].'%" ';
}
if(!empty($_GET['objCode'])){
	$condition .= ' and d.objCode like "%'.$_GET['objCode'].'%" ';
}
if(!empty($_GET['objType'])){
	$condition .= ' and d.objType="'.$_GET['objType'].'" ';
}
if(!empty($_GET['salesmanId'])){
	$condition .= ' and c.salesmanId="'.$_GET['salesmanId'].'" ';
}
if(!empty($_GET['deptId'])){
	$condition .= ' and c.deptId='.$_GET['deptId'].' ';
}
if(!empty($_GET['payContent'])){
	$condition .= ' and d.payContent like"%'.$_GET['payContent'].'%" ';
}
if(!empty($_GET['amount'])){
	$condition .= ' and C.amount = '.$_GET['amount'];
}

$QuerySQL = <<<QuerySQL
select
	c.id ,c.payApplyId ,c.payApplyNo ,c.formNo ,c.formDate ,c.financeDate ,c.supplierName ,c.supplierId ,pt.dataName as payType ,c.remark,
	c.payNo ,c.bank ,c.certificate ,c.amount ,c.deptId ,c.deptName ,c.salesman ,if(c.status = 1,'已退款','正常') as status ,c.ExaStatus ,c.ExaDT ,
	c.createName ,c.createTime ,c.updateTime,c.belongId,d.objCode,d.objId,d.money,d.objType,d.payContent,if(d.objTypeOrg <> 'YFRK-01',d.expand1,'') as expand1,
	if(d.objTypeOrg <> 'YFRK-01',d.expand2,'') as expand2
from oa_finance_payables c
	left join (
		select
				c.id ,c.advancesId ,c.money ,c.cashsubject ,c.objId ,c.objCode ,d.dataName as objType,c.objType as objTypeOrg,
				c.orgFormType ,c.orgFormNo,c.payContent,c.expand1,c.expand2,c.expand3
		from
			oa_finance_payables_detail c
				left join
			oa_system_datadict d on c.objType = d.dataCode where d.parentCode = 'YFRK'
		) d	on c.id = d.advancesId
	left join (select dataName,dataCode from oa_system_datadict where parentCode = 'CWFKFS') pt on c.payType = pt.dataCode

where 1=1 and c.formType = 'CWYF-01' $condition
order by c.formDate,c.id asc

QuerySQL;
GenAttrXmlData($QuerySQL, false);
?>
