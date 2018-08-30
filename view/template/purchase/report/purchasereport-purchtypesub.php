<?php
include '../../../../webreport/data/mysql_GenXmlData.php';

//订单条件
$purchaseCondition = "";

$year = $_GET['thisYear'];
//锚定日期
$January = $_GET['thisYear'] . "01";
$February = $_GET['thisYear'] . "02";
$March = $_GET['thisYear'] . "03";
$April = $_GET['thisYear'] . "04";
$May = $_GET['thisYear'] . "05";
$June = $_GET['thisYear'] . "06";
$July = $_GET['thisYear'] . "07";
$August = $_GET['thisYear'] . "08";
$September = $_GET['thisYear'] . "09";
$October = $_GET['thisYear'] . "10";
$November = $_GET['thisYear'] . "11";
$December = $_GET['thisYear'] . "12";

//订单条件
$purchaseCondition = "";
//开始日期
$beginDate = $_GET['thisYear'] . "-" . $_GET['beginMonth'] . "-1";
$purchaseCondition .= " and date_format(ab.createTime,'%Y%m%d') >= date_format('$beginDate','%Y%m%d')";
if($_GET['beginMonth'] > 9){
	$beginYearMonth =  $_GET['thisYear'] . $_GET['beginMonth'];
}else{
	$beginYearMonth =  $_GET['thisYear'] ."0". $_GET['beginMonth'];
}

//结束日期
$endYearMonthNum = cal_days_in_month ( CAL_GREGORIAN, $_GET['endMonth'], $_GET['thisYear'] );
$endDate = $_GET['thisYear'] . "-" . $_GET['endMonth'] . "-" . $endYearMonthNum;
$purchaseCondition .= " and date_format(ab.createTime,'%Y%m%d') <= date_format('$endDate','%Y%m%d')";
if($_GET['endMonth'] > 9){
	$endYearMonth =  $_GET['thisYear'] . $_GET['endMonth'];
}else{
	$endYearMonth =  $_GET['thisYear'] ."0". $_GET['endMonth'];
}


//echo $purchaseCondition;
$QuerySQL = <<<QuerySQL
select
	sum(gs.moneyAll) as yearMoney,gs.purchType, round((sum(gs.moneyAll) / ml.yearAllMoney)*100,4) as moneyPercent,
	if( $January < $beginYearMonth or $January > $endYearMonth,'',sum(if(gs.thisYearMonth = $January,gs.moneyAll,0))) as month1Money,
	if( $February < $beginYearMonth or $February > $endYearMonth,'',sum(if(gs.thisYearMonth = $February,gs.moneyAll,0))) as month2Money,
	if( $March < $beginYearMonth or $March > $endYearMonth,'',sum(if(gs.thisYearMonth = $March,gs.moneyAll,0))) as month3Money,
	if( $April < $beginYearMonth or $April > $endYearMonth,'',sum(if(gs.thisYearMonth = $April,gs.moneyAll,0))) as month4Money,
	if( $May < $beginYearMonth or $May > $endYearMonth,'',sum(if(gs.thisYearMonth = $May,gs.moneyAll,0))) as month5Money,
	if( $June < $beginYearMonth or $June > $endYearMonth,'',sum(if(gs.thisYearMonth = $June,gs.moneyAll,0))) as month6Money,
	if( $July < $beginYearMonth or $July > $endYearMonth,'',sum(if(gs.thisYearMonth = $July,gs.moneyAll,0))) as month7Money,
	if( $August < $beginYearMonth or $August > $endYearMonth,'',sum(if(gs.thisYearMonth = $August,gs.moneyAll,0))) as month8Money,
	if( $September < $beginYearMonth or $September > $endYearMonth,'',sum(if(gs.thisYearMonth = $September,gs.moneyAll,0))) as month9Money,
	if( $October < $beginYearMonth or $October > $endYearMonth,'',sum(if(gs.thisYearMonth = $October,gs.moneyAll,0))) as month10Money,
	if( $November < $beginYearMonth or $November > $endYearMonth,'',sum(if(gs.thisYearMonth = $November,gs.moneyAll,0))) as month11Money,
	if( $December < $beginYearMonth or $December > $endYearMonth,'',sum(if(gs.thisYearMonth = $December,gs.moneyAll,0))) as month12Money
from
	(
	select
		sum(ae.moneyAll) as moneyAll,date_format(ab.createTime,'%Y%m') as thisYearMonth,$year as thisYear,
    	case ae.purchType
        	when 'oa_borrow_borrow' then '库存采购'
            when 'oa_present_present' then '库存采购'
            when 'stock' then '库存采购'
            when 'oa_sale_service' then '销售采购'
            when 'oa_sale_rdproject' then '销售采购'
            when 'oa_sale_order' then '销售采购'
            when 'oa_sale_lease' then '销售采购'
			when 'HTLX-XSHT'  then '销售采购'
			when 'HTLX-ZLHT'  then '销售采购'
			when 'HTLX-FWHT'  then '销售采购'
			when 'HTLX-YFHT'  then '销售采购'
            when 'produce' then '生产采购'
            when 'rdproject' then '研发采购'
            when 'assets' then '固定资产采购'
            when 'oa_asset_purchase_apply' then '固定资产采购'
            else ae.`purchType`
        end  as purchType
	from
		oa_purch_apply_equ ae
		inner join
		oa_purch_apply_basic ab
			on ae.basicId = ab.id
	where ab.isTemp = 0 and (((ab.state in (4, 7) and ab.ExaStatus = '完成') or (ab.state in (5, 8,10)))) and ae.amountAll > 0 $purchaseCondition
	group by ae.purchType,date_format(ab.createTime,'%Y%m')
    )gs
    left join
    (select sum(ab.allMoney) as yearAllMoney,$year as thisYear from oa_purch_apply_basic ab where ab.isTemp = 0 and (((ab.state in (4, 7) and ab.ExaStatus = '完成') or (ab.state in (5,8,10)))) $purchaseCondition) ml
    on gs.thisYear = ml.thisYear
group by gs.purchType
order by yearMoney desc

QuerySQL;
GenAttrXmlData($QuerySQL, false);
?>
