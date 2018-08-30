<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
set_time_limit(0);
$company = isset($_GET['company']) ? $_GET['company'] : "";  //公司
$deptId =  $_GET['deptId'];  //部门
$deptName = $_GET['deptName'];
$assetName = isset($_GET['assetName']) ? $_GET['assetName'] : "";  //资产名称

$condition = "";
//所属公司
if($company != 'all'){
	$condition.=" and c.companyCode = '$company'";
}
//资产名称
if($assetName){
	$condition.=" and c.assetName = '$assetName'";
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
AND	c.useOrgId = $deptId
AND c.property = 0
$condition
ORDER BY
	c.assetName
QuerySQL;
GenAttrXmlData($sql,false);