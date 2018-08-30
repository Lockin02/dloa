<?php
/**
 * @author Show
 * @Date 2012年12月10日 星期一 14:20:22
 * @version 1.0
 * @description:项目指标选项 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.detailId ,c.optionName ,c.score  from oa_esm_project_assoptions c where 1=1 "
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "detailId",
		"sql" => " and c.detailId=# "
	),
	array (
		"name" => "optionName",
		"sql" => " and c.optionName=# "
	),
	array (
		"name" => "score",
		"sql" => " and c.score=# "
	)
)
?>