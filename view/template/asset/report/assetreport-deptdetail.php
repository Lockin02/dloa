<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
set_time_limit(0);
$dateType = $_GET['dateType'];  //日期类型
$beginDate = $_GET['beginDate'];  //开始日期
$endDate = $_GET['endDate']; //结束日期
$dateFmt = $_GET['dateFmt']; //日期格式
$company = isset($_GET['company']) ? $_GET['company'] : "";  //公司
$deptId =  $_GET['deptId'];  //部门
$deptName = $_GET['deptName'];

$condition = "";
//根据购置日期或入账日期过滤数据
if($dateType){
	if($dateFmt == 'month'){//日期精确到月份
		$condition.=" and date_format(c.".$dateType." ,'%Y-%m') >= '$beginDate' and date_format(c.".$dateType." ,'%Y-%m') <= '$endDate'";
	}else{
		$condition.=" and c.".$dateType." >= '$beginDate' and c.".$dateType." <= '$endDate'";
	}
}
//所属公司
if($company != 'all'){
	$condition.=" and c.companyCode = '$company'";
}

$sql = <<<QuerySQL
SELECT
 c.id,
 c.assetName,
'固定资产' AS property,
 c.assetTypeName,
 c.assetCode,
 c.brand,
 c.englishName,
 c.spec,
 c.unit,
 c.deploy,
 c.mobileNetwork,
 c.mobileBand,
 c.machineCode,
 c.userName,
 c.parentUseOrgName,
 c.useOrgName,
 c.belongMan,
 c.parentOrgName,
 c.orgName,
 c.agencyName,
 c.assetSourceName,
 c.useStatusName,
 c.useProName,
 c.requireCode,
 c.origina,
 c.salvage,
 c.buyDate,
 c.wirteDate,
 c.beginTime,
 c.estimateDay,
 c.alreadyDay,
 c.remark
FROM
	oa_asset_card c
WHERE
	c.isDel = 0 
AND	c.isTemp = 0
AND	c.parentUseOrgId = $deptId
AND c.property = 0
$condition
ORDER BY
	c.assetName
QuerySQL;
GenAttrXmlData($sql,false);