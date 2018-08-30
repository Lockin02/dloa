<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
set_time_limit(0);
$dateType = $_GET['dateType'];  //��������
$beginDate = $_GET['beginDate'];  //��ʼ����
$endDate = $_GET['endDate']; //��������
$dateFmt = $_GET['dateFmt']; //���ڸ�ʽ
$company = isset($_GET['company']) ? $_GET['company'] : "";  //��˾
$deptId =  $_GET['deptId'];  //����
$deptName = $_GET['deptName'];

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

$sql = <<<QuerySQL
SELECT
 c.id,
 c.assetName,
'�̶��ʲ�' AS property,
 c.assetTypeName,
 c.assetCode,
 c.brand,
 c.englishName,
 c.spec,
 c.unit,
 c.deploy,
 c.mobileNetwork,
 c.mobileBand,
 c.machineCode,
 c.userName,
 c.parentUseOrgName,
 c.useOrgName,
 c.belongMan,
 c.parentOrgName,
 c.orgName,
 c.agencyName,
 c.assetSourceName,
 c.useStatusName,
 c.useProName,
 c.requireCode,
 c.origina,
 c.salvage,
 c.buyDate,
 c.wirteDate,
 c.beginTime,
 c.estimateDay,
 c.alreadyDay,
 c.remark
FROM
	oa_asset_card c
WHERE
	c.isDel = 0 
AND	c.isTemp = 0
AND	c.parentUseOrgId = $deptId
AND c.property = 0
$condition
ORDER BY
	c.assetName
QuerySQL;
GenAttrXmlData($sql,false);