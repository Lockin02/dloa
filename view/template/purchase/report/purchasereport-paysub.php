<?php
include '../../../../webreport/data/mysql_GenXmlData.php';

//订单条件
$purchaseCondition = "";
//付款申请条件
$payapplyCondition = "";

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
$payapplyCondition .= " and date_format(p.actPayDate,'%Y%m%d') >= date_format('$beginDate','%Y%m%d')";
if($_GET['beginMonth'] > 9){
	$beginYearMonth =  $_GET['thisYear'] . $_GET['beginMonth'];
}else{
	$beginYearMonth =  $_GET['thisYear'] ."0". $_GET['beginMonth'];
}

//结束日期
$endYearMonthNum = cal_days_in_month ( CAL_GREGORIAN, $_GET['endMonth'], $_GET['thisYear'] );
$endDate = $_GET['thisYear'] . "-" . $_GET['endMonth'] . "-" . $endYearMonthNum;
$purchaseCondition .= " and date_format(ab.createTime,'%Y%m%d') <= date_format('$endDate','%Y%m%d')";
$payapplyCondition .= " and date_format(p.actPayDate,'%Y%m%d') <= date_format('$endDate','%Y%m%d')";
if($_GET['endMonth'] > 9){
	$endYearMonth =  $_GET['thisYear'] . $_GET['endMonth'];
}else{
	$endYearMonth =  $_GET['thisYear'] ."0". $_GET['endMonth'];
}

//echo $purchaseCondition;
$QuerySQL = <<<QuerySQL
select
	pa.paymentConditionName as payConditionName,
	sum(pa.allMoney) as yearMoney, round((sum(pa.allMoney) / ml.yearAllMoney)*100,2) as moneyPercent,
	if( $January < $beginYearMonth or $January > $endYearMonth,'',sum(if(date_format(pa.createTime,'%Y%m') = $January,pa.allMoney,0))) as month1Money,
	if( $February < $beginYearMonth or $February > $endYearMonth,'',sum(if(date_format(pa.createTime,'%Y%m') = $February,pa.allMoney,0))) as month2Money,
	if( $March < $beginYearMonth or $March > $endYearMonth,'',sum(if(date_format(pa.createTime,'%Y%m') = $March,pa.allMoney,0))) as month3Money,
	if( $April < $beginYearMonth or $April > $endYearMonth,'',sum(if(date_format(pa.createTime,'%Y%m') = $April,pa.allMoney,0))) as month4Money,
	if( $May < $beginYearMonth or $May > $endYearMonth,'',sum(if(date_format(pa.createTime,'%Y%m') = $May,pa.allMoney,0))) as month5Money,
	if( $June < $beginYearMonth or $June > $endYearMonth,'',sum(if(date_format(pa.createTime,'%Y%m') = $June,pa.allMoney,0))) as month6Money,
	if( $July < $beginYearMonth or $July > $endYearMonth,'',sum(if(date_format(pa.createTime,'%Y%m') = $July,pa.allMoney,0))) as month7Money,
	if( $August < $beginYearMonth or $August > $endYearMonth,'',sum(if(date_format(pa.createTime,'%Y%m') = $August,pa.allMoney,0))) as month8Money,
	if( $September < $beginYearMonth or $September > $endYearMonth,'',sum(if(date_format(pa.createTime,'%Y%m') = $September,pa.allMoney,0))) as month9Money,
	if( $October < $beginYearMonth or $October > $endYearMonth,'',sum(if(date_format(pa.createTime,'%Y%m') = $October,pa.allMoney,0))) as month10Money,
	if( $November < $beginYearMonth or $November > $endYearMonth,'',sum(if(date_format(pa.createTime,'%Y%m') = $November,pa.allMoney,0))) as month11Money,
	if( $December < $beginYearMonth or $December > $endYearMonth,'',sum(if(date_format(pa.createTime,'%Y%m') = $December,pa.allMoney,0))) as month12Money
from
	(
		select
			allMoney,$year as thisYear,
			case paymentCondition
				when 'YFK' then if(replace(payRatio,'%','') = 100,'预付款(100%)',if(replace(payRatio,'%','') >= 50,'预付款(50%以上(含))','预算款(50%以下)'))
				when 'MJFK' then '模具付款'
				when 'MJFK2' then '模具付款'
				when 'MJFK2' then '模具付款'
				else paymentConditionName end
			as paymentConditionName,
			replace(payRatio,'%','') as payRatio,
			createTime
		from oa_purch_apply_basic ab where ab.isTemp = 0 and (((ab.state in (4, 7) and ab.ExaStatus = '完成') or (ab.state in (5, 8,10))))  $purchaseCondition
	) pa
	left join
	(select sum(ab.allMoney) as yearAllMoney,$year as thisYear from oa_purch_apply_basic ab where ab.isTemp = 0 and (((ab.state in (4, 7) and ab.ExaStatus = '完成') or (ab.state in (5,8,10)))) $purchaseCondition) ml
    on pa.thisYear = ml.thisYear
