<?php
/**
 * @author Show
 * @Date 2012��12��21�� ������ 9:45:04
 * @version 1.0
 * @description:���˷���ģ�� sql�����ļ�
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.templateName ,c.userId ,c.userName ,c.content ,c.contentId,c.id as modelType,c.templateName as modelTypeName  from cost_customtemplate c where 1=1 "
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "templateName",
		"sql" => " and c.templateName=# "
	),
	array (
		"name" => "userId",
		"sql" => " and c.userId=# "
	),
	array (
		"name" => "userName",
		"sql" => " and c.userName=# "
	),
	array (
		"name" => "content",
		"sql" => " and c.content=# "
	),
	array (
		"name" => "contentId",
		"sql" => " and c.contentId=# "
	)
)
?>