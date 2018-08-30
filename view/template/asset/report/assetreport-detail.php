<?php
include '../../../../webreport/data/mysql_GenXmlData.php';
set_time_limit(0);
$dateType = $_GET['dateType'];  //日期类型
$beginDate = $_GET['beginDate'];  //开始日期
$endDate = $_GET['endDate']; //结束日期
$company = isset($_GET['company']) ? $_GET['company'] : "";  //公司
$deptId =  isset($_GET['deptId']) ? $_GET['deptId'] : "";;  //部门
$deptIdStr =  isset($_GET['deptIdStr']) ? $_GET['deptIdStr'] : "";;  //部门权限
$agencyCode = isset($_GET['agencyCode']) ? $_GET['agencyCode'] : "";  //行政区域
$agencyCodeStr = isset($_GET['agencyCodeStr']) ? $_GET['agencyCodeStr'] : "";  //区域权限
$assetName = isset($_GET['assetName']) ? $_GET['assetName'] : "";  //资产名称
$useStatusName = isset($_GET['useStatusName']) ? $_GET['useStatusName'] : "";  //资产状态
$userId = isset($_GET['userId']) ? $_GET['userId'] : "";  //用户id
$deptLimit = isset($_GET['deptLimit']) ? $_GET['deptLimit'] : "";  //部门权限处理标识
$agencyLimit = isset($_GET['agencyLimit']) ? $_GET['agencyLimit'] : "";  //区域权限处理标识

$condition = "";
//报废状态的资产以具体更新时间为准，其它状态则以购置日期或入账日期为准
$date = " and c.".$dateType." >= '$beginDate' and c.".$dateType." <= '$endDate'";
$updateTime = " and date_format(c.updateTime,'%Y-%m-%d') >= '$beginDate' and date_format(c.updateTime,'%Y-%m-%d') <= '$endDate'";
//所属公司
if($company != 'all'){
	$condition.=" and c.companyCode = '$company'";
}
//资产名称
if($assetName){
	$condition.=" and c.assetName = '$assetName'";
}
//资产状态
if($useStatusName){
	switch($useStatusName){
		case '调库' : $condition.=" and (c.useStatusCode = 'SYZT-DTK' or c.useStatusCode = 'SYZT-YTK') ".$date; break;
		case '库存' : $condition.=" and c.useStatusCode = 'SYZT-XZ' ".$date; break;
		case '使用' : $condition.=" and c.useStatusCode = 'SYZT-SYZ' ".$date; break;
		case '使用中' : $condition.=" and c.useStatusCode = 'SYZT-SYZ' ".$date; break;
		case '报废' : $condition.=" and (c.useStatusCode = 'SYZT-DBF' or c.useStatusCode = 'SYZT-YBF' or c.useStatusCode = 'SYZT-WCS' or 
								c.useStatusCode = 'SYZT-YQL' or c.useStatusCode = 'SYZT-WXZ') ".$updateTime; break;
		case '非报废' : $condition.=" and c.useStatusCode <> 'SYZT-DBF' and c.useStatusCode <> 'SYZT-YBF' and c.useStatusCode <> 'SYZT-WCS' and
								c.useStatusCode <> 'SYZT-YQL' and c.useStatusCode <> 'SYZT-WXZ' ".$date; break;
		case '总计(公司汇总)' : $condition.=" and (c.useStatusCode = 'SYZT-XZ' or c.useStatusCode = 'SYZT-SYZ' or c.useStatusCode = 'SYZT-SYZ' or c.useStatusCode = 'SYZT-DBF'
								or c.useStatusCode = 'SYZT-YBF' or c.useStatusCode = 'SYZT-WCS' or c.useStatusCode = 'SYZT-YQL' or c.useStatusCode = 'SYZT-WXZ') ".$date; break;
		case '总计(区域汇总)' : $condition.=" and (c.useStatusCode = 'SYZT-XZ' or c.useStatusCode = 'SYZT-SYZ') ".$date; break;
	}
}else{
	$condition.=$date;
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