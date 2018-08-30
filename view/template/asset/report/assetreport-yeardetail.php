<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
set_time_limit(0);
$dateType = $_GET['dateType'];  //��������
$year = $_GET['year'];  //���
$company = isset($_GET['company']) ? $_GET['company'] : "";  //��˾
$deptId =  isset($_GET['deptId']) ? $_GET['deptId'] : "";;  //����
$deptIdStr =  isset($_GET['deptIdStr']) ? $_GET['deptIdStr'] : "";;  //����Ȩ��
$agencyCode = isset($_GET['agencyCode']) ? $_GET['agencyCode'] : "";  //��������
$agencyCodeStr = isset($_GET['agencyCodeStr']) ? $_GET['agencyCodeStr'] : "";  //����Ȩ��
$userId = isset($_GET['userId']) ? $_GET['userId'] : "";  //�û�id
$deptLimit = isset($_GET['deptLimit']) ? $_GET['deptLimit'] : "";  //����Ȩ�޴����ʶ
$agencyLimit = isset($_GET['agencyLimit']) ? $_GET['agencyLimit'] : "";  //����Ȩ�޴����ʶ

//���ݹ������ڻ��������ڹ�������
$condition = " and date_format(c.".$dateType." ,'%Y') = '$year'";
//������˾
if($company != 'all'){
	$condition.=" and c.companyCode = '$company'";
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
	c.remark,
	$year as year
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