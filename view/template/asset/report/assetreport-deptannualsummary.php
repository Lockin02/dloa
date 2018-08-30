<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
set_time_limit(0);
$dateType = $_GET['dateType'];  //��������
$beginDate = $_GET['beginDate'];  //��ʼ����
$endDate = $_GET['endDate']; //��������
$company = isset($_GET['company']) ? $_GET['company'] : ""; //��˾
$deptId =  isset($_GET['deptId']) ? $_GET['deptId'] : "";;  //����
$deptName =  isset($_GET['deptName']) ? $_GET['deptName'] : "";
$deptIdStr =  isset($_GET['deptIdStr']) ? $_GET['deptIdStr'] : "";;  //����Ȩ��

//���ݹ������ڻ��������ڹ�������
$condition= " and c.".$dateType." >= '$beginDate' and c.".$dateType." <= '$endDate'";
//������˾
if($company != 'all'){
	$condition.=" and c.companyCode = '$company'";
}
//ʹ�ò���
if($deptId){
	$condition.=" and (c.useOrgId in($deptId) or c.parentUseOrgId in($deptId))";
}else{
	//����Ȩ��
	if($deptIdStr && strstr($deptIdStr,';;') == false){//���ڲ���Ȩ��,�Ҳ�Ϊ����
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
			'����' AS assetName,
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