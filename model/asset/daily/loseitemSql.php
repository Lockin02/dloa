<?php
/**
 * ×Ê²úÒÅÊ§Ã÷Ï¸sql
 *@linzx
 */
$sql_arr = array (
	"select_keep" => "select s.id,s.loseId,s.sequence,s.assetId,s.assetCode,s.assetName,s.spec,s.buyDate," .
			"s.orgId,s.orgName,s.useOrgId,s.useOrgName,s.origina,s.alreadyDay,s.depreciation,s.salvage,s.remark," .
			"s.createName,s.createId,s.createTime,s.updateName,s.updateId,s.updateTime,s.isScrap
			from oa_asset_loseitem s where 1=1"
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
		"name" => "sequence",
		"sql" => "and s.sequence like CONCAT('%',#,'%')"
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
		"name" => "assetName",
		"sql" => "and s.assetName like CONCAT('%',#,'%')"
	),
	array (
		"name" => "spec",
		"sql" => "and s.spec like CONCAT('%',#,'%')"
	),
	array (
		"name" => "buyDate",
		"sql" => "and s.buyDate like CONCAT('%',#,'%')"
	),
	array (
		"name" => "orgId",
		"sql" => "and s.orgId like CONCAT('%',#,'%')"
	),
	array (
		"name" => "orgName",
		"sql" => "and s.orgName like CONCAT('%',#,'%')"
	),
	array (
		"name" => "useOrgId",
		"sql" => "and s.useOrgId   like CONCAT('%',#,'%')"
	),
	array (
		"name" => "origina",
		"sql" => "and s.origina   like CONCAT('%',#,'%')"
	),
	array (
		"name" => "alreadyDay",
		"sql" => "and s.alreadyDay  like CONCAT('%',#,'%')"
	),
	array (
		"name" => "depreciation",
		"sql" => "and s.depreciation  like CONCAT('%',#,'%')"
	),
	array (
		"name" => "salvage",
		"sql" => "and s.salvage like CONCAT('%',#,'%')"
	),
	array (
		"name" => "remark",
		"sql" => "and s.remark like CONCAT('%',#,'%')"
	),
	array (
		"name" => "createId",
		"sql" => "and s.createId = #"
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
	array (
		"name" => "isScrap",
		"sql" => "and s.isScrap = #"
	)

);
?>
