<?php
/**
 * @author Administrator
 * @Date 2011年11月18日 17:36:26
 * @version 1.0
 * @description:固定资产卡片 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.isBelong,c.belongToCode,c.belongTo,c.property,c.monthlyDepreciation,c.agencyName,c.agencyCode,c.companyCode,c.companyName,c.idle,c.productCode,c.productName,c.productId,c.belongMan,c.belongManId,c.assetSource,c.brand,c.assetSourceName,c.id ,c.assetabbrev,c.assetCode ,c.assetName ,c.englishName ,c.machineCode ,c.assetTypeId " .
         		",c.assetTypeName ,c.unit ,c.buyDate ,c.wirteDate ,c.place ,c.useType ,c.useStatusCode ,c.useStatusName " .
         		",c.changeTypeCode ,c.changeTypeName ,c.spec ,c.origin ,c.supplierName ,c.supplierId ,c.manufacturers " .
         		",c.remark ,c.deprCode ,c.deprName ,c.subId ,c.subName ,c.depSubId ,c.depSubName ,c.userId ,c.userName " .
         		",c.useOrgId ,c.useOrgName ,c.parentUseOrgId ,c.parentUseOrgName ,c.useProId ,c.useProCode ,c.useProName ,c.orgId ,c.orgName ,c.parentOrgId ,c.parentOrgName ,c.origina " .
         		",c.buyDepr ,c.beginTime ,c.estimateDay ,c.alreadyDay ,c.depreciation ,c.salvage ,c.netValue ,c.version " .
         		",c.isDel ,c.ExaStatus ,c.ExaDT ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId,c.isSell,c.isDeprf,c.isScrap " .
         		",c.updateTime,c.isTemp,c.deploy,c.requireId,c.requireCode,c.mobileBand,c.mobileNetwork from oa_asset_card c where 1=1 and isDel=0 and isTemp = 0",
         "select_list"=>"select c.isBelong,c.belongToCode,c.belongTo,c.property,c.monthlyDepreciation,c.agencyName,c.agencyCode,c.companyCode,c.companyName,c.idle,c.productCode,c.productName,c.productId,c.belongMan,c.belongManId,c.assetSource,c.brand,c.assetSourceName,c.id ,c.assetabbrev,c.assetCode ,c.assetName ,c.englishName ,c.machineCode ,c.assetTypeId " .
         		",c.assetTypeName ,c.unit ,c.buyDate ,c.wirteDate ,c.place ,c.useType ,c.useStatusCode ,c.useStatusName " .
         		",c.changeTypeCode ,c.changeTypeName ,c.spec ,c.origin ,c.supplierName ,c.supplierId ,c.manufacturers " .
         		",c.remark ,c.deprCode ,c.deprName ,c.subId ,c.subName ,c.depSubId ,c.depSubName ,c.userId ,c.userName " .
         		",c.useOrgId ,c.useOrgName ,c.parentUseOrgId ,c.parentUseOrgName ,c.useProId ,c.useProCode ,c.useProName ,c.orgId ,c.orgName ,c.parentOrgId ,c.parentOrgName ,c.origina " .
         		",c.buyDepr ,c.beginTime ,c.estimateDay ,c.alreadyDay ,c.depreciation ,c.salvage ,c.netValue ,c.version " .
         		",c.isDel ,c.ExaStatus ,c.ExaDT ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId,c.isSell,c.isDeprf,c.isScrap " .
         		",c.updateTime,c.isTemp,c.deploy,c.requireId,c.requireCode,c.mobileBand,c.mobileNetwork from oa_asset_card c where 1=1 and isDel=0 and isTemp = 0",
         "select_other"=>"select c.isBelong,c.belongToCode,c.belongTo,c.property,c.monthlyDepreciation,c.agencyName,c.agencyCode,c.companyCode,c.companyName,c.idle,c.productCode,c.productName,c.productId,c.belongMan,c.belongManId,c.assetSource,c.brand,c.assetSourceName,c.id ,c.assetabbrev,c.assetCode ,c.assetName ,c.englishName ,c.machineCode ,c.assetTypeId " .
         		",c.assetTypeName ,c.unit ,c.buyDate ,c.wirteDate ,c.place ,c.useType ,c.useStatusCode ,c.useStatusName " .
         		",c.changeTypeCode ,c.changeTypeName ,c.spec ,c.origin ,c.supplierName ,c.supplierId ,c.manufacturers " .
         		",c.remark ,c.deprCode ,c.deprName ,c.subId ,c.subName ,c.depSubId ,c.depSubName ,c.userId ,c.userName " .
         		",c.useOrgId ,c.useOrgName ,c.parentUseOrgId ,c.parentUseOrgName ,c.useProId ,c.useProCode ,c.useProName ,c.orgId ,c.orgName ,c.parentOrgId ,c.parentOrgName ,c.origina " .
         		",c.buyDepr ,c.beginTime ,c.estimateDay ,c.alreadyDay ,c.depreciation ,c.salvage ,c.netValue ,c.version " .
         		",c.isDel ,c.ExaStatus ,c.ExaDT ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId,c.isSell,c.isDeprf,c.isScrap " .
         		",c.updateTime,c.isTemp,c.deploy,c.requireId,c.requireCode,c.mobileBand,c.mobileNetwork from oa_asset_card c where 1=1"
);

$condition_arr = array (
    array(
   		"name" => "belongToCode",
   		"sql" => " and c.belongToCode like CONCAT('%',#,'%') "
   	  ),
    array(
   		"name" => "isBelong",
   		"sql" => " and c.isBelong=# "
   	  ),
    array(
   		"name" => "belongTo",
   		"sql" => " and c.belongTo=# "
   	  ),
    array(
   		"name" => "property",
   		"sql" => " and c.property=# "
   	  ),
    array(
   		"name" => "currentId",
   		"sql" => " and (c.userId=# or c.belongManId=#) "
   	  ),
    array(
   		"name" => "brand",
   		"sql" => " and c.brand like CONCAT('%',#,'%') "
   	  ),
    array(
   		"name" => "agencyName",
   		"sql" => " and c.agencyName like CONCAT('%',#,'%') "
   	  ),
	array(
   		"name" => "agencyCode",
   		"sql" => " and c.agencyCode=# "
        ),
    array(
   		"name" => "companyName",
   		"sql" => " and c.companyName like CONCAT('%',#,'%') "
   	  ),
	array(
   		"name" => "companyCode",
   		"sql" => " and c.companyCode=# "
        ),
	array(
   		"name" => "idle",
   		"sql" => " and c.idle=# "
        ),
    array(
   		"name" => "productCode",
   		"sql" => " and c.productCode like CONCAT('%',#,'%') "
   	  ),
    array(
   		"name" => "productName",
   		"sql" => " and c.productName like CONCAT('%',#,'%') "
   	  ),
	array(
   		"name" => "productId",
   		"sql" => " and c.productId=# "
        ),
    array(
   		"name" => "belongMan",
   		"sql" => " and c.belongMan like CONCAT('%',#,'%') "
   	  ),
	array(
   		"name" => "belongManId",
   		"sql" => " and c.belongManId=# "
        ),
	array(
   		"name" => "id",
   		"sql" => " and c.id=# "
        ),
	array(
   		"name" => "ids",
   		"sql" => " and c.id in(arr) "
        ),
	array(
   		"name" => "assetSource",
   		"sql" => " and c.assetSource =# "
        ),
	array(
   		"name" => "assetSourceName",
   		"sql" => " and c.assetSourceName =# "
        ),
	array(
		"name" => "assetSourceNameSer",
		"sql" => " and c.assetSourceName like CONCAT('%',#,'%') "
	),
    array(
   		"name" => "assetabbrev",
   		"sql" => " and c.assetabbrev like CONCAT('%',#,'%') "
   	  ),
   	array(
   		"name" => "isSell",
   		"sql" => " and c.isSell like CONCAT('%',#,'%') "
   	  ),
     array(
   		"name" => "isScrap",
   		"sql" => " and c.isScrap =#"
   	  ),
   array(
   		"name" => "assetCode",
   		"sql" => " and c.assetCode like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "assetCodeAbr",
   		"sql" => " and c.assetCode =#"
   	  ),
   	array(
   		"name" => "assetCodeEq",
   		"sql" => " and c.assetCode =#"
   	  ),
   array(
   		"name" => "assetName",
   		"sql" => " and c.assetName like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "englishName",
   		"sql" => " and c.englishName=# "
   	  ),
   array(
   		"name" => "machineCodeSearch",
//   		"sql" => " and ((c.property=1 and c.machineCode='') or (c.property=0 and c.machineCode<>''))"
   		"sql" => " and ((c.property=1) or (c.property=0 and c.machineCode<>''))"
   	  ),
   array(
   		"name" => "machineCode",
   		"sql" => " and c.machineCode=# "
   	  ),
   array(
		"name" => "machineCodeSer",
		"sql" => " and c.machineCode like CONCAT('%',#,'%') "
	),
   array(
   		"name" => "assetTypeId",
   		"sql" => " and c.assetTypeId=# "
   	  ),
   array(
   		"name" => "assetTypeName",
   		"sql" => " and c.assetTypeName like CONCAT('%',#,'%')  "
   	  ),
   array(
   		"name" => "unit",
   		"sql" => " and c.unit=# "
   	  ),
   array(
   		"name" => "buyDate",
   		"sql" => " and c.buyDate=# "
   	  ),
   array(
   		"name" => "wirteDate",
   		"sql" => " and c.wirteDate=# "
   	  ),
   array(
   		"name" => "place",
   		"sql" => " and c.place=# "
   	  ),
   array(
   		"name" => "useType",
   		"sql" => " and c.useType=# "
   	  ),
   array(
   		"name" => "useStatusCode",
   		"sql" => " and c.useStatusCode=# "
   	  ),
	array(
		"name" => "useStatusCodeArr",
		"sql" => " and c.useStatusCode in(arr) "
	),
   array(
   		"name" => "useStatusName",
   		"sql" => " and c.useStatusName like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "changeTypeCode",
   		"sql" => " and c.changeTypeCode=# "
   	  ),
   array(
   		"name" => "changeTypeName",
   		"sql" => " and c.changeTypeName like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "spec",
   		"sql" => " and c.spec like CONCAT('%',#,'%') "
   	  ),
	array(
		"name" => "deploy",
		"sql" => " and c.deploy like CONCAT('%',#,'%') "
	  ),
   array(
   		"name" => "origin",
   		"sql" => " and c.origin=# "
   	  ),
   array(
   		"name" => "supplierName",
   		"sql" => " and c.supplierName=# "
   	  ),
   array(
   		"name" => "supplierId",
   		"sql" => " and c.supplierId=# "
   	  ),
   array(
   		"name" => "manufacturers",
   		"sql" => " and c.manufacturers=# "
   	  ),
   array(
   		"name" => "remark",
   		"sql" => " and c.remark=# "
   	  ),
   array(
   		"name" => "deprCode",
   		"sql" => " and c.deprCode=# "
   	  ),
   array(
   		"name" => "deprName",
   		"sql" => " and c.deprName=# "
   	  ),
   array(
   		"name" => "subId",
   		"sql" => " and c.subId=# "
   	  ),
   array(
   		"name" => "subName",
   		"sql" => " and c.subName=# "
   	  ),
   array(
   		"name" => "depSubId",
   		"sql" => " and c.depSubId=# "
   	  ),
   array(
   		"name" => "depSubName",
   		"sql" => " and c.depSubName=# "
   	  ),
   array(
   		"name" => "userId",
   		"sql" => " and c.userId=# "
   	  ),
   array(
   		"name" => "userName",
   		"sql" => " and c.userName like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "useOrgId",
   		"sql" => " and c.useOrgId=# "
   	  ),
   array(
   		"name" => "useOrgName",
   		"sql" => " and c.useOrgName like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "hasUseProId",
   		"sql" => " and c.useProId <> 0 "
   	  ),
   array(
   		"name" => "projectId",
   		"sql" => " and c.useProId =# "
   	  ),
   array(
   		"name" => "useProId",
   		"sql" => " and c.useProId=# "
   	  ),
   array(
   		"name" => "useProCode",
   		"sql" => " and c.useProCode=# "
   	  ),
   array(
   		"name" => "useProName",
   		"sql" => " and c.useProName=# "
   	  ),
   array(
   		"name" => "orgId",
   		"sql" => " and c.orgId=# "
   	  ),
   array(
   		"name" => "deptId",
   		"sql" => " and c.orgId=# "
   	  ),
   array(
   		"name" => "orgName",
   		"sql" => " and c.orgName like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "origina",
   		"sql" => " and c.origina=# "
   	  ),
   array(
   		"name" => "buyDepr",
   		"sql" => " and c.buyDepr=# "
   	  ),
   array(
   		"name" => "beginTime",
   		"sql" => " and c.beginTime=# "
   	  ),
   array(
   		"name" => "estimateDay",
   		"sql" => " and c.estimateDay=# "
   	  ),
   array(
   		"name" => "alreadyDay",
   		"sql" => " and c.alreadyDay=# "
   	  ),
   array(
   		"name" => "depreciation",
   		"sql" => " and c.depreciation=# "
   	  ),
   array(
   		"name" => "salvage",
   		"sql" => " and c.salvage=# "
   	  ),
   array(
   		"name" => "netValue",
   		"sql" => " and c.netValue=# "
   	  ),
   array(
   		"name" => "version",
   		"sql" => " and c.version=# "
   	  ),
   array(
   		"name" => "isDel",
   		"sql" => " and c.isDel=# "
   	  ),
   array(
   		"name" => "ExaStatus",
   		"sql" => " and c.ExaStatus=# "
   	  ),
   array(
   		"name" => "ExaDT",
   		"sql" => " and c.ExaDT=# "
   	  ),
   array(
   		"name" => "createName",
   		"sql" => " and c.createName=# "
   	  ),
   array(
   		"name" => "createId",
   		"sql" => " and c.createId=# "
   	  ),
   array(
   		"name" => "createTime",
   		"sql" => " and c.createTime=# "
   	  ),
   array(
   		"name" => "updateName",
   		"sql" => " and c.updateName=# "
   	  ),
   array(
   		"name" => "updateId",
   		"sql" => " and c.updateId=# "
   	  ),
   array(
   		"name" => "updateTime",
   		"sql" => " and c.updateTime=# "
   	  ),

   array(
   		"name" => "isTemp",
   		"sql" => " and c.isTemp=# "
   	  ),
   array(
   		"name" => "isDeprf",
   		"sql" => " and c.isDeprf=# "
   	  ),
	array(
		"name" => "requireId",
		"sql" => " and c.requireId=# "
	),
	array(
		"name" => "requireCode",
		"sql" => " and c.requireCode like CONCAT('%',#,'%') "
	),
	array(
		"name" => "mobileBand",
		"sql" => " and c.mobileBand like CONCAT('%',#,'%') "
	),
	array(
		"name" => "mobileNetwork",
		"sql" => " and c.mobileNetwork like CONCAT('%',#,'%') "
	),
   //自定义条件
   array(
		"name" => "agencyCondition",
		"sql" => "$"
	),
   array(
		"name" => "comboAssetInfo",
		"sql" => "$"
	)
)
?>