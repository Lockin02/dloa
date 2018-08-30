<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
set_time_limit(0);
$beginDate = $_GET['beginDate'];  //开始日期
$endDate = $_GET['endDate']; //结束日期
$company = isset($_GET['company']) ? $_GET['company'] : "";  //公司
$deptId =  isset($_GET['deptId']) ? $_GET['deptId'] : "";;  //部门
$deptIdStr =  isset($_GET['deptIdStr']) ? $_GET['deptIdStr'] : "";;  //部门权限
$agencyCode = isset($_GET['agencyCode']) ? $_GET['agencyCode'] : "";  //行政区域
$agencyCodeStr = isset($_GET['agencyCodeStr']) ? $_GET['agencyCodeStr'] : "";  //区域权限

$condition = "";
//报废状态的资产以具体更新时间为准，其它状态则以入账日期为准
$wirteDate= " and c.wirteDate >= '$beginDate' and c.wirteDate <= '$endDate'";
$updateTime = " and date_format(c.updateTime,'%Y-%m-%d') >= '$beginDate' and date_format(c.updateTime,'%Y-%m-%d') <= '$endDate'";
//所属公司
if($company != 'all'){
	$condition.=" and c.companyCode = '$company'";
}
//所属行政区域
if($agencyCode != 'all'){
	$condition.=" and c.agencyCode = '$agencyCode'";
}else{
	//区域权限
	if($agencyCodeStr && strstr($agencyCodeStr,';;') == false){
		$condition.=" and c.agencyCode in($agencyCodeStr)";
	}
}
//所属部门
if($deptId){
	$condition.=" and (c.orgId in($deptId) or c.orgId in(select DEPT_ID from department where DelFlag = 0 and PARENT_ID in($deptId)))";
}else{
	//部门权限
	if($deptIdStr && strstr($deptIdStr,';;') == false){
		$condition.=" and (c.orgId in($deptIdStr) or c.orgId in(select DEPT_ID from department where DelFlag = 0 and PARENT_ID in($deptIdStr)))";
	}
}

$sql = <<<QuerySQL
SELECT
	total.assetName,
	total.useStatusName,
	total.num
FROM
	(
		SELECT
			c.assetName,
			'调库' AS useStatusName,
			COUNT(*) AS num,
			1 AS sort
		FROM
			oa_asset_card c
		WHERE
			isDel = 0 
		AND	isTemp = 0
		AND	c.assetName <> ''
		AND c.assetName IS NOT NULL
		AND (
			c.useStatusCode = 'SYZT-DTK'
			OR c.useStatusCode = 'SYZT-YTK'
		)
		$wirteDate
		$condition
		GROUP BY
			c.assetName
		UNION ALL
		SELECT
			c.assetName,
			'库存' AS useStatusName,
			COUNT(*) AS num,
			2 AS sort
		FROM
			oa_asset_card c
		WHERE
			isDel = 0 
		AND	isTemp = 0
		AND	c.assetName <> ''
		AND c.assetName IS NOT NULL
		AND c.useStatusCode = 'SYZT-XZ'
		$wirteDate
		$condition
		GROUP BY
			c.assetName
		UNION ALL
		SELECT
			c.assetName,
			'使用' AS useStatusName,
			COUNT(*) AS num,
			3 AS sort
		FROM
			oa_asset_card c
		WHERE
			isDel = 0 
		AND	isTemp = 0
		AND	c.assetName <> ''
		AND c.assetName IS NOT NULL
		AND c.useStatusCode = 'SYZT-SYZ'
		$wirteDate
		$condition
		GROUP BY
			c.assetName
		UNION ALL
		SELECT
			c.assetName,
			'报废' AS useStatusName,
			COUNT(*) AS num,
			4 AS sort
		FROM
			oa_asset_card c
		WHERE
			isDel = 0 
		AND	isTemp = 0
		AND	c.assetName <> ''
		AND c.assetName IS NOT NULL
		AND (
			c.useStatusCode = 'SYZT-DBF'
			OR c.useStatusCode = 'SYZT-YBF'
			OR c.useStatusCode = 'SYZT-WCS'
			OR c.useStatusCode = 'SYZT-YQL'
			OR c.useStatusCode = 'SYZT-WXZ'
		)
		$updateTime
		$condition
		GROUP BY
			c.assetName
	) total
ORDER BY
	total.sort,
	total.assetName
QuerySQL;
GenAttrXmlData($sql,false);