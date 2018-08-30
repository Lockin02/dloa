<?php

/**
 * 资产报废明细sql
 *@linzx
 */
$sql_arr = array (
	"select_scrapItem" => "select s.id,s.allocateID,s.sequence,s.assetId," .
			"s.assetCode,s.assetName,s.buyDate,s.spec,s.depreciation,s.origina," .
			"s.salvage,s.remark,s.sellStatus,s.createName,s.createId," .
			"s.createTime,s.updateName,s.updateId,s.updateTime,s.loseId,s.netValue
			from oa_asset_scrapitem s where 1=1",
	"select_card"=>"select s.id,s.allocateID,s.sequence,s.assetId," .
			"s.assetCode,s.assetName,s.buyDate,s.spec,s.depreciation,s.origina," .
			"s.salvage,s.remark,s.sellStatus,s.createName,s.createId," .
			"s.createTime,s.updateName,s.updateId,s.updateTime,s.loseId,".
            "c.id as cid ,c.assetabbrev,c.assetCode ,c.assetName ,c.englishName ,c.machineCode ,c.assetTypeId," .
         	"c.assetTypeName ,c.unit ,c.buyDate ,c.wirteDate ,c.place ,c.useType ,c.useStatusCode ,c.useStatusName," .
         	"c.changeTypeCode ,c.changeTypeName ,c.spec ,c.origin ,c.supplierName ,c.supplierId ,c.manufacturers," .
         	"c.remark ,c.deprCode ,c.deprName ,c.subId ,c.subName ,c.depSubId ,c.depSubName ,c.userId ,c.userName," .
         	"c.useOrgId ,c.useOrgName ,c.useProId ,c.useProCode ,c.useProName ,c.orgId ,c.orgName ,c.origina," .
         	"c.buyDepr ,c.beginTime ,c.estimateDay ,c.alreadyDay ,c.depreciation ,c.salvage ,c.netValue ,c.version," .
         	"c.isDel ,c.ExaStatus ,c.ExaDT ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId," .
         	"c.updateTime,c.isTemp,c.isSell
			from oa_asset_scrapitem s left join oa_asset_card c on s.assetId = c.id where 1=1 "
);
$condition_arr = array (
    array (
        "name" => "id",
        "sql" => "and s.id = #"
    ),
    array (
        "name" => "loseId",
        "sql" => "and s.loseId = #"
    ),
	array (
		"name" => "assetName",
		"sql" => "and s.assetName like CONCAT('%',#,'%')"
	),
	array (
		"name" => "assetName",
		"sql" => "and c.assetName like CONCAT('%',#,'%')"
	),
	array (
		"name" => "allocateID",
		"sql" => "and s.allocateID =#"
	),
	array (
		"name" => "sellStatus",
		"sql" => "and s.sellStatus like CONCAT('%',#,'%')"
	),
	array (
		"name" => "sequence",
		"sql" => "and s.sequence = #"
	),
	array (
		"name" => "assetId",
		"sql" => "and s.assetId = #"
	),
	array (
		"name" => "assetCode",
		"sql" => "and s.assetCode like CONCAT('%',#,'%')"
	),
	array (
		"name" => "assetCode",
		"sql" => "and c.assetCode like CONCAT('%',#,'%')"
	),
	array (
		"name" => "buyDate",
		"sql" => "and s.buyDate = #"
	),
	array (
		"name" => "spec",
		"sql" => "and s.spec = #"
	),array (
		"name" => "depreciation",
		"sql" => "and s.depreciation =#"
	),
	array (
		"name" => "salvage",
		"sql" => "and s.salvage = #"
	),
	array (
		"name" => "remark",
		"sql" => "and s.remark   = #"
	),
	array (
		"name" => "createName",
		"sql" => "and s.createName in(arr)"
	),
	array (
		"name" => "createTime",
		"sql" => "and s.createTime = #"
	),
	array (
		"name" => "updateName",
		"sql" => "and s.updateName = #"
	),
	array (
		"name" => "updateId",
		"sql" => "and s.updateId = #"
	),
	array (
		"name" => "updateTime",
		"sql" => "and s.updateTime = #"
	),
	array (  //自定义条件
		"name" => "cardsCondition",
		"sql" => "$"
	)
);
?>
