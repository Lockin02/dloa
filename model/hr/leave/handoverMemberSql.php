<?php
/**
 * @author Administrator
 * @Date 2012年10月30日 星期二 15:05:44
 * @version 1.0
 * @description:离职交接清单明细接收人 sql配置文件
 */
$sql_arr = array (
	"select_default"=>"select c.id ,c.parentId ,c.handoverId ,c.recipientName ,c.recipientId ,c.affstate ,c.affTime ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime  from oa_hr_handover_member c where 1=1 "
);

$condition_arr = array (
	array(
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array(
		"name" => "parentId",
		"sql" => " and c.parentId=# "
	),
	array(
		"name" => "handoverId",
		"sql" => " and c.handoverId=# "
	),
	array(
		"name" => "recipientName",
		"sql" => " and c.recipientName=# "
	),
	array(
		"name" => "recipientId",
		"sql" => " and c.recipientId=# "
	),
	array(
		"name" => "affstate",
		"sql" => " and c.affstate=# "
	),
	array(
		"name" => "affTime",
		"sql" => " and c.affTime=# "
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