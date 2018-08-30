<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
set_time_limit(0);
$dateType = $_GET['dateType'];  //��������
$beginDate = $_GET['beginDate'];  //��ʼ����
$endDate = $_GET['endDate']; //��������
$dateFmt = $_GET['dateFmt']; //���ڸ�ʽ
$company = isset($_GET['company']) ? $_GET['company'] : "";  //��˾
$deptId =  isset($_GET['deptId']) ? $_GET['deptId'] : "";;  //����
$deptIdStr =  isset($_GET['deptIdStr']) ? $_GET['deptIdStr'] : "";;  //����Ȩ��
$agencyCode = isset($_GET['agencyCode']) ? $_GET['agencyCode'] : "";  //��������
$agencyCodeStr = isset($_GET['agencyCodeStr']) ? $_GET['agencyCodeStr'] : "";  //����Ȩ��
$userId = isset($_GET['userId']) ? $_GET['userId'] : "";  //�û�id

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
//��������Ȩ�޻���Ȩ��
if($agencyCodeStr || $deptIdStr){
	//������������
	if($agencyCode != 'all'){
		$condition.=" and c.agencyCode = '$agencyCode'";
	}else{
		//����Ȩ��
		if($agencyCodeStr && strstr($agencyCodeStr,';;') == false){
			$condition.=" and c.agencyCode in($agencyCodeStr)";
		}
	}
	//ʹ�ò���
	if($deptId){
		$condition.=" and (c.useOrgId in($deptId) or c.parentUseOrgId in($deptId))";
	}else{
		//����Ȩ��
		if($deptIdStr && strstr($deptIdStr,';;') == false){
			$condition.=" and (c.useOrgId in($deptIdStr) or c.parentUseOrgId in($deptIdStr))";
		}
	}
}else{//�������κ�Ȩ������ʾ�Լ����µ��ʲ�
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
			IFNULL(a.typeName,'�̶���Ŀ') as typeName,
			c.assetName,
			'���' AS useStatusName,
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
			IFNULL(a.typeName,'�̶���Ŀ') as typeName,
			c.assetName,
			'ʹ����' AS useStatusName,
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
			'�ܼ�' AS agencyName,
			'' AS agencyCode,
			'' as typeName,
			'' AS assetName,
			'���' AS useStatusName,
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
			'�ܼ�' AS agencyName,
			'' AS agencyCode,
			'' as typeName,
			'' AS assetName,
			'ʹ����' AS useStatusName,
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