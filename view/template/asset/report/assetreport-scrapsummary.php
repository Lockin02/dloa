<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
set_time_limit(0);
$beginDate = $_GET['beginDate'];  //开始日期
$endDate = $_GET['endDate']; //结束日期
$company = isset($_GET['company']) ? $_GET['company'] : "";  //公司

//报废日期
$condition= " and date_format(c.updateTime,'%Y-%m-%d') >= '$beginDate' and date_format(c.updateTime,'%Y-%m-%d') <= '$endDate' ";
//所属公司
if($company != 'all'){
	$condition.=" and c.companyCode = '$company'";
}

$sql = <<<QuerySQL
SELECT
	total.assetName,
	total.num,
	total.salvage
FROM
	(
		SELECT
			c.assetName,
			COUNT(*) AS num,
			SUM(c.salvage) AS salvage,
			1 AS sort
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
		$condition
		GROUP BY
			c.assetName
		UNION ALL
		SELECT
			'汇总' AS assetName,
			COUNT(*) AS num,
			SUM(c.salvage) AS salvage,
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
		$condition
	) total
ORDER BY
	total.sort
QuerySQL;
GenAttrXmlData($sql,false);