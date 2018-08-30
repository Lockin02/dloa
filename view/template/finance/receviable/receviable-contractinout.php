<?php
include '../../../../webreport/data/mysql_GenXmlData.php';

$beginYearMonthNum = cal_days_in_month ( CAL_GREGORIAN, $_GET['beginMonth'], $_GET['beginYear'] ); //这个月有多少天
$monthBeginDate = $_GET['beginYear'] . "-" . $_GET['beginMonth'] . "-1";//月开始日期
$endYearMonthNum = cal_days_in_month ( CAL_GREGORIAN, $_GET['endMonth'], $_GET['endYear'] ); //这个月有多少天
$monthEndDate = $_GET['endYear'] . "-" . $_GET['endMonth'] . "-" . $endYearMonthNum. " 23:59:59";//月结束日期
$condition .= " and c.createTime between '".$monthBeginDate ."' and '" . $monthEndDate ."'";

//财务期设置
$thisPeriodDate = $_GET['periodYear'] .'-'. $_GET['periodMonth'] . '-01';


if(!empty($_GET['orderId'])){
	$condition .= ' and c.id="'.$_GET['orderId'].'" ';
}
if(!empty($_GET['customerId'])){
	$condition .= ' and c.customerId='.$_GET['customerId'].' ';
}

$QuerySQL = <<<QuerySQL
select
	year(c.createTime) as thisYear,month(c.createTime) as thisMonth,c.id as orgId,c.contractCode as orderCode,c.contractMoney,c.customerName,ii.invoiceMoney,
	ii.psType,fi.invoiceMoney as thisInvoiceMoney,fi.softMoney,fi.hardMoney,fi.serviceMoney,fi.repairMoney,
	cf.thisCarryMoney,cf.beforeCarryMoney,cf.allCarryMoney
from
	oa_contract_contract c
	left join financeview_is_03_sumorder ii on c.id = ii.objId and 'KPRK-12' = ii.orderObjType
	left join
		( select
			sum(invoiceMoney) as invoiceMoney,sum(softMoney) as softMoney,sum(hardMoney) as hardMoney,sum(serviceMoney) as serviceMoney,sum(repairMoney) as repairMoney,objId,objType
		from
			oa_finance_invoice
		where
			DATE_FORMAT(invoiceTime,'%Y%m') = DATE_FORMAT('$thisPeriodDate','%Y%m') group by objId,objType
		) fi
	on c.id = fi.objId  and 'KPRK-12' = fi.objType
	left join
		(select
			c.saleId ,c.saleCode ,c.saleType ,
			sum(if(DATE_FORMAT(c.thisDate,'%Y%m') = DATE_FORMAT('$thisPeriodDate','%Y%m'),round( i.subCost * carryRate /100 ,2),0)) as thisCarryMoney ,
			sum(if(DATE_FORMAT(c.thisDate,'%Y%m') < DATE_FORMAT('$thisPeriodDate','%Y%m'),round( i.subCost * carryRate /100 ,2),0)) as beforeCarryMoney ,
			sum(round(( i.subCost * carryRate /100 ),2)) as allCarryMoney
		from
			oa_finance_carriedforward c left join
			(
			select
			        i.subCost,
				i.id ,c.docCode ,c.docType ,c.isRed ,c.contractId ,c.contractName ,c.contractCode ,c.contractType,c.customerName
				from oa_stock_outstock c left join oa_stock_outstock_item i on c.id = i.mainId where 1=1 and c.docStatus = 'YSH' and c.docType = 'CKSALES'
			) i on c.outStockDetailId = i.id where 1=1
		group by
			c.saleId,c.saleType

		) cf
	on c.id = cf.saleId and  'KPRK-12' =cf.saleType
where c.isTemp = 0  and c.ExaStatus in ('完成' ,'变更审批中') $condition
order by c.createTime

QuerySQL;
GenAttrXmlData($QuerySQL, false);
?>
