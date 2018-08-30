<?php

/**
 * @author Show
 * @Date 2012年7月29日 16:32:12
 * @version 1.0
 * @description:费用明细 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.projectId ,c.projectCode ,c.projectName ,c.activityId ,c.activityName ,
			c.worklogId ,c.costType ,c.costTypeId ,c.costMoney ,c.days ,c.status ,c.createId ,c.createName ,
			c.createTime ,c.updateId ,c.updateName ,c.updateTime,c.parentCostType,c.parentCostTypeId,c.remark
		from oa_esm_costdetail c where 1=1 ",
	'select_fee' => "select c.costType,sum(c.costMoney) as costMoney,group_concat(c.remark) as remark from oa_esm_costdetail c where 1",
	'select_feetitle' => "select c.costType,c.costTypeId from oa_esm_costdetail c where 1",
	'select_fee2' => "select c.createName,c.createId,c.costTypeId,c.costType,sum(c.costMoney) as costMoney
			from oa_esm_costdetail c where 1",
	'count_all' => "select sum(c.costMoney) as costMoney from oa_esm_costdetail c where 1 ",
	'count_project' => "select
			c.createId,c.createName,
			sum(if(c.status <> 2,c.costMoney,0)) as allCostMoney,sum(if(c.status = 0,c.costMoney,0)) as unauditMoney
		from
			oa_esm_costdetail c
		where 1 ",
	'count_projectdate' => "select concat(cast(c.projectId as char(10)),'_',cast(c.executionDate as char(10))) as id,c.executionDate,
			c.createId,c.createName,c.projectId,
			sum(c.costMoney) as costMoney,
			sum(if(c.status = 1 or c.status = 3 or c.status = 4,c.costMoney,0)) as confirmMoney,
			sum(if(c.status = 0,c.costMoney,0)) as unconfirmMoney,
			sum(if(c.status = 3,c.costMoney,0)) as expensingMoney,
			sum(if(c.status = 4,c.costMoney,0)) as expenseMoney,
			sum(if(c.status = 1,c.costMoney,0)) as unexpenseMoney,
			sum(if(c.status = 2,c.costMoney,0)) as backMoney
		from
			oa_esm_costdetail c
		where 1 ",
	'count_membercount' => "select
			sum(c.costMoney) as costMoney,
			sum(if(c.status = 1 or c.status = 3 or c.status = 4,c.costMoney,0)) as confirmMoney,
			sum(if(c.status = 0,c.costMoney,0)) as unconfirmMoney,
			sum(if(c.status = 3,c.costMoney,0)) as expensingMoney,
			sum(if(c.status = 4,c.costMoney,0)) as expenseMoney,
			sum(if(c.status = 1,c.costMoney,0)) as unexpenseMoney,
			sum(if(c.status = 2,c.costMoney,0)) as backMoney
		from
			oa_esm_costdetail c
		where 1 ",
	'count_costType' => "select group_concat(c.id) as costIds,
			c.costTypeId,c.costType,c.parentCostType,c.parentCostTypeId,
			group_concat(c.remark SEPARATOR ';') as remark,
			sum(if(c.status = 1,c.costMoney,0)) as costMoney
		from
			oa_esm_costdetail c
		where 1 "
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
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
		"name" => "activityId",
		"sql" => " and c.activityId=# "
	),
	array (
		"name" => "activityName",
		"sql" => " and c.activityName=# "
	),
	array (
		"name" => "worklogId",
		"sql" => " and c.worklogId=# "
	),
	array (
		"name" => "costType",
		"sql" => " and c.costType=# "
	),
	array (
		"name" => "costTypeId",
		"sql" => " and c.costTypeId=# "
	),
	array (
		"name" => "costMoney",
		"sql" => " and c.costMoney=# "
	),
	array (
		"name" => "days",
		"sql" => " and c.days=# "
	),
	array (
		"name" => "status",
		"sql" => " and c.status=# "
	),
	array (
		"name" => "statusArr",
		"sql" => " and c.status in(arr) "
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
		"name" => "executionDate",
		"sql" => " and c.executionDate=# "
	),
	array (
		"name" => "executionDates",
		"sql" => " and c.executionDate in(arr) "
	)
)
?>