<?php
/**
 * 资产归还明细sql
 *@linzx
 */
$sql_arr = array (
	"select_return" => "select s.id,s.allocateID,s.sequence,s.assetId,s.assetCode,s.assetName,s.spec,s.buyDate," .
			"s.residueYears,s.alreadyDay,s.estimateDay,s.remark," .
			"s.createName,s.createId,s.createTime,s.updateName,s.updateId,s.updateTime
			from oa_asset_returnitem s where 1=1"
);
$condition_arr = array (
    array (
        "name" => "id",
        "sql" => "and s.id = #"
    ),
	array (
		"name" => "allocateID",
		"sql" => "and s.allocateID like CONCAT('%',#,'%')"
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
		"name" => "residueYears",
		"sql" => "and s.residueYears like CONCAT('%',#,'%')"
	),
	array (
		"name" => "alreadyDay",
		"sql" => "and s.alreadyDay like CONCAT('%',#,'%')"
	),
	array (
		"name" => "estimateDay",
		"sql" => "and s.estimateDay   like CONCAT('%',#,'%')"
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
	)

);
?>
