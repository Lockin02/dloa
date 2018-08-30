<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
set_time_limit(0);
$dateType = $_GET['dateType'];  //日期类型
$beginDate = $_GET['beginDate'];  //开始日期
$endDate = $_GET['endDate']; //结束日期
$dateFmt = $_GET['dateFmt']; //日期格式
$company = isset($_GET['company']) ? $_GET['company'] : "";  //公司
$deptId =  isset($_GET['deptId']) ? $_GET['deptId'] : "";  //部门
$deptIdStr =  isset($_GET['deptIdStr']) ? $_GET['deptIdStr'] : "";;  //部门权限

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
	total.typeName,
	total.parentUseOrgId,
	total.parentUseOrgName,
	total.useOrgId,
	total.useOrgName,
	total.assetName,
	total.num,
	total.origina,
	total.salvage,
	total.sort
FROM(
	SELECT
		IFNULL(a.typeName,'固定项目') as typeName,
		c.parentUseOrgId,
		c.parentUseOrgName,
		c.useOrgId,
		c.useOrgName,
		c.assetName,
		COUNT(*) AS num,
		SUM(c.origina) AS origina,
		SUM(c.salvage) AS salvage,
		1 AS sort
	FROM
		oa_asset_card c
	LEFT JOIN oa_asset_assetnametype a ON a.assetName = c.assetName
	WHERE
		c.isDel = 0
	AND c.isTemp = 0
	AND c.assetName <> ''
	AND c.assetName IS NOT NULL
	AND	c.useStatusCode <> 'SYZT-DBF'
	AND c.useStatusCode <> 'SYZT-YBF'
	AND c.useStatusCode <> 'SYZT-WCS'
	AND c.useStatusCode <> 'SYZT-YQL'
	AND c.useStatusCode <> 'SYZT-WXZ'
	AND c.property = 0
	$condition
	GROUP BY
		c.parentUseOrgId,
		c.useOrgId,
		typeName,
		c.assetName
	UNION ALL
		SELECT
			'' as typeName,
			'' as parentUseOrgId,
			'总计' as parentUseOrgName,
			'' as useOrgId,
			'' as useOrgName,
			'' as assetName,
			COUNT(*) AS num,
			SUM(c.origina) AS origina,
			SUM(c.salvage) AS salvage,
			2 AS sort
		FROM
			oa_asset_card c
		WHERE
			c.isDel = 0
		AND c.isTemp = 0
		AND c.assetName <> ''
		AND c.assetName IS NOT NULL
		AND	c.useStatusCode <> 'SYZT-DBF'
		AND c.useStatusCode <> 'SYZT-YBF'
		AND c.useStatusCode <> 'SYZT-WCS'
		AND c.useStatusCode <> 'SYZT-YQL'
		AND c.useStatusCode <> 'SYZT-WXZ'
		AND c.property = 0
		$condition
	) total
ORDER BY
	total.sort,
	total.parentUseOrgId DESC,
	total.useOrgId,
	total.typeName
QuerySQL;
GenAttrXmlData($sql,false);