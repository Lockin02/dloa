<?php
/**
 * @author Michael
 * @Date 2014��7��25�� 15:20:28
 * @version 1.0
 * @description:������Ϣ-�������� sql�����ļ�
 */
$sql_arr = array (
	"select_default"=>"select c.id ,c.parentId ,c.process ,c.processName ,c.processTime ,c.recipient ,c.recipientId ,c.remark  from oa_manufacture_processequ c where 1=1 "
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
		"name" => "process",
		"sql" => " and c.process=# "
	),
	array(
		"name" => "processName",
		"sql" => " and c.processName LIKE CONCAT('%' ,# ,'%') "
	),
	array(
		"name" => "processTime",
		"sql" => " and c.processTime=# "
	),
	array(
		"name" => "recipient",
		"sql" => " and c.recipient=# "
	),
	array(
		"name" => "recipientId",
		"sql" => " and c.recipientId=# "
	),
	array(
		"name" => "remark",
		"sql" => " and c.remark=# "
	)
)
?>