<?php
/**
 * @author Show
 * @Date 2011年12月22日 星期四 9:49:51
 * @version 1.0
 * @description:项目费用预算(oa_esm_project_budget) sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.budgetId  ,c.budgetName ,c.parentId ,
			c.parentName ,c.projectId ,c.projectCode ,c.projectName ,c.price ,c.numberOne ,c.numberTwo ,
			c.amount,c.remark ,c.createId ,c.createName ,c.createTime ,c.updateId ,
			c.updateName ,c.updateTime,c.budgetType,c.status,c.isChanging,
			c.changeAction,0 as thisType,0 as costMoney,c.actFee,c.customPrice,c.isImport,c.actFeeWait,c.amountWait
		 from oa_esm_project_budget c where 1=1 ",
	"count_all" => "select sum(c.amount) as amount,sum(c.actFee) as actFee,sum(c.actFeeWait) as actFeeWait,sum(c.amountWait) as amountWait from oa_esm_project_budget c where 1=1",
	"select_amountForType" => "select c.budgetType,sum(c.amount) as amount from oa_esm_project_budget c where 1=1 ",
	"select_change" => "
			select
				c.id ,c.budgetId ,c.budgetName ,c.parentId ,c.parentName ,c.projectId ,c.projectCode ,c.projectName ,
				c.price ,c.numberOne ,c.numberTwo ,c.amount ,c.remark ,
				c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime ,
				c.budgetType ,c.orgId ,c.changeId,c.isChanging,c.changeAction,
				c.coefficient,c.budgetPeople,c.budgetDay ,c.planBeginDate ,c.planEndDate,0 as costMoney,c.customPrice,c.isImport
			from
				oa_esm_change_budget c
			where c.changeAction <> 'delete'",
	"count_change" => "select sum(c.amount) as amount from
				oa_esm_change_budget c
			where c.changeAction <> 'delete'",
	"search_json" => "select c.id,c.parentName,c.budgetName,sum(c.amount) as amount,sum(c.actFee) as actFee,
                c.remark,c.budgetType,c.isImport,c.actFeeWait,c.amountWait,c.status,count(*) as num
			from oa_esm_project_budget c where 1=1"
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "ids",
		"sql" => " and c.Id in(arr) "
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
		"name" => "budgetNameSearch",
		"sql" => " and c.budgetName like CONCAT('%',#,'%') "
	),
	array (
		"name" => "parentId",
		"sql" => " and c.parentId=# "
	),
	array (
		"name" => "parentName",
		"sql" => " and c.parentName=# "
	),
	array (
		"name" => "parentNameSearch",
		"sql" => " and c.parentName like CONCAT('%',#,'%') "
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
		"name" => "createId",
		"sql" => " and c.createId=# "
	),
	array (
		"name" => "createName",
		"sql" => " and c.createName=# "
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
		"name" => "budgetType",
		"sql" => " and c.budgetType=# "
	),
	array (
		"name" => "changeId",
		"sql" => " and c.changeId=# "
	),
	array (
		"name" => "isImport",
		"sql" => " and c.isImport=# "
	)
);