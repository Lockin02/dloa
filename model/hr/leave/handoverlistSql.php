<?php
/**
 * @author Administrator
 * @Date 2012-08-09 15:38:12
 * @version 1.0
 * @description:离职交接清单明细 sql配置文件
 */
$sql_arr = array (
	"select_default"=>"select c.id ,c.handoverId ,c.affTime,c.items ,c.affstate,c.handoverCondition ,c.recipientName ,c.recipientId ,c.lose ,c.deduct ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime,c.isKey,c.remark ,c.sort ,c.mailAffirm ,c.sendPremise from oa_hr_handover_list c where 1=1 "
);

$condition_arr = array (
	array(
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array(
		"name" => "affstate",
		"sql" => " and c.affstate=# "
	),
	array(
		"name" => "handoverId",
		"sql" => " and c.handoverId=# "
	),
	array(
		"name" => "items",
		"sql" => " and c.items=# "
	),
	array(
		"name" => "handoverCondition",
		"sql" => " and c.handoverCondition=# "
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
		"name" => "lose",
		"sql" => " and c.lose=# "
	),
	array(
		"name" => "deduct",
		"sql" => " and c.deduct=# "
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
	),
	array(
		"name" => "recipientIdArr",
		"sql" => " and c.id in(select parentId from oa_hr_handover_member where recipientId=#) "
	)
)
?>