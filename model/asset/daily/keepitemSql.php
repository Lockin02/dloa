<?php
/**
 * 资产维保明细sql
 *@linzx
 */
$sql_arr = array (
	"select_scrap" => "select s.id,s.keepId,s.sequence,s.assetId,s.assetCode," .
			"s.assetName,s.amount,s.userId,s.userName,s.remark,s.createName,s.createId," .
			"s.createTime,s.updateName,s.updateId,s.updateTime
			from oa_asset_keepitem s where 1=1"
);
$condition_arr = array (
    array (
        "name" => "id",
        "sql" => "and s.id = #"
    ),
	array (
		"name" => "keepId",
		"sql" => "and s.keepId like CONCAT('%',#,'%')"
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
		"name" => "amount",
		"sql" => "and s.amount like CONCAT('%',#,'%')"
	),
	array (
		"name" => "userId",
		"sql" => "and s.userId like CONCAT('%',#,'%')"
	),
	array (
		"name" => "userName",
		"sql" => "and s.userName like CONCAT('%',#,'%')"
	),
	array (
		"name" => "remark",
		"sql" => "and s.remark like CONCAT('%',#,'%')"
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
