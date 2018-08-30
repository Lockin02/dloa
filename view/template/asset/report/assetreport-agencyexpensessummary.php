<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
set_time_limit(0);
$dateType = $_GET['dateType'];  //��������
$beginDate = $_GET['beginDate'];  //��ʼ����
$endDate = $_GET['endDate']; //��������
$company = isset($_GET['company']) ? $_GET['company'] : "";  //��˾
$deptId =  isset($_GET['deptId']) ? $_GET['deptId'] : "";;  //����

//���ݹ������ڻ��������ڹ�������,��ʽyyyy-MM
$condition= " and date_format(c.".$dateType." ,'%Y-%m') >= '$beginDate' and date_format(c.".$dateType." ,'%Y-%m') <= '$endDate'";
//������˾
if($company != 'all'){
	$condition.=" and c.companyCode = '$company'";
}
//ʹ�ò���
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
	'����' AS month,
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