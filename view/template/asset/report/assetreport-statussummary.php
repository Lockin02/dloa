<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
set_time_limit(0);
$beginDate = $_GET['beginDate'];  //��ʼ����
$endDate = $_GET['endDate']; //��������
$company = isset($_GET['company']) ? $_GET['company'] : "";  //��˾
$deptId =  isset($_GET['deptId']) ? $_GET['deptId'] : "";;  //����
$deptIdStr =  isset($_GET['deptIdStr']) ? $_GET['deptIdStr'] : "";;  //����Ȩ��
$agencyCode = isset($_GET['agencyCode']) ? $_GET['agencyCode'] : "";  //��������
$agencyCodeStr = isset($_GET['agencyCodeStr']) ? $_GET['agencyCodeStr'] : "";  //����Ȩ��

$condition = "";
//����״̬���ʲ��Ծ������ʱ��Ϊ׼������״̬������������Ϊ׼
$wirteDate= " and c.wirteDate >= '$beginDate' and c.wirteDate <= '$endDate'";
$updateTime = " and date_format(c.updateTime,'%Y-%m-%d') >= '$beginDate' and date_format(c.updateTime,'%Y-%m-%d') <= '$endDate'";
//������˾
if($company != 'all'){
	$condition.=" and c.companyCode = '$company'";
}
//������������
if($agencyCode != 'all'){
	$condition.=" and c.agencyCode = '$agencyCode'";
}else{
	//����Ȩ��
	if($agencyCodeStr && strstr($agencyCodeStr,';;') == false){
		$condition.=" and c.agencyCode in($agencyCodeStr)";
	}
}
//��������
if($deptId){
	$condition.=" and (c.orgId in($deptId) or c.orgId in(select DEPT_ID from department where DelFlag = 0 and PARENT_ID in($deptId)))";
}else{
	//����Ȩ��
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
			'����' AS useStatusName,
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
			'���' AS useStatusName,
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
			'ʹ��' AS useStatusName,
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
			'����' AS useStatusName,
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