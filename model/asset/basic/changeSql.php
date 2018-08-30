<?php

/**
 * @author Show
 * @Date 2011年5月3日 19:15:50
 * @version 1.0
 * @description:仓库基本信息 sql配置文件
 */
$sql_arr = array (
	"select_change" => "select c.id ,c.code ,c.name ,c.vouchers ,c.digest ,c.subName ,c.subcode ,c.type,c.isDel ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime ,c.isSysType  from oa_asset_change c where 1=1 ",

	"select_grid" => "select c.id ,c.code ,c.name ,c.vouchers ,c.digest ,c.subName ,c.subcode ,c.isDel ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime ,c.isSysType  from oa_asset_change c where id <> -1 ",

	"select_changeIsDel" => "select c.id ,c.code ,c.name ,c.vouchers ,c.digest ,c.subName ,c.subcode ,c.isDel ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime ,c.isSysType  from oa_asset_change c where isDel=0 ",


);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.id=# "
	),
	array (
		"name" => "code",
		"sql" => " and c.code like CONCAT('%',#,'%') "
	),
	array (
		"name" => "codeEq",
		"sql" => " and c.code=#"
	),
	array (
		"name" => "name",
		"sql" => "and c.name =#"
	),
	array (
		"name" => "nameEq",
		"sql" => "and c.name like CONCAT('%',#,'%')"
	),
	array (
		"name" => "vouchers",
		"sql" => "and c.vouchers =#"
	),
	array (
		"name" => "digest",
		"sql" => "and c.digest =#"
	),
	array (
		"name" => "subName",
		"sql" => " and c.subName=# "
	),
	array (
		"name" => "subcode",
		"sql" => " and c.subcode =# "
	),
	array (
		"name" => "subcodeEq",
		"sql" => " and c.subcode like CONCAT('%',#,'%') "
	),
	array (
		"name" => "type",
		"sql" => " and c.type=# "
	),
	array (
		"name" => "isDel",
		"sql" => " and c.isDel=# "
	),

	array (
		"name" => "createName",
		"sql" => " and c.createName=# "
	),
	array (
		"name" => "createId",
		"sql" => " and c.createId=# "
	),
	array (
		"name" => "createTime",
		"sql" => " and c.createTime=# "
	),
	array (
		"name" => "updateName",
		"sql" => " and c.updateName=# "
	),
	array (
		"name" => "updateId",
		"sql" => " and c.updateId=# "
	),
	array (
		"name" => "updateTime",
		"sql" => " and c.updateTime=# "
	),
	array (
		"name" => "isSysType",
		"sql" => " and c.isSysType=# "
	)
)
?>