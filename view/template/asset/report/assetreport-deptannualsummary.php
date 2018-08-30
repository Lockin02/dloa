<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
set_time_limit(0);
$dateType = $_GET['dateType'];  //日期类型
$beginDate = $_GET['beginDate'];  //开始日期
$endDate = $_GET['endDate']; //结束日期
$company = isset($_GET['company']) ? $_GET['company'] : ""; //公司
$deptId =  isset($_GET['deptId']) ? $_GET['deptId'] : "";;  //部门
$deptName =  isset($_GET['deptName']) ? $_GET['deptName'] : "";
$deptIdStr =  isset($_GET['deptIdStr']) ? $_GET['deptIdStr'] : "";;  //部门权限

//根据购置日期或入账日期过滤数据
$condition= " and c.".$dateType." >= '$beginDate' and c.".$dateType." <= '$endDate'";
//所属公司
if($company != 'all'){
	$condition.=" and c.companyCode = '$company'";
}
//使用部门
if($deptId){
	$condition.=" and (c.useOrgId in($deptId) or c.parentUseOrgId in($deptId))";
}else{
	//部门权限
	if($deptIdStr && strstr($deptIdStr,';;') == false){//存在部门权限,且不为所有
		$condition.=" and (c.useOrgId in($deptIdStr) or c.parentUseOrgId in($deptIdStr))";
	}
}

$sql = <<<QuerySQL
SELECT
	total.assetName,
	total.num,
	total.origina,
	'$deptName' as deptName
FROM
	(
		SELECT
			c.assetName,
			COUNT(*) AS num,
			SUM(c.origina) AS origina,
			1 AS sort
		FROM
			oa_asset_card c
		WHERE
			c.isDel = 0 
		AND	c.isTemp = 0
		AND	c.assetName <> ''
		AND c.assetName IS NOT NULL
		AND c.property = 0
		$condition
		GROUP BY
			c.assetName
		UNION ALL
		SELECT
			'汇总' AS assetName,
			COUNT(*) AS num,
			SUM(c.origina) AS origina,
			2 AS sort
		FROM
			oa_asset_card c
		WHERE
			c.isDel = 0 
		AND	c.isTemp = 0
		AND	c.assetName <> ''
		AND c.assetName IS NOT NULL
		AND c.property = 0
		$condition
	) total
ORDER BY
	total.sort
QuerySQL;
GenAttrXmlData($sql,false);