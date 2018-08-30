<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
$condition = " ";
extract($_GET);
if(!empty($projectCode)){
	$condition.=" and c.projectCode like CONCAT('%','".$projectCode."','%')";
}

if(!empty($projectName)){
	$condition.=" and c.projectName like CONCAT('%','".$projectName."','%')";
}

if(!empty($orderCode)){
	$condition.=" and c.orderCode like CONCAT('%','".$orderCode."','%')";
}

if(!empty($signCompanyName)){
	$condition.=" and c.signCompanyName like CONCAT('%','".$signCompanyName."','%')";
}

if(!empty($nature)){
	$condition.=" and e.nature ='".$nature."'";
}

if(!empty($itemStatus)){
	$condition.=" and e.status ='".$itemStatus."'";
}
//echo $condition;
$QuerySQL = <<<QuerySQL
select c.projectCode ,c.projectName ,c.orderCode ,c.signCompanyName ,c.outsourcingName ,c.beginDate ,c.endDate
,case e.status
	when 'GCXMZT01' then '筹备'
	when 'GCXMZT02' then '在建'
	when 'GCXMZT03' then '关闭'
	when 'GCXMZT00' then '未启动'
	else '' end
as itemStatus
,case e.nature
	when 'GCYHL' then '优化类'
	when 'GCPGL' then '评估类'
	when 'GCDWL' then 'Fleet代维类'
	else '' end
as nature
,CONCAT(e.projectProcess,'%') as projectProcess
,FORMAT(e.contractMoney ,2) as contractMoney
,FORMAT(c.orderMoney ,2) as orderMoney
,FORMAT(if(pp.money is null,0,pp.money) + c.initPayMoney ,2) as payedMoney
,FORMAT((e.projectProcess * c.orderMoney)/100 - (if(pp.money is null,0,pp.money) + c.initPayMoney) ,2) as noPaydMoney
,FORMAT((e.projectProcess * c.orderMoney)/100 ,2) as confirmWorkMoney
,FORMAT(c.orderMoney - (if(pp.money is null,0,pp.money) + c.initPayMoney) ,2) as noPaydOutMoney
,c.id ,c.status ,c.ExaStatus
from
	oa_sale_outsourcing c
	left join
	(
		select p.objId,sum(p.money) as money from oa_finance_payablesapply_detail p left join oa_finance_payablesapply pa on p.payapplyId = pa.id where pa.ExaStatus != '打回' and pa.status not in('FKSQD-04','FKSQD-05') and p.objType = 'YFRK-03' group by p.objId,p.objType
	) p on c.id = p.objId
	left join
	(
		select p.objId,sum(if(pa.formType = 'CWYF-03',-p.money,p.money)) as money from oa_finance_payables_detail p left join oa_finance_payables pa on p.advancesId = pa.id where p.objType = 'YFRK-03' group by p.objId,p.objType
	) pp on c.id = pp.objId
	left join
	(
		select d.objId,
		    sum(if(i.isRed = 0,if(d.allCount = 0,d.amount ,d.allCount ),if(d.allCount = 0,-d.amount ,-d.allCount ))) as allCount
		from oa_finance_invother i left join oa_finance_invother_detail d on i.id = d.mainId
		where d.objId <> 0 and d.objType = 'YFQTYD01' group by  d.objId,d.objType
	)d on c.id = d.objId
	left join oa_esm_project e on c.projectCode=e.projectCode
where c.isTemp = 0 and c.outsourceTypeName = '工程项目' $condition
 group by c.id
 order by c.id
DESC
QuerySQL;
//echo $QuerySQL;
GenAttrXmlData ( $QuerySQL, false );