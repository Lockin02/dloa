<?php
/**
 * @author Show
 * @Date 2012年12月21日 星期五 9:45:04
 * @version 1.0
 * @description:个人费用模板 sql配置文件
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