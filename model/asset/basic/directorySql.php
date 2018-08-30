<?php
$sql_arr = array (
	"select" => "select c.id,c.name,c.name as text,c.code," .
			"c.assetSubject,c.limitYears,c.salvage,c.unit,c.deprCode,
			c.depr,c.subId,c.subName,c.isDepr,c.createName,c.createId,c.createTime,c.updateName,c.updateId,c.updateTime
			from oa_asset_assettype c where 1=1"
);
$condition_arr = array (
    array (
        "name" => "id",
        "sql" => "and c.id = #"
    ),
	array (
		"name" => "name",
		"sql" => "and c.name like CONCAT('%',#,'%')"
	),
	array (
		"name" => "nameEq",
		"sql" => "and c.name = #"
	),
	array (
		"name" => "code",
		"sql" => "and c.code like CONCAT('%',#,'%')"
	),
	array (
		"name" => "codeEq",
		"sql" => "and c.code = #"
	),
	array (
		"name" => "limitYears",
		"sql" => "and c.limitYears like CONCAT('%',#,'%')"
	),
	array (
		"name" => "assetSubject",
		"sql" => "and c.assetSubject like CONCAT('%',#,'%')"
	),
	array (
		"name" => "salvage",
		"sql" => "and c.salvage like CONCAT('%',#,'%')"
	),
	array (
		"name" => "unit",
		"sql" => "and c.unit like CONCAT('%',#,'%')"
	),
	array (
		"name" => "deprCode",
		"sql" => "and c.deprCode like CONCAT('%',#,'%')"
	),array (
		"name" => "depr",
		"sql" => "and c.depr like CONCAT('%',#,'%')"
	),
	array (
		"name" => "subId",
		"sql" => "and c.subId like CONCAT('%',#,'%')"
	),
	array (
		"name" => "subName",
		"sql" => "and c.subName  like CONCAT('%',#,'%')"
	),
	array (
		"name" => "isDepr",
		"sql" => "and c.isDepr like CONCAT('%',#,'%')"
	),
	array (
		"name" => "createName",
		"sql" => "and c.createName in(arr)"
	),
	array (
		"name" => "createTime",
		"sql" => "and c.createTime = #"
	),
	array (
		"name" => "updateName",
		"sql" => "and c.updateName = #"
	),
	array (
		"name" => "updateId",
		"sql" => "and c.updateId = #"
	),
	array (
		"name" => "updateTime",
		"sql" => "and c.updateTime = #"
	)

);
?>
