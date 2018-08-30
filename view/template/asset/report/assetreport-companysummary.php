<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
set_time_limit(0);
$dateType = $_GET['dateType'];  //日期类型
$beginDate = $_GET['beginDate'];  //开始日期
$endDate = $_GET['endDate']; //结束日期
$company = isset($_GET['company']) ? $_GET['company'] : "";  //公司
$deptId =  isset($_GET['deptId']) ? $_GET['deptId'] : "";;  //部门
$deptIdStr =  isset($_GET['deptIdStr']) ? $_GET['deptIdStr'] : "";;  //部门权限
$agencyCode = isset($_GET['agencyCode']) ? $_GET['agencyCode'] : "";  //行政区域
$agencyCodeStr = isset($_GET['agencyCodeStr']) ? $_GET['agencyCodeStr'] : "";  //区域权限
$userId = isset($_GET['userId']) ? $_GET['userId'] : "";  //用户id

$condition = "";
//报废状态的资产以具体更新时间为准，其它状态则以购置日期或入账日期为准
$date = " and c.".$dateType." >= '$beginDate' and c.".$dateType." <= '$endDate'";
$updateTime = " and date_format(c.updateTime,'%Y-%m-%d') >= '$beginDate' and date_format(c.updateTime,'%Y-%m-%d') <= '$endDate'";
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
	total.typeName,
	total.assetName,
	total.useStatusName,
	total.num,
	total.origina,
	total.sort
FROM
	(
		SELECT
			IFNULL(a.typeName,'固定项目') as typeName,
			c.assetName,
			'库存' AS useStatusName,
			COUNT(*) AS num,
			SUM(c.origina) AS origina,
			1 AS sort
		FROM
			oa_asset_card c
		LEFT JOIN oa_asset_assetnametype a ON a.assetName = c.assetName
		WHERE
			c.isDel = 0 
		AND	c.isTemp = 0
		AND	c.assetName <> ''
		AND c.assetName IS NOT NULL
		AND c.useStatusCode = 'SYZT-XZ'
		AND c.property = 0
		$date
		$condition
		GROUP BY
			c.assetName
		UNION ALL
		SELECT
			IFNULL(a.typeName,'固定项目') as typeName,
			c.assetName,
			'使用中' AS useStatusName,
			COUNT(*) AS num,
			SUM(c.origina) AS origina,
			1 AS sort
		FROM
			oa_asset_card c
		LEFT JOIN oa_asset_assetnametype a ON a.assetName = c.assetName
		WHERE
			c.isDel = 0 
		AND	c.isTemp = 0
		AND	c.assetName <> ''
		AND c.assetName IS NOT NULL
		AND c.useStatusCode = 'SYZT-SYZ'
		AND c.property = 0
		$date
		$condition
		GROUP BY
			c.assetName
		UNION ALL
		SELECT
			IFNULL(a.typeName,'固定项目') as typeName,
			c.assetName,
			'报废' AS useStatusName,
			COUNT(*) AS num,
			SUM(c.origina) AS origina,
			1 AS sort
		FROM
			oa_asset_card c
		LEFT JOIN oa_asset_assetnametype a ON a.assetName = c.assetName
		WHERE
			c.isDel = 0 
		AND	c.isTemp = 0
		AND	c.assetName <> ''
		AND c.assetName IS NOT NULL
		AND (
			c.useStatusCode = 'SYZT-DBF'
			OR c.useStatusCode = 'SYZT-YBF'
			OR c.useStatusCode = 'SYZT-WCS'
			OR c.useStatusCode = 'SYZT-YQL'
			OR c.useStatusCode = 'SYZT-WXZ'
		)
		AND c.property = 0
		$updateTime
		$condition
		GROUP BY
			c.assetName
		UNION ALL
		SELECT
			'总计' as typeName,
			'' as assetName,
			'库存' AS useStatusName,
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
		AND c.useStatusCode = 'SYZT-XZ'
		AND c.property = 0
		$date
		$condition
		UNION ALL
		SELECT
			'总计' as typeName,
			'' as assetName,
			'使用中' AS useStatusName,
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
		AND c.useStatusCode = 'SYZT-SYZ'
		AND c.property = 0
		$date
		$condition
		UNION ALL
		SELECT
			'总计' as typeName,
			'' as assetName,
			'报废' AS useStatusName,
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
		AND (
			c.useStatusCode = 'SYZT-DBF'
			OR c.useStatusCode = 'SYZT-YBF'
			OR c.useStatusCode = 'SYZT-WCS'
			OR c.useStatusCode = 'SYZT-YQL'
			OR c.useStatusCode = 'SYZT-WXZ'
		)
		AND c.property = 0
		$updateTime
		$condition
	) total
GROUP BY
	total.sort,
	total.typeName,
	total.assetName,
	total.useStatusName
QuerySQL;
GenAttrXmlData($sql,false);