<?php

/**
 * @author Show
 * @Date 2012年8月21日 星期二 10:12:31
 * @version 1.0
 * @description:任职资格模板明细 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.modelId ,c.moduleId ,c.moduleName ,c.detailName ,c.detailId ,c.standard ,c.needMaterial,c.weights  from oa_hr_baseinfo_certifytemplate_detail c where 1=1 "
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "modelId",
		"sql" => " and c.modelId=# "
	),
	array (
		"name" => "moduleId",
		"sql" => " and c.moduleId=# "
	),
	array (
		"name" => "moduleName",
		"sql" => " and c.moduleName=# "
	),
	array (
		"name" => "detailName",
		"sql" => " and c.detailName=# "
	),
	array (
		"name" => "detailId",
		"sql" => " and c.detailId=# "
	),
	array (
		"name" => "standard",
		"sql" => " and c.standard=# "
	),
	array (
		"name" => "needMaterial",
		"sql" => " and c.needMaterial=# "
	)
)
?>