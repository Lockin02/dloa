<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
set_time_limit(0);
$dateType = $_GET['dateType'];  //��������
$beginDate = $_GET['beginDate'];  //��ʼ����
$endDate = $_GET['endDate']; //��������
$company = isset($_GET['company']) ? $_GET['company'] : "";  //��˾
$deptId =  isset($_GET['deptId']) ? $_GET['deptId'] : "";;  //����
$deptIdStr =  isset($_GET['deptIdStr']) ? $_GET['deptIdStr'] : "";;  //����Ȩ��
$agencyCode = isset($_GET['agencyCode']) ? $_GET['agencyCode'] : "";  //��������
$agencyCodeStr = isset($_GET['agencyCodeStr']) ? $_GET['agencyCodeStr'] : "";  //����Ȩ��
$userId = isset($_GET['userId']) ? $_GET['userId'] : "";  //�û�id

$condition = "";
//����״̬���ʲ��Ծ������ʱ��Ϊ׼������״̬���Թ������ڻ���������Ϊ׼
$date = " and c.".$dateType." >= '$beginDate' and c.".$dateType." <= '$endDate'";
$updateTime = " and date_format(c.updateTime,'%Y-%m-%d') >= '$beginDate' and date_format(c.updateTime,'%Y-%m-%d') <= '$endDate'";
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
	total.typeName,
	total.assetName,
	total.useStatusName,
	total.num,
	total.origina,
	total.sort
FROM
	(
		SELECT
			IFNULL(a.typeName,'�̶���Ŀ') as typeName,
			c.assetName,
			'���' AS useStatusName,
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
			IFNULL(a.typeName,'�̶���Ŀ') as typeName,
			c.assetName,
			'ʹ����' AS useStatusName,
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
			IFNULL(a.typeName,'�̶���Ŀ') as typeName,
			c.assetName,
			'����' AS useStatusName,
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
			'�ܼ�' as typeName,
			'' as assetName,
			'���' AS useStatusName,
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
			'�ܼ�' as typeName,
			'' as assetName,
			'ʹ����' AS useStatusName,
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
			'�ܼ�' as typeName,
			'' as assetName,
			'����' AS useStatusName,
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