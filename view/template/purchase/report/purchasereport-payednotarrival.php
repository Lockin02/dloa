<?php
include '../../../../webreport/data/mysql_GenXmlData.php';

//��������
$purchaseCondition = "";
//ȫ������
$allCondition = "";
//��ʼ����
//$beginDate = $_GET['beginYear'] . "-" . $_GET['beginMonth'] . "-1";
//$purchaseCondition .= " and date_format(c.createTime,'%Y%m%d') >= date_format('$beginDate','%Y%m%d')";

//��������
//$endYearMonthNum = cal_days_in_month ( CAL_GREGORIAN, $_GET['endMonth'], $_GET['endYear'] );
//$endDate = $_GET['endYear'] . "-" . $_GET['endMonth'] . "-" . $endYearMonthNum;
//$purchaseCondition .= " and date_format(c.createTime,'%Y%m%d') <= date_format('$endDate','%Y%m%d')";

//�ɹ�Ա
if(!empty($_GET['sendName'])){
	$purchaseCondition .= " and c.sendName = '".$_GET['sendName']."'";
}

//���ݱ��
if(!empty($_GET['hwapplyNumb'])){
	$purchaseCondition .= " and c.hwapplyNumb like '%".$_GET['hwapplyNumb']."%'";
}

//��Ӧ��
if(!empty($_GET['suppId'])){
	$purchaseCondition .= " and c.suppId = ".$_GET['suppId']."";
}

//������Ϣ
if(!empty($_GET['productId'])){
	$purchaseCondition .= " and p.productId = ".$_GET['productId']."";
}

//�ɹ���;
if(!empty($_GET['purchTypeCN'])){
	$allCondition .= " and c.purchTypeCN like '%".$_GET['purchTypeCN']."%'";
}

//echo $purchaseCondition;
$QuerySQL = <<<QuerySQL
select
	c.id ,c.hwapplyNumb ,c.orderTime,c.orderYearMonth,c.orderYear,
	c.state ,c.suppId ,c.suppName ,c.allMoney,
	c.Pid,c.productId,c.productName,c.productNumb,c.pattem,c.price,c.noTaxMoney,c.taxRate,c.units,
	c.amountIssued,c.amountDiff,c.moneyDiff,c.sendName,
	c.moneyAll,c.amountAll,c.applyPrice,c.purchTypeCN,
	p.expand1,p.applyed,p.applyed/c.moneyAll*c.amountAll as payNum,
	c.dateHope,
	s.arrivalDate
from
	(select c.id ,c.hwapplyNumb ,date_format(c.createTime,'%Y-%m-%d') as orderTime,date_format(c.createTime,'%Y%m') as orderYearMonth,date_format(c.createTime,'%Y') as orderYear,
		c.state ,c.suppId ,c.suppName ,c.allMoney,c.sendName,
		p.id as Pid,p.productId,p.productName,p.productNumb,p.pattem,p.price,cast((p.price*p.amountAll) as decimal(20,6)) as noTaxMoney,p.taxRate,p.units,
		p.amountIssued,p.amountAll - p.amountIssued as amountDiff,round((p.amountAll - p.amountIssued) * p.applyPrice,2) as moneyDiff,
		p.moneyAll,p.amountAll,p.applyPrice,c.dateHope,p.purchType,
		case p.purchType
		when 'oa_sale_order'  then '���ۺ�ͬ�ɹ�'
		when 'oa_sale_lease'  then '���޺�ͬ�ɹ�'
		when 'oa_sale_service'  then '�����ͬ�ɹ�'
		when 'oa_sale_rdproject'  then '�з���ͬ�ɹ�'
		when 'oa_borrow_borrow'  then '����ɹ�'
		when 'oa_present_present'  then '����ɹ�'
		when 'HTLX-XSHT'  then '���ۺ�ͬ�ɹ�'
		when 'HTLX-ZLHT'  then '���޺�ͬ�ɹ�'
		when 'HTLX-FWHT'  then '�����ͬ�ɹ�'
		when 'HTLX-YFHT'  then '�з���ͬ�ɹ�'
		when 'stock'  then '����ɹ�'
		when 'assets'  then '�ʲ��ɹ�'
		when 'rdproject'  then '�з��ɹ�'
		when 'produce'  then '�����ɹ�'
		when 'oa_asset_purchase_apply'  then '�ʲ��ɹ�'
		else '' end
		as purchTypeCN
	from
		oa_purch_apply_basic c inner join oa_purch_apply_equ p on c.id=p.basicId
	where
		c.isTemp =0 and c.state in (4,7) and c.ExaStatus = '���' and c.dateHope < now() and ( p.purchType <> 'assets' and p.purchType <> 'oa_asset_purchase_apply'  ) $purchaseCondition
	) c
	inner join
	(
		select
			c.id,sum(if(c.payFor = 'FKLX-03',-d.money,d.money)) as applyed,d.objId,d.objType,d.expand1
		from
			oa_finance_payablesapply c inner join oa_finance_payablesapply_detail d on c.id = d.payapplyId where c.status = 'FKSQD-03' and d.objType = 'YFRK-01'
		group by d.objId,d.expand1
	) p on if(p.expand1 = '',c.id = p.objId,c.Pid = p.expand1)
	left join
	(
		select max(e.arrivalDate) as arrivalDate,e.contractId from oa_purchase_arrival_info i inner join oa_purchase_arrival_equ e on i.id = e.arrivalId where i.state =2 group by i.purchaseId,e.contractId
	) s on c.Pid = s.contractId
where 1=1 and if(s.arrivalDate is null,1=1,c.dateHope < s.arrivalDate) $allCondition
HAVING amountDiff <> 0 and payNum > c.amountIssued
QuerySQL;
GenAttrXmlData($QuerySQL, false);
?>
