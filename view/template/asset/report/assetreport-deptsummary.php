<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
set_time_limit(0);
$dateType = $_GET['dateType'];  //��������
$beginDate = $_GET['beginDate'];  //��ʼ����
$endDate = $_GET['endDate']; //��������
$dateFmt = $_GET['dateFmt']; //���ڸ�ʽ
$company = isset($_GET['company']) ? $_GET['company'] : "";  //��˾
$deptId =  isset($_GET['deptId']) ? $_GET['deptId'] : "";  //����
$deptIdStr =  isset($_GET['deptIdStr']) ? $_GET['deptIdStr'] : "";;  //����Ȩ��

$condition = "";
//���ݹ������ڻ��������ڹ�������
if($dateType){
	if($dateFmt == 'month'){//���ھ�ȷ���·�
		$condition.=" and date_format(c.".$dateType." ,'%Y-%m') >= '$beginDate' and date_format(c.".$dateType." ,'%Y-%m') <= '$endDate'";
	}else{
		$condition.=" and c.".$dateType." >= '$beginDate' and c.".$dateType." <= '$endDate'";
	}
}
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
		IFNULL(a.typeName,'�̶���Ŀ') as typeName,
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
			'�ܼ�' as parentUseOrgName,
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