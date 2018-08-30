<?php
/**
 * @author show
 * @Date 2015��2��6�� 9:52:48
 * @version 1.0
 * @description:��Ŀ�ر���ϸ sql�����ļ�
 */
$sql_arr = array(
	"select_default" => "select c.id,c.projectId,c.ruleId,c.reply,c.status,c.confirmName,
			c.confirmId,c.confirmTime
		from oa_esm_project_close_detail c where 1 ",
	"select_rules" => "select c.id,c.projectId,c.ruleId,c.reply,c.status,c.confirmName,c.confirmId,c.confirmTime,c.val,
			r.ruleName,r.content,r.isCustom
		from oa_esm_project_close_detail c LEFT JOIN oa_esm_close_rule r ON c.ruleId = r.id where 1 "
);

$condition_arr = array(
	array(
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array(
		"name" => "projectId",
		"sql" => " and c.projectId=# "
	),
	array(
		"name" => "ruleId",
		"sql" => " and c.ruleId=# "
	),
	array(
		"name" => "status",
		"sql" => " and c.status=# "
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
		"name" => "confirmTime",
		"sql" => " and c.confirmTime=# "
	)
);