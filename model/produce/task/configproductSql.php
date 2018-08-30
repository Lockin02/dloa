<?php
/**
 * @author Michael
 * @Date 2014年8月26日 14:07:59
 * @version 1.0
 * @description:生产任务配置物料 sql配置文件
 */
$sql_arr = array (
	"select_default"=>"select c.id ,c.taskId ,c.productId ,c.productCode ,c.productName ,c.pattern ,c.unitName ,c.num ,c.planNum ,c.planId ,c.isMeetProduction ,remark from oa_produce_taskconfig_product c where 1=1 ",
	"select_statistics"=>"select c.id ,c.taskId ,c.productId ,c.productCode ,c.productName ,c.pattern ,c.unitName ,c.num ,c.planNum ,c.planId ,c.isMeetProduction ,
			k.docCode ,k.docStatus ,k.relDocCode ,k.urgentLevel ,k.customerName ,k.chargeUserName
		from oa_produce_taskconfig_product c left join oa_produce_producetask k on k.id = c.taskId where 1=1 "
);

$condition_arr = array (
	array(
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array(
		"name" => "taskId",
		"sql" => " and c.taskId=# "
	),
	array (
		"name" => "taskIds",
		"sql" => " and c.taskId in(arr) "
	),
	array(
		"name" => "productId",
		"sql" => " and c.productId=# "
	),
	array(
		"name" => "productCode",
		"sql" => " and c.productCode=# "
	),
	array(
		"name" => "productName",
		"sql" => " and c.productName=# "
	),
	array(
		"name" => "pattern",
		"sql" => " and c.pattern=# "
	),
	array(
		"name" => "unitName",
		"sql" => " and c.unitName=# "
	),
	array(
		"name" => "num",
		"sql" => " and c.num=# "
	),
	array(
		"name" => "isMeetProduction",
		"sql" => " and c.isMeetProduction=# "
	)
)
?>