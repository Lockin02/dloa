<?php

/**
 * @author Show
 * @Date 2012年8月23日 星期四 9:40:38
 * @version 1.0
 * @description:任职资格等级认证评价表明细 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.assessId ,c.moduleId ,c.moduleName ,c.detailName ,c.detailId ,c.standard ,c.needMaterial ,
			c.content ,c.file,c.weights,c.weightScore,c.averageDifference,c.maxDifference,c.isDeal,c.averageScore,c.id as cdetailId
		from oa_hr_certifyapplyassess_detail c where 1=1 "
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "assessId",
		"sql" => " and c.assessId=# "
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
	),
	array (
		"name" => "content",
		"sql" => " and c.content=# "
	),
	array (
		"name" => "file",
		"sql" => " and c.file=# "
	)
)
?>