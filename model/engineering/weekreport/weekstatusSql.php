<?php
/**
 * @author show
 * @Date 2013年9月22日 14:46:02
 * @version 1.0
 * @description:项目周进度状况 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.mainId ,c.weekNo ,c.processAll ,c.processPlan ,c.processAct ,c.processFee ,c.budgetDiff ,c.grossMargin ,c.remarkAll ,c.remarkPlan ,c.remarkAct ,c.remarkDiff ,c.remarkFee ,c.thisWeek  from oa_esm_project_weekstatus c where 1=1 "
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
		"name" => "weekNo",
		"sql" => " and c.weekNo=# "
	),
	array (
		"name" => "processAll",
		"sql" => " and c.processAll=# "
	),
	array (
		"name" => "processPlan",
		"sql" => " and c.processPlan=# "
	),
	array (
		"name" => "processAct",
		"sql" => " and c.processAct=# "
	),
	array (
		"name" => "processFee",
		"sql" => " and c.processFee=# "
	),
	array (
		"name" => "budgetDiff",
		"sql" => " and c.budgetDiff=# "
	),
	array (
		"name" => "grossMargin",
		"sql" => " and c.grossMargin=# "
	),
	array (
		"name" => "remarkAll",
		"sql" => " and c.remarkAll=# "
	),
	array (
		"name" => "remarkPlan",
		"sql" => " and c.remarkPlan=# "
	),
	array (
		"name" => "remarkAct",
		"sql" => " and c.remarkAct=# "
	),
	array (
		"name" => "remarkDiff",
		"sql" => " and c.remarkDiff=# "
	),
	array (
		"name" => "remarkFee",
		"sql" => " and c.remarkFee=# "
	),
	array (
		"name" => "thisWeek",
		"sql" => " and c.thisWeek=# "
	)
)
?>