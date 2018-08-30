<?php
/**
 * @author Administrator
 * @Date 2011年11月23日 9:51:30
 * @version 1.0
 * @description:变动记录 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.alterDate ,c.explanation ,c.needVoucher ,c.newAlter ,c.otherAlter ,c.period " .
         		",c.years ,c.assetId ,c.businessType ,c.businessId ,c.createName ,c.createId ,c.createTime ,c.updateName " .
         		",c.updateId ,c.updateTime ,c.assetCode  from oa_asset_alter c where 1=1 ",
         "select_changeRecord"=>"select c.id ,c.alterDate ,c.assetId ,c.oldAssetId,c.businessType ,c.businessId ,c.businessCode ,c.assetCode ,ac.id as acId " .
   				",ac.assetName ,ac.assetCode ,ac.assetTypeName ,ac.assetTypeId ,ac.unit ,ac.buyDate ,ac.wirteDate " .
   				",ac.place ,ac.useType ,ac.useStatusCode ,ac.useStatusName ,ac.changeTypeCode ,ac.changeTypeName ,ac.spec " .
   				",ac.origin ,ac.supplierName ,ac.supplierId ,ac.manufacturers ,ac.deprCode ,ac.deprName ,ac.subId " .
   				",ac.subName ,ac.depSubId ,ac.depSubName ,ac.userId ,ac.userName ,ac.useOrgId ,ac.useOrgName " .
   				",ac.useProId ,ac.useProCode ,ac.useProName ,ac.orgId ,ac.orgName ,ac.origina ,ac.buyDepr " .
   				",ac.depreciation ,ac.salvage ,ac.netValue ,ac.version from oa_asset_alter c left join oa_asset_card ac " .
   				" on c.assetCode=ac.assetCode and c.oldAssetId=ac.id where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
	array(
   		"name" => "assetCode",
   		"sql" => " and c.assetCode=# "
        ),
   array(
   		"name" => "alterDate",
   		"sql" => " and c.alterDate=# "
   	  ),
   array(
   		"name" => "explanation",
   		"sql" => " and c.explanation=# "
   	  ),
   array(
   		"name" => "needVoucher",
   		"sql" => " and c.needVoucher=# "
   	  ),
   array(
   		"name" => "newAlter",
   		"sql" => " and c.newAlter=# "
   	  ),
   array(
   		"name" => "otherAlter",
   		"sql" => " and c.otherAlter=# "
   	  ),
   array(
   		"name" => "period",
   		"sql" => " and c.period=# "
   	  ),
   array(
   		"name" => "years",
   		"sql" => " and c.years=# "
   	  ),
   array(
   		"name" => "assetId",
   		"sql" => " and c.assetId=# "
   	  ),
   array(
   		"name" => "businessType",
   		"sql" => " and c.businessType=# "
   	  ),
   array(
   		"name" => "businessId",
   		"sql" => " and c.businessId=# "
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
   	  )
)
?>