group by pa.paymentConditionName
union all
select
	'订单金额合计' as payConditionName,
	sum(ab.allMoney) as yearMoney, '' as moneyPercent,
	if( $January < $beginYearMonth or $January > $endYearMonth,'',sum(if(date_format(ab.createTime,'%Y%m') = $January,ab.allMoney,0))) as month1Money,
	if( $February < $beginYearMonth or $February > $endYearMonth,'',sum(if(date_format(ab.createTime,'%Y%m') = $February,ab.allMoney,0))) as month2Money,
	if( $March < $beginYearMonth or $March > $endYearMonth,'',sum(if(date_format(ab.createTime,'%Y%m') = $March,ab.allMoney,0))) as month3Money,
	if( $April < $beginYearMonth or $April > $endYearMonth,'',sum(if(date_format(ab.createTime,'%Y%m') = $April,ab.allMoney,0))) as month4Money,
	if( $May < $beginYearMonth or $May > $endYearMonth,'',sum(if(date_format(ab.createTime,'%Y%m') = $May,ab.allMoney,0))) as month5Money,
	if( $June < $beginYearMonth or $June > $endYearMonth,'',sum(if(date_format(ab.createTime,'%Y%m') = $June,ab.allMoney,0))) as month6Money,
	if( $July < $beginYearMonth or $July > $endYearMonth,'',sum(if(date_format(ab.createTime,'%Y%m') = $July,ab.allMoney,0))) as month7Money,
	if( $August < $beginYearMonth or $August > $endYearMonth,'',sum(if(date_format(ab.createTime,'%Y%m') = $August,ab.allMoney,0))) as month8Money,
	if( $September < $beginYearMonth or $September > $endYearMonth,'',sum(if(date_format(ab.createTime,'%Y%m') = $September,ab.allMoney,0))) as month9Money,
	if( $October < $beginYearMonth or $October > $endYearMonth,'',sum(if(date_format(ab.createTime,'%Y%m') = $October,ab.allMoney,0))) as month10Money,
	if( $November < $beginYearMonth or $November > $endYearMonth,'',sum(if(date_format(ab.createTime,'%Y%m') = $November,ab.allMoney,0))) as month11Money,
	if( $December < $beginYearMonth or $December > $endYearMonth,'',sum(if(date_format(ab.createTime,'%Y%m') = $December,ab.allMoney,0))) as month12Money
from
	oa_purch_apply_basic ab
where ab.isTemp = 0 and (((ab.state in (4, 7) and ab.ExaStatus = '完成') or (ab.state in (5, 8,10)))) $purchaseCondition

union all

