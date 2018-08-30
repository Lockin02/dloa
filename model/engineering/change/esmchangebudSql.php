<?php
/**
 * @author Show
 * @Date 2012年12月15日 星期六 15:21:23
 * @version 1.0
 * @description:项目费用预算变更表 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.changeId ,c.budgetId ,c.budgetName ,c.projectId ,c.projectCode ,c.projectName ,c.price ,c.numberOne ,
			c.numberTwo ,c.amount ,c.remark ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,
			c.updateTime ,c.orgId ,c.parentId ,c.parentName,c.budgetType,c.isChanging,c.changeAction,c.customPrice
		from oa_esm_change_budget c where 1=1 "
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "changeId",
		"sql" => " and c.changeId=# "
	),
	array (
		"name" => "budgetId",
		"sql" => " and c.budgetId=# "
	),
	array (
		"name" => "budgetName",
		"sql" => " and c.budgetName=# "
	),
	array (
		"name" => "projectId",
		"sql" => " and c.projectId=# "
	),
	array (
		"name" => "projectCode",
		"sql" => " and c.projectCode=# "
	),
	array (
		"name" => "projectName",
		"sql" => " and c.projectName=# "
	),
	array (
		"name" => "price",
		"sql" => " and c.price=# "
	),
	array (
		"name" => "numberOne",
		"sql" => " and c.numberOne=# "
	),
	array (
		"name" => "numberTwo",
		"sql" => " and c.numberTwo=# "
	),
	array (
		"name" => "amount",
		"sql" => " and c.amount=# "
	),
	array (
		"name" => "remark",
		"sql" => " and c.remark=# "
	),
	array (
		"name" => "createId",
		"sql" => " and c.createId=# "
	),
	array (
		"name" => "createName",
		"sql" => " and c.createName=# "
	),
	array (
		"name" => "createTime",
		"sql" => " and c.createTime=# "
	),
	array (
		"name" => "updateId",
		"sql" => " and c.updateId=# "
	),
	array (
		"name" => "updateName",
		"sql" => " and c.updateName=# "
	),
	array (
		"name" => "updateTime",
		"sql" => " and c.updateTime=# "
	),
	array (
		"name" => "orgId",
		"sql" => " and c.orgId=# "
	),
	array (
		"name" => "parentId",
		"sql" => " and c.parentId=# "
	),
	array (
		"name" => "parentName",
		"sql" => " and c.parentName=# "
	)
);