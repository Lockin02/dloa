<?php
/**
 * 资产出售明细表sql
 *@linzx
 */
$sql_arr = array (
	"select_sell" => "select s.id,s.sellID,s.sequence,s.assetId,s.assetCode," .
			"s.assetName,s.englishName,s.buyDate,s.spec,s.estimateDay,s.alreadyDay," .
			"s.beforeUse,s.deptId,s.deptName,s.monthDepr,s.depreciation,s.salvage,s.remark," .
			"s.createName,s.createId,s.createTime,s.updateName,s.updateId,s.updateTime
			from oa_asset_sellitem s where 1=1",
	"select_assetcard"=>"select s.id,s.sellID,s.sequence,s.assetId,s.assetCode," .
			"s.assetName,s.englishName,s.buyDate,s.spec,s.estimateDay,s.alreadyDay," .
			"s.beforeUse,s.deptId,s.deptName,s.monthDepr,s.depreciation,s.salvage,s.remark," .
			"s.createName,s.createId,s.createTime,s.updateName,s.updateId,s.updateTime,".
            "c.id as cid ,c.assetabbrev,c.assetCode ,c.assetName ,c.englishName ,c.machineCode ,c.assetTypeId," .
         	"c.assetTypeName ,c.unit ,c.buyDate ,c.wirteDate ,c.place ,c.useType ,c.useStatusCode ,c.useStatusName," .
         	"c.changeTypeCode ,c.changeTypeName ,c.spec ,c.origin ,c.supplierName ,c.supplierId ,c.manufacturers," .
         	"c.remark ,c.deprCode ,c.deprName ,c.subId ,c.subName ,c.depSubId ,c.depSubName ,c.userId ,c.userName," .
         	"c.useOrgId ,c.useOrgName ,c.useProId ,c.useProCode ,c.useProName ,c.orgId ,c.orgName ,c.origina," .
         	"c.buyDepr ,c.beginTime ,c.estimateDay ,c.alreadyDay ,c.depreciation ,c.salvage ,c.netValue ,c.version," .
         	"c.isDel ,c.ExaStatus ,c.ExaDT ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId," .
         	"c.updateTime,c.isTemp,c.isSell
			from oa_asset_sellitem s left join oa_asset_card c on s.assetId = c.id where 1=1 "


);
$condition_arr = array (
    array (
        "name" => "id",
        "sql" => "and s.id = #"
    ), array (
        "name" => "sellID",
        "sql" => "and s.sellID = #"
    ),
	array (
		"name" => "sequence",
		"sql" => "and s.sequence like CONCAT('%',#,'%')"
	),
	array (
		"name" => "assetId",
		"sql" => "and s.assetId like CONCAT('%',#,'%')"
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
		"name" => "assetName",
		"sql" => "and s.assetName like CONCAT('%',#,'%')"
	),
		array (
		"name" => "assetName",
		"sql" => "and c.assetName like CONCAT('%',#,'%')"
	),
	array (
		"name" => "englishName",
		"sql" => "and s.englishName like CONCAT('%',#,'%')"
	),
	array (
		"name" => "remark",
		"sql" => "and s.remark like CONCAT('%',#,'%')"
	),
	array (
		"name" => "buyDate",
		"sql" => "and s.buyDate  like CONCAT('%',#,'%')"
	),
	array (
		"name" => "spec",
		"sql" => "and s.spec = #"
	),
	array (
		"name" => "estimateDay",
		"sql" => "and s.estimateDay  like CONCAT('%',#,'%')"
	),
	array (
		"name" => "alreadyDay",
		"sql" => "and s.alreadyDay = #"
	),
	array (
		"name" => "beforeUse",
		"sql" => "and s.beforeUse like CONCAT('%',#,'%')"
	),
	array (
		"name" => "deptId",
		"sql" => "and s.deptId like CONCAT('%',#,'%')"
	),
	array (
		"name" => "deptName",
		"sql" => "and s.deptName  like CONCAT('%',#,'%')"
	),
	array (
		"name" => "monthDepr",
		"sql" => "and s.monthDepr = #"
	),
	array (
		"name" => "depreciation",
		"sql" => "and s.depreciation  like CONCAT('%',#,'%')"
	),
	array (
		"name" => "salvage",
		"sql" => "and s.salvage = #"
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
	)

);
?>
