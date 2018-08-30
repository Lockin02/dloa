<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
?>
<?php
//总条件
$condition = "";
//合同条件
$contractCondition = "";

//到款条件
$incomeCondition = "";
if(!empty($_GET['beginYear'])){
	$beginYearMonthNum = cal_days_in_month ( CAL_GREGORIAN, $_GET['beginMonth'], $_GET['beginYear'] ); //这个月有多少天
	$beginDate = $_GET['beginYear'] . "-" . $_GET['beginMonth'] . "-1";//月开始日期

	$endYearMonthNum = cal_days_in_month ( CAL_GREGORIAN, $_GET['endMonth'], $_GET['endYear'] ); //这个月有多少天
	$endDate = $_GET['endYear'] . "-" . $_GET['endMonth'] . "-" . $endYearMonthNum;//月结束日期

	$contractCondition .= " and c.createTime between '".$beginDate ."' and '" . $endDate ."'";
}
if(!empty($_GET['areaName'])){
	$contractCondition .= ' and c.areaName="'.$_GET['areaName'].'" ';
	$incomeCondition .= ' and i.areaName="'.$_GET['areaName'].'" ';
}
if(!empty($_GET['customerName'])){
	$contractCondition .= " and c.customerName like concat('%','".$_GET['customerName']."','%') ";
	$incomeCondition .= " and i.customerName like concat('%','".$_GET['customerName']."','%')";
}
if(!empty($_GET['customerType'])){
	$contractCondition .= ' and c.customerType="'.$_GET['customerType'].'" ';
	$incomeCondition .= ' and i.incomeUnitType="'.$_GET['customerType'].'" ';
}
if(!empty($_GET['orderCode'])){
	$contractCondition .=  " and c.contractCode like concat('%','".$_GET['orderCode']."','%') ";
	$incomeCondition .=  " and 1=0 ";
}
if(!empty($_GET['prinvipalId'])){
	$contractCondition .= ' and c.prinvipalId="'.$_GET['prinvipalId'].'" ';
	$incomeCondition .=  " and 1=0 ";
}
if(!empty($_GET['areaPrincipalId'])){
	$contractCondition .= ' and c.areaPrincipalId="'.$_GET['areaPrincipalId'].'" ';
	$incomeCondition .= ' and i.managerId="'.$_GET['areaPrincipalId'].'" ';
}
if(!empty($_GET['customerProvince'])){
	$contractCondition .= ' and c.contractProvince="'.$_GET['customerProvince'].'" ';
	$incomeCondition .= ' and i.province="'.$_GET['customerProvince'].'" ';
}
if(!empty($_GET['incomeMoney'])){
	$contractCondition .= ' and c.incomeMoney="'.$_GET['incomeMoney'].'" ';
	$condition .=  ' and c.incomeMoney="'.$_GET['incomeMoney'].'" ';
}
if(!empty($_GET['invoiceMoney'])){
	$contractCondition .= ' and c.invoiceMoney="'.$_GET['invoiceMoney'].'" ';
	$incomeCondition .=  " and 1=0 ";
}
$QuerySQL = <<<QuerySQL
select
*
from
(
	select
		date_format(c.createTime,'%Y%m%d') as thisDate,
		c.contractCode AS thisOrderCode,
		c.contractTypeName as thisOrderType,
		c.contractProvince as prov,
		c.customerTypeName as customerTypeName,
		c.contractName,
		c.prinvipalId,
		c.prinvipalName,
		c.areaName,
		c.areaPrincipal,
		c.areaPrincipalId,
		year(c.createTime) as thisYear,
		month(c.createTime) as thisMonth,
		c.customerId,
		c.customerName,
		c.customerType,
		if(c.contractMoney > 0,c.contractMoney,c.contractTempMoney) AS thisOrderMoney,
		if(c.invoiceMoney is null,0,c.invoiceMoney) as invoiceMoney,
		if(c.incomeMoney is null,0,c.incomeMoney) as incomeMoney,
		c.invoiceMoney - c.incomeMoney as unReceviceMoney,
		if(c.contractMoney > 0,c.contractMoney,c.contractTempMoney) - c.incomeMoney as unIncomeMoney,
		if(c.contractMoney > 0,c.contractMoney,c.contractTempMoney) - c.invoiceMoney as unInvoiceMoney,

		p.conProducts as detail,

		i.psType,
		i.invoiceType,
		i.incomeDates,
		i.invoiceDates

	from
		oa_contract_contract c
		inner join
		(select contractId,group_concat(p.conProductName) as conProducts from oa_contract_product p group by contractId) p
			on c.id = p.contractId
		left join
		financeview_is_03_sumorder i
			on i.objId = c.id and i.objType = 'KPRK-12'
	where c.ExaStatus in('完成','变更审批中') and c.isTemp = 0 $contractCondition

	union

	select
		date_format(i.incomeDate,'%Y%m%d') as thisDate,
		null AS thisOrderCode,
		null as thisOrderType,
		i.province as prov,
		d.dataName as customerTypeName,
		null as contractName,
		null as prinvipalId,
		null as prinvipalName,
		i.areaName,
		i.managerName as areaPrincipal,
		i.managerId as areaPrincipalId,
		year(i.incomeDate) as thisYear,
		month(i.incomeDate) as thisMonth,
		i.customerId as customerId,
		i.customerName as customerName,
		i.incomeUnitType as customerType,
		0 AS thisOrderMoney,
		0 as invoiceMoney,
		sum(i.incomeMoney) as incomeMoney,
		0 unReceviceMoney,
		0 as unIncomeMoney,
		0 as unInvoiceMoney,

		null as detail,

		null as psType,
		null as invoiceType,
		null as incomeDates,
		group_concat(concat(cast(i.incomeDate as char charset gbk),' , ',cast(i.incomeMoney as char charset gbk)) separator ' ; ') as incomeDates
	from
		(
		select
			i.incomeNo,i.incomeUnitId as customerId,i.incomeUnitName as customerName,i.incomeDate,i.province,
			a.objId,a.objType,a.objCode,i.incomeUnitType,i.managerId,i.managerName,i.areaName,i.areaId,
			if(i.formType <> 'YFLX-TKD' ,i.incomeMoney,-i.incomeMoney) as incomeMoney
		from
			oa_finance_income i
			left join
			oa_finance_income_allot a on i.id = a.incomeId
		where i.status = 'DKZT-WFP' and date_format(i.incomeDate,'%Y%m') between date_format("$beginDate",'%Y%m') and date_format("$endDate",'%Y%m')
		union all
		select
			i.incomeNo,i.incomeUnitId as customerId,i.incomeUnitName as customerName,i.incomeDate,i.province,
			null as objId,null as objType,null as objCode,i.incomeUnitType,i.managerId,i.managerName,i.areaName,i.areaId,
			if(i.formType <> 'YFLX-TKD' ,i.allotAble,-i.allotAble) as incomeMoney
		from
			oa_finance_income i
		where i.status = 'DKZT-BFFP' and date_format(i.incomeDate,'%Y%m') between date_format("$beginDate",'%Y%m') and date_format("$endDate",'%Y%m')
		) i
		left join
		(select dataCode,dataName from oa_system_datadict where parentCode = 'KHLX') d
			on i.incomeUnitType = d.dataCode
	where 1=1 $incomeCondition
	group by date_format(i.incomeDate,'%Y%m'),i.customerName
) c
where 1=1 $condition