select
	'财务付款合计' as payConditionName,
	sum(if(p.payFor = 'FKLX-03',-p.payedMoney,p.payedMoney)) as yearMoney, '' as moneyPercent,
	if( $January < $beginYearMonth or $January > $endYearMonth,'',sum(if(date_format(p.actPayDate,'%Y%m') = $January,if(p.payFor = 'FKLX-03',-p.payedMoney,p.payedMoney),0))) as month1Money,
	if( $February < $beginYearMonth or $February > $endYearMonth,'',sum(if(date_format(p.actPayDate,'%Y%m') = $February,if(p.payFor = 'FKLX-03',-p.payedMoney,p.payedMoney),0))) as month2Money,
	if( $March < $beginYearMonth or $March > $endYearMonth,'',sum(if(date_format(p.actPayDate,'%Y%m') = $March,if(p.payFor = 'FKLX-03',-p.payedMoney,p.payedMoney),0))) as month3Money,
	if( $April < $beginYearMonth or $April > $endYearMonth,'',sum(if(date_format(p.actPayDate,'%Y%m') = $April,if(p.payFor = 'FKLX-03',-p.payedMoney,p.payedMoney),0))) as month4Money,
	if( $May < $beginYearMonth or $May > $endYearMonth,'',sum(if(date_format(p.actPayDate,'%Y%m') = $May,if(p.payFor = 'FKLX-03',-p.payedMoney,p.payedMoney),0))) as month5Money,
	if( $June < $beginYearMonth or $June > $endYearMonth,'',sum(if(date_format(p.actPayDate,'%Y%m') = $June,if(p.payFor = 'FKLX-03',-p.payedMoney,p.payedMoney),0))) as month6Money,
	if( $July < $beginYearMonth or $July > $endYearMonth,'',sum(if(date_format(p.actPayDate,'%Y%m') = $July,if(p.payFor = 'FKLX-03',-p.payedMoney,p.payedMoney),0))) as month7Money,
	if( $August < $beginYearMonth or $August > $endYearMonth,'',sum(if(date_format(p.actPayDate,'%Y%m') = $August,if(p.payFor = 'FKLX-03',-p.payedMoney,p.payedMoney),0))) as month8Money,
	if( $September < $beginYearMonth or $September > $endYearMonth,'',sum(if(date_format(p.actPayDate,'%Y%m') = $September,if(p.payFor = 'FKLX-03',-p.payedMoney,p.payedMoney),0))) as month9Money,
	if( $October < $beginYearMonth or $October > $endYearMonth,'',sum(if(date_format(p.actPayDate,'%Y%m') = $October,if(p.payFor = 'FKLX-03',-p.payedMoney,p.payedMoney),0))) as month10Money,
	if( $November < $beginYearMonth or $November > $endYearMonth,'',sum(if(date_format(p.actPayDate,'%Y%m') = $November,if(p.payFor = 'FKLX-03',-p.payedMoney,p.payedMoney),0))) as month11Money,
	if( $December < $beginYearMonth or $December > $endYearMonth,'',sum(if(date_format(p.actPayDate,'%Y%m') = $December,if(p.payFor = 'FKLX-03',-p.payedMoney,p.payedMoney),0))) as month12Money
from
	oa_finance_payablesapply p
where actPayDate is not null and sourceType = 'YFRK-01' and status = 'FKSQD-03' $payapplyCondition

union all

select
	'订单金额与财务付款差额' as payConditionName,
	sum(cs.yearMoney) as yearMoney, '' as moneyPercent,
	if(sum(cs.month1Money) = 0,'',sum(cs.month1Money)) as month1Money,if(sum(cs.month2Money) = 0,'',sum(cs.month2Money)) as month2Money,
	if(sum(cs.month3Money) = 0,'',sum(cs.month3Money)) as month3Money,if(sum(cs.month4Money) = 0,'',sum(cs.month4Money)) as month4Money,
	if(sum(cs.month5Money) = 0,'',sum(cs.month5Money)) as month5Money,if(sum(cs.month6Money) = 0,'',sum(cs.month6Money)) as month6Money,
	if(sum(cs.month7Money) = 0,'',sum(cs.month7Money)) as month7Money,if(sum(cs.month8Money) = 0,'',sum(cs.month8Money)) as month8Money,
	if(sum(cs.month9Money) = 0,'',sum(cs.month9Money)) as month9Money,if(sum(cs.month10Money) = 0,'',sum(cs.month10Money)) as month10Money,
	if(sum(cs.month11Money) = 0,'',sum(cs.month11Money)) as month11Money,if(sum(cs.month12Money) = 0,'',sum(cs.month12Money)) as month12Money
