<?php
include '../../../../webreport/data/mysql_GenXmlData.php';

//订单条件
$purchaseCondition = "";
//全部条件
$allCondition = "";
//开始日期
//$beginDate = $_GET['beginYear'] . "-" . $_GET['beginMonth'] . "-1";
//$purchaseCondition .= " and date_format(c.createTime,'%Y%m%d') >= date_format('$beginDate','%Y%m%d')";

//结束日期
//$endYearMonthNum = cal_days_in_month ( CAL_GREGORIAN, $_GET['endMonth'], $_GET['endYear'] );
//$endDate = $_GET['endYear'] . "-" . $_GET['endMonth'] . "-" . $endYearMonthNum;
//$purchaseCondition .= " and date_format(c.createTime,'%Y%m%d') <= date_format('$endDate','%Y%m%d')";

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

//采购用途
if(!empty($_GET['purchTypeCN'])){
	$allCondition .= " and c.purchTypeCN like '%".$_GET['purchTypeCN']."%'";
}

//echo $purchaseCondition;
$QuerySQL = <<<QuerySQL
select
	c.id ,c.hwapplyNumb ,c.orderTime,
	c.orderYearMonth,c.orderYear,
	c.suppName ,c.allMoney,c.sendName,
	c.Pid,c.productId,c.productName,c.productNumb,
	c.price,
	c.noTaxMoney,
	c.amountAll,
	c.applyPrice,
	c.moneyAll,
	c.purchType,
	c.purchTypeCN,
	c.amountIssued,
	p.applyed,
	if(i.allCount is null,0,i.allCount) as allCount,
	p.applyed - if(i.allCount is null,0,i.allCount) as moneyDiff
from
	(
		select
			c.id ,c.hwapplyNumb ,date_format(c.createTime,'%Y-%m-%d') as orderTime,
			date_format(c.createTime,'%Y%m') as orderYearMonth,date_format(c.createTime,'%Y') as orderYear,
			c.suppName ,c.allMoney,c.sendName,
			p.id as Pid,p.productId,p.productName,p.productNumb,p.amountIssued,
			p.price,
			cast((p.price*p.amountAll) as decimal(20,6)) as noTaxMoney,
			p.amountAll,
			p.applyPrice,
			p.moneyAll,
			p.purchType,
			case p.purchType
			when 'oa_sale_order'  then '销售合同采购'
			when 'oa_sale_lease'  then '租赁合同采购'
			when 'oa_sale_service'  then '服务合同采购'
			when 'oa_sale_rdproject'  then '研发合同采购'
			when 'oa_borrow_borrow'  then '补库采购'
			when 'oa_present_present'  then '补库采购'
			when 'HTLX-XSHT'  then '销售合同采购'
			when 'HTLX-ZLHT'  then '租赁合同采购'
			when 'HTLX-FWHT'  then '服务合同采购'
			when 'HTLX-YFHT'  then '研发合同采购'
			when 'stock'  then '补库采购'
			when 'assets'  then '资产采购'
			when 'rdproject'  then '研发采购'
			when 'produce'  then '生产采购'
			when 'oa_asset_purchase_apply'  then '资产采购'
			else '' end
			as purchTypeCN
		from
			oa_purch_apply_basic c inner join oa_purch_apply_equ p on c.id=p.basicId
		where
			c.isTemp =0 and c.state in (4,7) and c.ExaStatus = '完成' and ( p.purchType <> 'assets' and p.purchType <> 'oa_asset_purchase_apply'  ) $purchaseCondition
	) c
	inner join
	(
		select
			c.id,sum(if(c.payFor = 'FKLX-03',-d.money,d.money)) as applyed,d.objId,d.expand1
		from
			oa_finance_payablesapply c inner join oa_finance_payablesapply_detail d on c.id = d.payapplyId where c.status = 'FKSQD-03' and d.objType = 'YFRK-01'
		group by d.objId,d.expand1
	) p on if(p.expand1 = '',c.id = p.objId,c.Pid = p.expand1)
	left join
	(
		select it.contractId,iv.allCount from
		(
		select
					d.expand1,sum(d.allCount) as allCount
				from
				oa_finance_invpurchase_detail d
				where d.contractCode <> '' and d.expand1 <> '' and d.expand1 is not null GROUP BY d.expand1
		) iv inner join
		(
			select it.id,e.contractId from oa_purchase_arrival_equ e inner join oa_stock_instock_item it on e.id = it.relDocId
		) it on iv.expand1 = it.id
	) i on c.Pid = i.contractId
where 1=1 $allCondition
having moneyDiff > 0
QuerySQL;
GenAttrXmlData($QuerySQL, false);
?>
