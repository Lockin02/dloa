<?php

/**
 * @author Show
 * @Date 2012年11月28日 星期三 14:46:41
 * @version 1.0
 * @description:合同不开票金额 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.objId ,c.objCode ,c.objType ,c.isRed ,c.money ,c.descript ,c.createName ,c.createId ,c.createTime  from oa_contract_uninvoice c where 1=1 ",
	"count_all" => "select sum(if(c.isRed = 0,c.money,-c.money)) as uninvoiceMoney from oa_contract_uninvoice c where 1=1 "
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "objId",
		"sql" => " and c.objId=# "
	),
	array (
		"name" => "objCode",
		"sql" => " and c.objCode=# "
	),
	array (
		"name" => "objType",
		"sql" => " and c.objType=# "
	),
	array (
		"name" => "isRed",
		"sql" => " and c.isRed=# "
	),
	array (
		"name" => "money",
		"sql" => " and c.money=# "
	),
	array (
		"name" => "descript",
		"sql" => " and c.descript=# "
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
	)
)
?>