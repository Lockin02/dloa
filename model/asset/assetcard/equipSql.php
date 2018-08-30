<?php
$sql_arr = array (

	"select_equip" => "select c.equipCode,c.id,
			c.equipId,c.equipName,c.regDate,c.spec,c.unit,c.num,c.account,
			c.placeId,c.place,c.remark,c.assetCode,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime from oa_asset_equip c
			where 1=1 "
		);
$condition_arr = array (
	array (
		"name" => "equipCode",
		"sql" => "and c.equipCode=#"
	),
	array (
		"name" => "id",
		"sql" => "and c.id=#"
	),
	array (
		"name" => "assetId",
		"sql" => "and c.assetId=#"
	),
	array (
		"name" => "assetCode",
		"sql" => "and c.assetCode=#"
	),
	array (
		"name" => "assetCodeAbr",
		"sql" => "and c.assetCode=#"
	),
	array (
		"name" => "equipId",
		"sql" => "and c.equipId like CONCAT('%',#,'%')"
	),
	array (
		"name" => "equipName",
		"sql" => "and c.equipName like CONCAT('%',#,'%')"
	),
	array (
		"name" => "regDate",
		"sql" => "and c.regDate =#"
	),
	array (
		"name" => "spec",
		"sql" => "and c.spec =#"
	),
	array(
		"name" => "unit",
		"sql" => " and c.unit = #"
	),
	array(
		"name" => "num",
		"sql" => " and c.num = # "
	),
	array(
		"name" => "account",
		"sql" => " and  c.account = #"
	),array (
		"name" => "placeId",
		"sql" => "and c.placeId =#"
	),
	array (
		"name" => "place",
		"sql" => "and c.place =#"
	),
	array(
		"name" => "remark",
		"sql" => " and c.remark = #"
	),  array(
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

);
?>
