<?php
include '../../../../webreport/data/mysql_GenXmlData.php';

//订单条件
$purchaseCondition = "";
//全部条件
$allCondition = "";
//开始日期
$beginDate = $_GET['beginYear'] . "-" . $_GET['beginMonth'] . "-1";
$purchaseCondition .= " and date_format(c.createTime,'%Y%m%d') >= date_format('$beginDate','%Y%m%d')";

//结束日期
$endYearMonthNum = cal_days_in_month ( CAL_GREGORIAN, $_GET['endMonth'], $_GET['endYear'] );
$endDate = $_GET['endYear'] . "-" . $_GET['endMonth'] . "-" . $endYearMonthNum;
$purchaseCondition .= " and date_format(c.createTime,'%Y%m%d') <= date_format('$endDate','%Y%m%d')";

//采购员
if(!empty($_GET['sendName'])){
	$purchaseCondition .= " and c.sendName = '".$_GET['sendName']."'";
}

//单据编号
if(!empty($_GET['hwapplyNumb'])){
	$purchaseCondition .= " and c.hwapplyNumb like '%".$_GET['hwapplyNumb']."%'";
}

//供应商
if(!empty($_GET['suppId'])){
	$purchaseCondition .= " and c.suppId = ".$_GET['suppId']."";
}

//物料信息
if(!empty($_GET['productId'])){
	$purchaseCondition .= " and p.productId = ".$_GET['productId']."";
}

//echo $purchaseCondition;
$QuerySQL = <<<QuerySQL
select
	c.id ,c.hwapplyNumb ,c.orderTime,c.orderYearMonth,c.orderYear,
	c.suppId ,c.suppName ,
	c.Pid,c.productId,c.productName,c.productNumb,c.applyPrice,c.sendName,
	p.expand1,p.applyed,
	c.dateHope,
	s.deliveryNum,
	s.deliveryNum * c.applyPrice as needDeliveryMoney,
	if(rt.payedMoney is null,0,rt.payedMoney) as actDeliveryMoney
from
	(select c.id ,c.hwapplyNumb ,date_format(c.createTime,'%Y-%m-%d') as orderTime,date_format(c.createTime,'%Y%m') as orderYearMonth,date_format(c.createTime,'%Y') as orderYear,
		c.state ,c.suppId ,c.suppName ,c.allMoney,c.sendName,
		p.id as Pid,p.productId,p.productName,p.productNumb,p.pattem,p.price,
		p.amountIssued,
		p.moneyAll,p.amountAll,p.applyPrice,c.dateHope
	from
		oa_purch_apply_basic c inner join oa_purch_apply_equ p on c.id=p.basicId
	where
		c.isTemp =0 and c.state in (4,7) and c.ExaStatus = '完成' $purchaseCondition
	) c
	inner join
	(
		select
			c.id,sum(if(c.payFor = 'FKLX-03',-d.money,d.money)) as applyed,d.objId,d.objType,d.expand1
		from
			oa_finance_payablesapply c inner join oa_finance_payablesapply_detail d on c.id = d.payapplyId where c.status = 'FKSQD-03' and d.objType = 'YFRK-01'
		group by d.objId,d.expand1
	) p on if(p.expand1 = '',c.id = p.objId,c.Pid = p.expand1)
	inner join
	(
		select e.contractId,sum(e.factNum ) as deliveryNum from oa_purchase_delivered i inner join
		(
			select e.basicId,a.contractId,e.factNum from oa_purchase_delivered_equ e inner join oa_purchase_arrival_equ a on e.businessId = a.id
		) e
		on i.id = e.basicId where i.state =2 group by i.sourceId,e.contractId
	) s on c.Pid = s.contractId
	left join
	(
		select
			p.objId,p.expand1,sum(p.money) as payedMoney
		from oa_finance_payables i inner join oa_finance_payables_detail p on i.id = p.advancesId where i.formType = 'CWYF-03' and p.objId <> 0 and p.objType = 'YFRK-01' group by p.objId,p.expand1
	) rt on if(rt.expand1 = '',c.id = rt.objId,c.Pid = rt.expand1)
	where 1=1 $allCondition
QuerySQL;
GenAttrXmlData($QuerySQL, false);
?>
