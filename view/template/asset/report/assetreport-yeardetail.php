<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
set_time_limit(0);
$dateType = $_GET['dateType'];  //日期类型
$year = $_GET['year'];  //年份
$company = isset($_GET['company']) ? $_GET['company'] : "";  //公司
$deptId =  isset($_GET['deptId']) ? $_GET['deptId'] : "";;  //部门
$deptIdStr =  isset($_GET['deptIdStr']) ? $_GET['deptIdStr'] : "";;  //部门权限
$agencyCode = isset($_GET['agencyCode']) ? $_GET['agencyCode'] : "";  //行政区域
$agencyCodeStr = isset($_GET['agencyCodeStr']) ? $_GET['agencyCodeStr'] : "";  //区域权限
$userId = isset($_GET['userId']) ? $_GET['userId'] : "";  //用户id
$deptLimit = isset($_GET['deptLimit']) ? $_GET['deptLimit'] : "";  //部门权限处理标识
$agencyLimit = isset($_GET['agencyLimit']) ? $_GET['agencyLimit'] : "";  //区域权限处理标识

//根据购置日期或入账日期过滤数据
$condition = " and date_format(c.".$dateType." ,'%Y') = '$year'";
//所属公司
if($company != 'all'){
	$condition.=" and c.companyCode = '$company'";
}
//存在区域权限或部门权限
if($agencyCodeStr || $deptIdStr){
	if($agencyLimit != 'none'){//需要进行区域权限处理
		//所属行政区域
		if($agencyCode != 'all'){
			$condition.=" and c.agencyCode = '$agencyCode'";
		}else{
			//区域权限
			if($agencyCodeStr && strstr($agencyCodeStr,';;') == false){
				$condition.=" and c.agencyCode in($agencyCodeStr)";
			}
		}
	}
	if($deptLimit != 'none'){//需要进行部门权限处理
		//使用部门
		if($deptId){
			$condition.=" and (c.useOrgId in($deptId) or c.parentUseOrgId in($deptId))";
		}else{
			//部门权限
			if($deptIdStr && strstr($deptIdStr,';;') == false){
				$condition.=" and (c.useOrgId in($deptIdStr) or c.parentUseOrgId in($deptIdStr))";
			}
		}
	}
}else{//不存在任何权限则显示自己名下的资产
	$condition.=" and (c.userId = '$userId' or c.belongManId = '$userId')";
}

$sql = <<<QuerySQL
SELECT
	c.id,
	c.assetName,
	'固定资产' AS property,
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