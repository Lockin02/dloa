<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
?>
<?php
$condition = "";
if(!empty($_GET['beginYear'])){
	$beginYearMonthNum = cal_days_in_month ( CAL_GREGORIAN, $_GET['beginMonth'], $_GET['beginYear'] ); //这个月有多少天
	$monthBeginDate = $_GET['beginYear'] . "-" . $_GET['beginMonth'] . "-1";//月开始日期
	$endYearMonthNum = cal_days_in_month ( CAL_GREGORIAN, $_GET['endMonth'], $_GET['endYear'] ); //这个月有多少天
	$monthEndDate = $_GET['endYear'] . "-" . $_GET['endMonth'] . "-" . $endYearMonthNum;//月结束日期
	$condition .= " and c.invoiceTime between '".$monthBeginDate ."' and '" . $monthEndDate ."'";
}
//合同号查询
if(!empty($_GET['objCodeSearch'])){
	$condition .= ' and c.objCode like concat("%","'.$_GET['objCodeSearch'].'","%") ';
}
//客户查询
if(!empty($_GET['customerId'])){
	$condition .= ' and c.invoiceUnitId = '.$_GET['customerId'].' ';
}
//发票号码查询
if(!empty($_GET['invoiceNo'])){
	$condition .= ' and c.invoiceNo like concat("%","'.$_GET['invoiceNo'].'","%") ';
}
//客户类型查询
if(!empty($_GET['customerType'])){
	$condition .= ' and c.invoiceUnitType='.$_GET['customerType'].' ';
}
//区域名称查询
if(!empty($_GET['areaName'])){
	$condition .= ' and c.areaName="'.$_GET['areaName'].'" ';
}
//区域名称查询
if(!empty($_GET['areaNameLimit'])){
	if(!strstr($_GET['areaNameLimit'],';;') && !strstr($_GET['deptLimit'],';;')){
	    $areaNameLimitArr = explode(',',$_GET['areaNameLimit']);
	    $limitStr = '';
	    foreach($areaNameLimitArr as $key => $val){
	        if($key){
	            $limitStr .= '","'.$val;
	        }else{
	        	$limitStr = $val;
	        }
	    }
	    $condition .= ' and (c.areaName in ("'.$limitStr.'") or c.deptId in ('.$_GET['deptLimit'].'))';
	}
}else if(!strstr($_GET['deptLimit'],';;')){
    $condition .= ' and c.deptId in ('.$_GET['deptLimit'].') ';
}

//归属公司查询
if(!empty($_GET['comLimit'])){
	if(!strstr($_GET['areaNameLimit'],';;') && !strstr($_GET['deptLimit'],';;') && !strstr($_GET['comLimit'],';;')){
		$comLimitArr = explode(',',$_GET['comLimit']);
		$limitStr = '';
		foreach($comLimitArr as $key => $val){
			if($key){
				$limitStr .= '","'.$val;
			}else{
				$limitStr = $val;
			}
		}
		$condition .= ' and (c.businessBelong in ("'.$limitStr.'") or c.formBelong in ("'.$limitStr.'") )';
	}
}

//客户省份查询
if(!empty($_GET['customerProvince'])){
	$condition .= ' and c.invoiceUnitProvince="'.$_GET['customerProvince'].'" ';
}
//销售员查询
if(!empty($_GET['salesmanId'])){
	$condition .= ' and c.salesmanId="'.$_GET['salesmanId'].'" ';
}
//签约主体
if(!empty($_GET['signSubjectName'])){
	$condition .= ' and con.signSubjectName like concat("%","'.$_GET['signSubjectName'].'","%") ';
}
$QuerySQL = <<<QuerySQL
select
	c.id,c.invoiceNo,c.invoiceCode,
	if(c.isRed = 0,c.allAmount,-c.allAmount) as amount,
	if(c.isRed = 0,c.invoiceMoney,-c.invoiceMoney) as invoiceMoney,
	if(c.isRed = 0,c.softMoney,-c.softMoney) as softMoney,
	if(c.isRed = 0,c.hardMoney,-c.hardMoney) as hardMoney,
	if(c.isRed = 0,c.repairMoney,-c.repairMoney) as repairMoney,
	if(c.isRed = 0,c.serviceMoney,-c.serviceMoney) as serviceMoney,
	if(c.isRed = 0,c.equRentalMoney,-c.equRentalMoney) as equRentalMoney,
	if(c.isRed = 0,c.spaceRentalMoney,-c.spaceRentalMoney) as spaceRentalMoney,
	if(c.isRed = 0,c.otherMoney,-c.otherMoney) as otherMoney,
	if(c.isRed = 0,c.dsEnergyCharge,-c.dsEnergyCharge) as dsEnergyCharge,
	if(c.isRed = 0,c.dsWaterRateMoney,-c.dsWaterRateMoney) as dsWaterRateMoney,
	if(c.isRed = 0,c.houseRentalFee,-c.houseRentalFee) as houseRentalFee,
	if(c.isRed = 0,c.installationCost,-c.installationCost) as installationCost,
	c.invoiceTime,
	year(c.invoiceTime) as invoiceYear,
	date_format(c.invoiceTime,'%Y%m') as invoiceYearMonth,
	c.objType,
	c.salesmanId,
	c.salesman,
	c.invoiceUnitName,
	c.invoiceUnitId,
	c.invoiceTypeName,
	c.isRed,
	c.salesman as prinvipalName,
	c.objCode as orderCode,
	c.rObjCode as rObjCode,
	c.remark,c.createTime,
	c.invoiceContent as productName,
	c.psType,
	c.areaName,
	c.invoiceUnitProvince as customerProvince,
	c.invoiceUnitTypeName as customerType,
	c.salemanArea as thisAreaName,
	c.managerName as areaPrincipal,
	c.formBelong,
	c.formBelongName,
	c.businessBelong,
	c.businessBelongName,
	c.rentBeginDate,
	c.rentEndDate,
	c.rentDays,
	if(c.objType = 'KPRK-12',con.contractTypeName,c.objTypeName) as objTypeCN,
	con.signSubjectName,
	con.signContractTypeName,
	con.contractNatureName
from oa_finance_invoice c
	left join oa_contract_contract con on con.isTemp = 0 and con.id = c.objId
where 1=1 $condition
order by c.invoiceTime asc , c.createTime asc

QuerySQL;
GenAttrXmlData($QuerySQL, false);
?>
