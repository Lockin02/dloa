<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
set_time_limit(0);
$dateType = $_GET['dateType'];  //日期类型
$beginDate = $_GET['beginDate'];  //开始日期
$endDate = $_GET['endDate']; //结束日期
$company = isset($_GET['company']) ? $_GET['company'] : "";  //公司
$deptId =  isset($_GET['deptId']) ? $_GET['deptId'] : "";;  //部门

//根据购置日期或入账日期过滤数据,格式yyyy-MM
$condition= " and date_format(c.".$dateType." ,'%Y-%m') >= '$beginDate' and date_format(c.".$dateType." ,'%Y-%m') <= '$endDate'";
//所属公司
if($company != 'all'){
	$condition.=" and c.companyCode = '$company'";
}
//使用部门
if($deptId){
	$condition.=" and (c.useOrgId in($deptId) or c.parentUseOrgId in($deptId))";
}

$sql = <<<QuerySQL
SELECT
	date_format(c.buyDate, '%Y') AS year,
	date_format(c.buyDate, '%m') AS month,
	c.agencyName,
	c.agencyCode,
	c.parentUseOrgId,
	c.parentUseOrgName,
	c.useOrgId,
	c.useOrgName,
	SUM(c.origina) AS origina
FROM
	oa_asset_card c
WHERE
	c.isDel = 0
AND c.isTemp = 0
AND c.assetName <> ''
AND c.assetName IS NOT NULL
AND c.property = 0
$condition
GROUP BY
	c.agencyName,
	c.parentUseOrgId,
	c.useOrgId,
	date_format(c.buyDate, '%Y'),
	date_format(c.buyDate, '%m')
UNION ALL
SELECT
	date_format(c.buyDate, '%Y') AS year,
	'汇总' AS month,
	c.agencyName,
	c.agencyCode,
	c.parentUseOrgId,
	c.parentUseOrgName,
	c.useOrgId,
	c.useOrgName,
	SUM(c.origina) AS origina
FROM
	oa_asset_card c
WHERE
	c.isDel = 0
AND c.isTemp = 0
AND c.assetName <> ''
AND c.assetName IS NOT NULL
AND c.property = 0
$condition
GROUP BY
	c.agencyName,
	c.parentUseOrgId,
	c.useOrgId,
	date_format(c.buyDate, '%Y')
QuerySQL;
GenAttrXmlData($sql,false);