<?php

/**
 * @author Show
 * @Date 2012��9��3�� ����һ 19:51:29
 * @version 1.0
 * @description:����ģ����չ��Ϣ sql�����ļ�
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.taskId ,c.upperLimit ,c.lowerLimit ,c.score ,c.bookId ,c.bookName  from oa_hr_baseinfo_trialplantem_expand c where 1=1 "
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.id=# "
	),
	array (
		"name" => "ids",
		"sql" => " and c.id in(arr) "
	),
	array (
		"name" => "taskId",
		"sql" => " and c.taskId=# "
	),
	array (
		"name" => "upperLimit",
		"sql" => " and c.upperLimit=# "
	),
	array (
		"name" => "lowerLimit",
		"sql" => " and c.lowerLimit=# "
	),
	array (
		"name" => "score",
		"sql" => " and c.score=# "
	),
	array (
		"name" => "bookId",
		"sql" => " and c.bookId=# "
	),
	array (
		"name" => "bookName",
		"sql" => " and c.bookName=# "
	)
)
?>