from
(
	select
		sum(ab.allMoney) as yearMoney,
		if( $January < $beginYearMonth or $January > $endYearMonth,'',sum(if(date_format(ab.createTime,'%Y%m') = $January,ab.allMoney,0))) as month1Money,
		if( $February < $beginYearMonth or $February > $endYearMonth,'',sum(if(date_format(ab.createTime,'%Y%m') = $February,ab.allMoney,0))) as month2Money,
		if( $March < $beginYearMonth or $March > $endYearMonth,'',sum(if(date_format(ab.createTime,'%Y%m') = $March,ab.allMoney,0))) as month3Money,
		if( $April < $beginYearMonth or $April > $endYearMonth,'',sum(if(date_format(ab.createTime,'%Y%m') = $April,ab.allMoney,0))) as month4Money,
		if( $May < $beginYearMonth or $May > $endYearMonth,'',sum(if(date_format(ab.createTime,'%Y%m') = $May,ab.allMoney,0))) as month5Money,
		if( $June < $beginYearMonth or $June > $endYearMonth,'',sum(if(date_format(ab.createTime,'%Y%m') = $June,ab.allMoney,0))) as month6Money,
		if( $July < $beginYearMonth or $July > $endYearMonth,'',sum(if(date_format(ab.createTime,'%Y%m') = $July,ab.allMoney,0))) as month7Money,
		if( $August < $beginYearMonth or $August > $endYearMonth,'',sum(if(date_format(ab.createTime,'%Y%m') = $August,ab.allMoney,0))) as month8Money,
		if( $September < $beginYearMonth or $September > $endYearMonth,'',sum(if(date_format(ab.createTime,'%Y%m') = $September,ab.allMoney,0))) as month9Money,
		if( $October < $beginYearMonth or $October > $endYearMonth,'',sum(if(date_format(ab.createTime,'%Y%m') = $October,ab.allMoney,0))) as month10Money,
		if( $November < $beginYearMonth or $November > $endYearMonth,'',sum(if(date_format(ab.createTime,'%Y%m') = $November,ab.allMoney,0))) as month11Money,
		if( $December < $beginYearMonth or $December > $endYearMonth,'',sum(if(date_format(ab.createTime,'%Y%m') = $December,ab.allMoney,0))) as month12Money
	from
		oa_purch_apply_basic ab
	where ab.isTemp = 0 and (((ab.state in (4, 7) and ab.ExaStatus = '完成') or (ab.state in (5, 8,10)))) $purchaseCondition
	union all
	select
		-sum(if(p.payFor = 'FKLX-03',-p.payedMoney,p.payedMoney)) as yearMoney,
		if( $January < $beginYearMonth or $January > $endYearMonth,'',-sum(if(date_format(p.actPayDate,'%Y%m') = $January,if(p.payFor = 'FKLX-03',-p.payedMoney,p.payedMoney),0))) as month1Money,
		if( $February < $beginYearMonth or $February > $endYearMonth,'',-sum(if(date_format(p.actPayDate,'%Y%m') = $February,if(p.payFor = 'FKLX-03',-p.payedMoney,p.payedMoney),0))) as month2Money,
		if( $March < $beginYearMonth or $March > $endYearMonth,'',-sum(if(date_format(p.actPayDate,'%Y%m') = $March,if(p.payFor = 'FKLX-03',-p.payedMoney,p.payedMoney),0))) as month3Money,
		if( $April < $beginYearMonth or $April > $endYearMonth,'',-sum(if(date_format(p.actPayDate,'%Y%m') = $April,if(p.payFor = 'FKLX-03',-p.payedMoney,p.payedMoney),0))) as month4Money,
		if( $May < $beginYearMonth or $May > $endYearMonth,'',-sum(if(date_format(p.actPayDate,'%Y%m') = $May,if(p.payFor = 'FKLX-03',-p.payedMoney,p.payedMoney),0))) as month5Money,
		if( $June < $beginYearMonth or $June > $endYearMonth,'',-sum(if(date_format(p.actPayDate,'%Y%m') = $June,if(p.payFor = 'FKLX-03',-p.payedMoney,p.payedMoney),0))) as month6Money,
		if( $July < $beginYearMonth or $July > $endYearMonth,'',-sum(if(date_format(p.actPayDate,'%Y%m') = $July,if(p.payFor = 'FKLX-03',-p.payedMoney,p.payedMoney),0))) as month7Money,
		if( $August < $beginYearMonth or $August > $endYearMonth,'',-sum(if(date_format(p.actPayDate,'%Y%m') = $August,if(p.payFor = 'FKLX-03',-p.payedMoney,p.payedMoney),0))) as month8Money,
		if( $September < $beginYearMonth or $September > $endYearMonth,'',-sum(if(date_format(p.actPayDate,'%Y%m') = $September,if(p.payFor = 'FKLX-03',-p.payedMoney,p.payedMoney),0))) as month9Money,
		if( $October < $beginYearMonth or $October > $endYearMonth,'',-sum(if(date_format(p.actPayDate,'%Y%m') = $October,if(p.payFor = 'FKLX-03',-p.payedMoney,p.payedMoney),0))) as month10Money,
		if( $November < $beginYearMonth or $November > $endYearMonth,'',-sum(if(date_format(p.actPayDate,'%Y%m') = $November,if(p.payFor = 'FKLX-03',-p.payedMoney,p.payedMoney),0))) as month11Money,
		if( $December < $beginYearMonth or $December > $endYearMonth,'',-sum(if(date_format(p.actPayDate,'%Y%m') = $December,if(p.payFor = 'FKLX-03',-p.payedMoney,p.payedMoney),0))) as month12Money
	from
		oa_finance_payablesapply p
	where actPayDate is not null and sourceType = 'YFRK-01' and status = 'FKSQD-03' $payapplyCondition
) cs

QuerySQL;
GenAttrXmlData($QuerySQL, false);
?>
