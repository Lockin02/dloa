<?php
/**
 * @author show
 * @Date 2015年2月5日 15:49:55
 * @version 1.0
 * @description:项目关闭规则 sql配置文件
 */
$sql_arr = array(
	"select_default" => "select c.id,c.ruleName,c.content,c.status,c.isCustom,c.confirmName,c.confirmId,c.isNeed
		from oa_esm_close_rule c where 1=1 ",
	"select_list" => "select c.id AS ruleId,c.ruleName,c.content,c.status,c.isCustom,c.confirmName,c.confirmId,c.isNeed
		from oa_esm_close_rule c where 1=1 "
);

$condition_arr = array(
	array(
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array(
		"name" => "ruleName",
		"sql" => " and c.ruleName=# "
	),
	array(
		"name" => "content",
		"sql" => " and c.content=# "
	),
	array(
		"name" => "status",
		"sql" => " and c.status=# "
	),
	array(
		"name" => "isCustom",
		"sql" => " and c.isCustom=# "
	),
	array(
		"name" => "confirmName",
		"sql" => " and c.confirmName=# "
	),
	array(
		"name" => "confirmId",
		"sql" => " and c.confirmId=# "
	),
	array(
		"name" => "isNeed",
		"sql" => " and c.isNeed=# "
	)
);