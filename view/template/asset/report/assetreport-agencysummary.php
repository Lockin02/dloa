<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
set_time_limit(0);
$dateType = $_GET['dateType'];  //日期类型
$beginDate = $_GET['beginDate'];  //开始日期
$endDate = $_GET['endDate']; //结束日期
$dateFmt = $_GET['dateFmt']; //日期格式
$company = isset($_GET['company']) ? $_GET['company'] : "";  //公司
$deptId =  isset($_GET['deptId']) ? $_GET['deptId'] : "";;  //部门
$deptIdStr =  isset($_GET['deptIdStr']) ? $_GET['deptIdStr'] : "";;  //部门权限
$agencyCode = isset($_GET['agencyCode']) ? $_GET['agencyCode'] : "";  //行政区域
$agencyCodeStr = isset($_GET['agencyCodeStr']) ? $_GET['agencyCodeStr'] : "";  //区域权限
$userId = isset($_GET['userId']) ? $_GET['userId'] : "";  //用户id

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
//存在区域权限或部门权限
if($agencyCodeStr || $deptIdStr){
	//所属行政区域
	if($agencyCode != 'all'){
		$condition.=" and c.agencyCode = '$agencyCode'";
	}else{
		//区域权限
		if($agencyCodeStr && strstr($agencyCodeStr,';;') == false){
			$condition.=" and c.agencyCode in($agencyCodeStr)";
		}
	}
	//使用部门
	if($deptId){
		$condition.=" and (c.useOrgId in($deptId) or c.parentUseOrgId in($deptId))";
	}else{
		//部门权限
		if($deptIdStr && strstr($deptIdStr,';;') == false){
			$condition.=" and (c.useOrgId in($deptIdStr) or c.parentUseOrgId in($deptIdStr))";
		}
	}
}else{//不存在任何权限则显示自己名下的资产
	$condition.=" and (c.userId = '$userId' or c.belongManId = '$userId')";
}

$sql = <<<QuerySQL
SELECT
	total.agencyName,
	total.agencyCode,
	total.typeName,
	total.assetName,
	total.useStatusName,
	total.num,
	total.origina,
	total.salvage,
	total.sort
FROM
	(
		SELECT
			c.agencyName,
			c.agencyCode,
			IFNULL(a.typeName,'固定项目') as typeName,
			c.assetName,
			'库存' AS useStatusName,
			COUNT(*) AS num,
			SUM(c.origina) AS origina,
			SUM(c.salvage) AS salvage,
			1 AS sort
		FROM
			oa_asset_card c
		LEFT JOIN oa_asset_assetnametype a ON a.assetName = c.assetName
		WHERE
			c.isDel = 0 
		AND	c.isTemp = 0
		AND	c.agencyName <> ''
		AND c.agencyName IS NOT NULL
		AND c.assetName <> ''
		AND c.assetName IS NOT NULL
		AND c.useStatusCode = 'SYZT-XZ'
		AND c.property = 0
		$condition
		GROUP BY
			c.agencyName,
			c.assetName
		UNION ALL
		SELECT
			c.agencyName,
			c.agencyCode,
			IFNULL(a.typeName,'固定项目') as typeName,
			c.assetName,
			'使用中' AS useStatusName,
			COUNT(*) AS num,
			SUM(c.origina) AS origina,
			SUM(c.salvage) AS salvage,
			1 AS sort
		FROM
			oa_asset_card c
		LEFT JOIN oa_asset_assetnametype a ON a.assetName = c.assetName
		WHERE
			c.isDel = 0 
		AND	c.isTemp = 0
		AND	c.agencyName <> ''
		AND c.agencyName IS NOT NULL
		AND c.assetName <> ''
		AND c.assetName IS NOT NULL
		AND c.useStatusCode = 'SYZT-SYZ'
		AND c.property = 0
		$condition
		GROUP BY
			c.agencyName,
			c.assetName
		UNION ALL
		SELECT
			'总计' AS agencyName,
			'' AS agencyCode,
			'' as typeName,
			'' AS assetName,
			'库存' AS useStatusName,
			COUNT(*) AS num,
			SUM(c.origina) AS origina,
			SUM(c.salvage) AS salvage,
			2 AS sort
		FROM
			oa_asset_card c
		LEFT JOIN oa_asset_assetnametype a ON a.assetName = c.assetName
		WHERE
			c.isDel = 0 
		AND	c.isTemp = 0
		AND	c.agencyName <> ''
		AND c.agencyName IS NOT NULL
		AND c.assetName <> ''
		AND c.assetName IS NOT NULL
		AND c.useStatusCode = 'SYZT-XZ'
		AND c.property = 0
		$condition
		UNION ALL
		SELECT
			'总计' AS agencyName,
			'' AS agencyCode,
			'' as typeName,
			'' AS assetName,
			'使用中' AS useStatusName,
			COUNT(*) AS num,
			SUM(c.origina) AS origina,
			SUM(c.salvage) AS salvage,
			2 AS sort
		FROM
			oa_asset_card c
		LEFT JOIN oa_asset_assetnametype a ON a.assetName = c.assetName
		WHERE
			c.isDel = 0 
		AND	c.isTemp = 0
		AND	c.agencyName <> ''
		AND c.agencyName IS NOT NULL
		AND c.assetName <> ''
		AND c.assetName IS NOT NULL
		AND c.useStatusCode = 'SYZT-SYZ'
		AND c.property = 0
		$condition
	) total
ORDER BY
	total.sort,
	total.agencyName,
	total.typeName,
	total.assetName,
	total.useStatusName
QuerySQL;
GenAttrXmlData($sql,false);