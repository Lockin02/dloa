<?php
/**
 * @author Administrator
 * @Date 2012年10月8日 7:43:09
 * @version 1.0
 * @description:卡片新增临时表 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.origina,c.subName,c.subId,c.depSubName,c.depSubId,c.isFinancial,c.id ,c.assetName ,c.englishName ,c.machineCode ,c.assetTypeId ,c.assetTypeName ,c.unit
         		,c.number ,c.buyDate ,c.wirteDate ,c.place ,c.brand ,c.useType ,c.spec ,c.deploy ,c.origin ,c.supplierName
         		,c.supplierId ,c.manufacturers ,c.remark ,c.assetabbrev ,c.belongManId ,c.belongMan ,c.userId ,c.userName
         		,c.useOrgId ,c.useOrgName ,c.parentUseOrgId ,c.parentUseOrgName ,c.useProId ,c.useProCode ,c.useProName ,c.orgId ,c.orgName ,c.parentOrgId ,c.parentOrgName ,c.companyCode
         		,c.companyName ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime ,c.belongErea
         		,c.assetSource ,c.assetSourceName ,c.productId ,c.productCode ,c.productName ,c.agencyCode ,c.agencyName
         		,c.expenseItems ,c.isCreate,c.property,c.assetCode,c.requireId,c.requireCode,c.mobileBand,c.mobileNetwork from oa_asset_card_temp c where 1=1 ",
         "select_card"=>"select c.origina,c.subName,c.subId,c.depSubName,c.depSubId,c.assetName ,c.englishName ,c.machineCode ,c.assetTypeId ,c.assetTypeName ,c.unit
         		,c.number ,c.buyDate ,c.wirteDate ,c.place ,c.brand ,c.useType ,c.spec ,c.deploy ,c.origin ,c.supplierName
         		,c.supplierId ,c.manufacturers ,c.remark ,c.assetabbrev ,c.belongManId ,c.belongMan ,c.userId ,c.userName
         		,c.useOrgId ,c.useOrgName ,c.parentUseOrgId ,c.parentUseOrgName ,c.useProId ,c.useProCode ,c.useProName ,c.orgId ,c.orgName ,c.parentOrgId ,c.parentOrgName ,c.companyCode
         		,c.companyName ,c.belongErea,c.assetSource ,c.assetSourceName ,c.productId ,c.productCode ,c.productName
         		,c.agencyCode ,c.agencyName ,c.expenseItems ,c.isCreate,c.property,c.assetCode,c.requireId,c.requireCode,c.mobileBand,c.mobileNetwork from oa_asset_card_temp c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "isFinancial",
   		"sql" => " and c.isFinancial=# "
        ),
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "assetName",
   		"sql" => " and c.assetName like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "englishName",
   		"sql" => " and c.englishName like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "machineCode",
   		"sql" => " and c.machineCode like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "assetTypeId",
   		"sql" => " and c.assetTypeId=# "
   	  ),
   array(
   		"name" => "assetTypeName",
   		"sql" => " and c.assetTypeName like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "unit",
   		"sql" => " and c.unit=# "
   	  ),
   array(
   		"name" => "number",
   		"sql" => " and c.number=# "
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
   		"name" => "brand",
   		"sql" => " and c.brand like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "useType",
   		"sql" => " and c.useType=# "
   	  ),
   array(
   		"name" => "spec",
   		"sql" => " and c.spec=# "
   	  ),
   array(
   		"name" => "deploy",
   		"sql" => " and c.deploy=# "
   	  ),
   array(
   		"name" => "origin",
   		"sql" => " and c.origin=# "
   	  ),
   array(
   		"name" => "supplierName",
   		"sql" => " and c.supplierName like CONCAT('%',#,'%') "
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
   		"name" => "assetabbrev",
   		"sql" => " and c.assetabbrev=# "
   	  ),
   array(
   		"name" => "belongManId",
   		"sql" => " and c.belongManId=# "
   	  ),
   array(
   		"name" => "belongMan",
   		"sql" => " and c.belongMan like CONCAT('%',#,'%') "
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
   		"name" => "useProId",
   		"sql" => " and c.useProId=# "
   	  ),
   array(
   		"name" => "useProCode",
   		"sql" => " and c.useProCode like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "useProName",
   		"sql" => " and c.useProName like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "orgId",
   		"sql" => " and c.orgId=# "
   	  ),
   array(
   		"name" => "orgName",
   		"sql" => " and c.orgName like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "companyCode",
   		"sql" => " and c.companyCode=# "
   	  ),
   array(
   		"name" => "companyName",
   		"sql" => " and c.companyName like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "createName",
   		"sql" => " and c.createName like CONCAT('%',#,'%') "
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
   		"name" => "belongErea",
   		"sql" => " and c.belongErea=# "
   	  ),
   array(
   		"name" => "assetSource",
   		"sql" => " and c.assetSource=# "
   	  ),
   array(
   		"name" => "assetSourceName",
   		"sql" => " and c.assetSourceName like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "productId",
   		"sql" => " and c.productId=# "
   	  ),
   array(
   		"name" => "productCode",
   		"sql" => " and c.productCode=# "
   	  ),
   array(
   		"name" => "productName",
   		"sql" => " and c.productName like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "agencyCodeArr",
   		"sql" => " and c.agencyCode in(arr) "
   	  ),
   array(
   		"name" => "agencyCode",
   		"sql" => " and c.agencyCode=# "
   	  ),
   array(
   		"name" => "agencyName",
   		"sql" => " and c.agencyName like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "expenseItems",
   		"sql" => " and c.expenseItems=# "
   	  ),
   array(
   		"name" => "isCreate",
   		"sql" => " and c.isCreate=# "
   	  ),
	array(
		"name" => "requireId",
		"sql" => " and c.requireId=# "
		),
	array(
		"name" => "requireCode",
		"sql" => " and c.requireCode=# "
	),
	array(
		"name" => "assetCodeSearch",
		"sql" => " and c.assetCode like CONCAT('%',#,'%') "
	)
)
?>