order by c.thisDate,c.customerName


QuerySQL;


GenAttrXmlData($QuerySQL, false);


//SELECT
//o.id,
//o.orgid,
//if(o.orderCode <> "" and o.orderCode is not null,o.orderCode,o.orderTempCode) AS thisOrderCode,
//o.orderName,
//o.objType,
//o.prinvipalId,
//o.prinvipalName,
//o.areaName,
//o.areaPrincipal,
//o.areaPrincipalId,
//o.`sign`,
//year(o.createTime) as thisYear,
//month(o.createTime) as thisMonth,
//if(o.signDate != '0000-00-00',o.signDate,null) as signDate,
//o.detail,
//o.allProNum,
//o.customerId,
//o.customerName,
//o.customerType,
//d.dataName as customerTypeName,
//if(o.orderCode <> "" and o.orderCode is not null,o.orderMoney,o.orderTempMoney) AS thisOrderMoney,
//if( i.incomeMoney is null,0,i.incomeMoney) as incomeMoney,
//if( i.invoiceMoney is null,0,i.invoiceMoney) as invoiceMoney,
//(if(o.orderCode <> "" and o.orderCode is not null,o.orderMoney,o.orderTempMoney) - if( i.incomeMoney is null,0,i.incomeMoney)) as unIncomeMoney,
//(if(o.orderCode <> "" and o.orderCode is not null,o.orderMoney,o.orderTempMoney) - if( i.invoiceMoney is null,0,i.invoiceMoney)) as unInvoiceMoney,
//(if( i.invoiceMoney is null,0,i.invoiceMoney) - if( i.incomeMoney is null,0,i.incomeMoney)) as unReceviceMoney,
//i.psType,
//i.incomeDates,
//i.invoiceDates,
//i.invoiceType,
//userArea.thisAreaName,
//(case o.objType when 'oa_sale_order' then '销售合同' when 'oa_sale_service' then '服务合同'
//	when 'oa_sale_lease' then '租赁合同' when 'oa_sale_rdproject' then '研发合同' else '研发合同' end) as thisOrderType,
//cu.prov
//
//FROM
//financeview_is_04_order o left join customer cu on o.customerId = cu.id left join financeview_is_03_sumorder i on o.orgId = i.objId and o.objType = convert(i.orderObjType using utf8)
//left join (SELECT
//u.USER_ID,
//a.name as thisAreaName
//from user u left join area a on u.area = a.id ) userArea on o.prinvipalId = userArea.USER_ID
//left join oa_system_datadict d on o.customerType = d.dataCode where o.ExaStatus = '完成' $condition
//order by o.createTime,o.customerId
?>
