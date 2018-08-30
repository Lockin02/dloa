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
$assetName = isset($_GET['assetName']) ? $_GET['assetName'] : "";  //�ʲ�����
$useStatusName = isset($_GET['useStatusName']) ? $_GET['useStatusName'] : "";  //�ʲ�״̬
$userId = isset($_GET['userId']) ? $_GET['userId'] : "";  //�û�id
$deptLimit = isset($_GET['deptLimit']) ? $_GET['deptLimit'] : "";  //����Ȩ�޴����ʶ
$agencyLimit = isset($_GET['agencyLimit']) ? $_GET['agencyLimit'] : "";  //����Ȩ�޴����ʶ

$condition = "";
//����״̬���ʲ��Ծ������ʱ��Ϊ׼������״̬���Թ������ڻ���������Ϊ׼
$date = " and c.".$dateType." >= '$beginDate' and c.".$dateType." <= '$endDate'";
$updateTime = " and date_format(c.updateTime,'%Y-%m-%d') >= '$beginDate' and date_format(c.updateTime,'%Y-%m-%d') <= '$endDate'";
//������˾
if($company != 'all'){
	$condition.=" and c.companyCode = '$company'";
}
//�ʲ�����
if($assetName){
	$condition.=" and c.assetName = '$assetName'";
}
//�ʲ�״̬
if($useStatusName){
	switch($useStatusName){
		case '����' : $condition.=" and (c.useStatusCode = 'SYZT-DTK' or c.useStatusCode = 'SYZT-YTK') ".$date; break;
		case '���' : $condition.=" and c.useStatusCode = 'SYZT-XZ' ".$date; break;
		case 'ʹ��' : $condition.=" and c.useStatusCode = 'SYZT-SYZ' ".$date; break;
		case 'ʹ����' : $condition.=" and c.useStatusCode = 'SYZT-SYZ' ".$date; break;
		case '����' : $condition.=" and (c.useStatusCode = 'SYZT-DBF' or c.useStatusCode = 'SYZT-YBF' or c.useStatusCode = 'SYZT-WCS' or 
								c.useStatusCode = 'SYZT-YQL' or c.useStatusCode = 'SYZT-WXZ') ".$updateTime; break;
		case '�Ǳ���' : $condition.=" and c.useStatusCode <> 'SYZT-DBF' and c.useStatusCode <> 'SYZT-YBF' and c.useStatusCode <> 'SYZT-WCS' and
								c.useStatusCode <> 'SYZT-YQL' and c.useStatusCode <> 'SYZT-WXZ' ".$date; break;
		case '�ܼ�(��˾����)' : $condition.=" and (c.useStatusCode = 'SYZT-XZ' or c.useStatusCode = 'SYZT-SYZ' or c.useStatusCode = 'SYZT-SYZ' or c.useStatusCode = 'SYZT-DBF'
								or c.useStatusCode = 'SYZT-YBF' or c.useStatusCode = 'SYZT-WCS' or c.useStatusCode = 'SYZT-YQL' or c.useStatusCode = 'SYZT-WXZ') ".$date; break;
		case '�ܼ�(�������)' : $condition.=" and (c.useStatusCode = 'SYZT-XZ' or c.useStatusCode = 'SYZT-SYZ') ".$date; break;
	}
}else{
	$condition.=$date;
}
//��������Ȩ�޻���Ȩ��
if($agencyCodeStr || $deptIdStr){
	if($agencyLimit != 'none'){//��Ҫ��������Ȩ�޴���
		//������������
		if($agencyCode != 'all'){
			$condition.=" and c.agencyCode = '$agencyCode'";
		}else{
			//����Ȩ��
			if($agencyCodeStr && strstr($agencyCodeStr,';;') == false){
				$condition.=" and c.agencyCode in($agencyCodeStr)";
			}
		}
	}
	if($deptLimit != 'none'){//��Ҫ���в���Ȩ�޴���
		//ʹ�ò���
		if($deptId){
			$condition.=" and (c.useOrgId in($deptId) or c.parentUseOrgId in($deptId))";
		}else{
			//����Ȩ��
			if($deptIdStr && strstr($deptIdStr,';;') == false){
				$condition.=" and (c.useOrgId in($deptIdStr) or c.parentUseOrgId in($deptIdStr))";
			}
		}
	}
}else{//�������κ�Ȩ������ʾ�Լ����µ��ʲ�
	$condition.=" and (c.userId = '$userId' or c.belongManId = '$userId')";
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
AND c.property = 0
$condition
ORDER BY
	c.assetName,
	c.parentUseOrgId,
	c.useOrgId
QuerySQL;
GenAttrXmlData($sql,false);