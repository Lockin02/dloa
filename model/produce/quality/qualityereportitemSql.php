<?php

/**
 * @author Administrator
 * @Date 2013年4月2日 星期二 15:15:03
 * @version 1.0
 * @description:检验报告清单 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.mainId ,c.dimensionId ,c.dimensionName ,c.examStartName ,c.examStartId ,
			c.examTypeName ,c.examTypeId ,c.exmineResult ,c.itemNum ,c.remark,c.crNum,c.maNum,c.miNum,c.qualitedNum
		from oa_produce_quality_ereportitem c where 1=1 "
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "mainId",
		"sql" => " and c.mainId=# "
	),
	array (
		"name" => "dimensionId",
		"sql" => " and c.dimensionId=# "
	),
	array (
		"name" => "dimensionName",
		"sql" => " and c.dimensionName=# "
	),
	array (
		"name" => "examStartName",
		"sql" => " and c.examStartName=# "
	),
	array (
		"name" => "examStartId",
		"sql" => " and c.examStartId=# "
	),
	array (
		"name" => "examTypeName",
		"sql" => " and c.examTypeName=# "
	),
	array (
		"name" => "examTypeId",
		"sql" => " and c.examTypeId=# "
	),
	array (
		"name" => "exmineResult",
		"sql" => " and c.exmineResult=# "
	),
	array (
		"name" => "itemNum",
		"sql" => " and c.itemNum=# "
	),
	array (
		"name" => "remark",
		"sql" => " and c.remark=# "
	)
)
?>