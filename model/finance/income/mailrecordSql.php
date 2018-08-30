<?php

/**
 * @author Show
 * @Date 2011年9月22日 星期四 10:30:00
 * @version 1.0
 * @description:到款邮件记录 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.incomeId ,c.incomeCode ,c.sendIds ,c.sendNames ,c.copyIds ,c.copyNames ,c.secretIds ,c.secretNames ,c.title ,c.content ,c.times ,c.status ,c.createOn ,c.lastMailTime  from oa_finance_income_mailrecord c where 1=1 "
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "incomeId",
		"sql" => " and c.incomeId=# "
	),
	array (
		"name" => "incomeCode",
		"sql" => " and c.incomeCode=# "
	),
	array (
		"name" => "incomeCodeSearch",
		"sql" => " and c.incomeCode like concat('%',#,'%')"
	),
	array (
		"name" => "sendIds",
		"sql" => " and c.sendIds=# "
	),
	array (
		"name" => "sendNames",
		"sql" => " and c.sendNames like concat('%',#,'%')"
	),
	array (
		"name" => "copyIds",
		"sql" => " and c.copyIds=# "
	),
	array (
		"name" => "copyNames",
		"sql" => " and c.copyNames=# "
	),
	array (
		"name" => "secretIds",
		"sql" => " and c.secretIds=# "
	),
	array (
		"name" => "secretNames",
		"sql" => " and c.secretNames like concat('%',#,'%')"
	),
	array (
		"name" => "times",
		"sql" => " and c.times=# "
	),
	array (
		"name" => "status",
		"sql" => " and c.status=# "
	),
	array (
		"name" => "createOn",
		"sql" => " and c.createOn=# "
	)
)
?>