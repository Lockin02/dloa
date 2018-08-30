<?php


/**
 * @author Show
 * @Date 2012年6月18日 星期一 17:35:04
 * @version 1.0
 * @description:项目人力预算(oa_esm_project_person) sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.projectId ,c.projectCode ,c.projectName ,c.activityId ,c.activityName ,c.personLevelId ,c.personLevel ,c.planBeginDate ,c.planEndDate ,c.days ,c.personDays ,c.personCostDays ,c.personCost ,c.number ,c.price ,c.coefficient ,c.status ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime  from oa_esm_project_person c where 1=1 ",
	"count_all" => "select sum(c.days) as days,sum(c.personDays) as personDays ,sum(c.personCostDays) as personCostDays ,sum(c.personCost) as personCost ,sum(c.number) as number from oa_esm_project_person c where 1=1 ",
	"select_person" => "select c.id ,c.projectId ,c.projectCode ,c.projectName ,c.activityId ,c.activityName ,c.personLevelId ,
			c.personLevel ,c.planBeginDate ,c.planEndDate ,c.days ,c.personDays ,c.personCostDays ,
			c.personCost ,c.number ,c.price ,c.coefficient ,c.status ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime
		from oa_esm_project_person c where 1=1"
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "ids",
		"sql" => " and c.Id in(arr)"
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
		"name" => "activityIds",
		"sql" => " and c.activityId in(arr) "
	),
	array (
		"name" => "activityName",
		"sql" => " and c.activityName=# "
	),
	array (
		"name" => "personLevelId",
		"sql" => " and c.personLevelId=# "
	),
	array (
		"name" => "personLevel",
		"sql" => " and c.personLevel=# "
	),
	array (
		"name" => "planBeginDate",
		"sql" => " and c.planBeginDate=# "
	),
	array (
		"name" => "planEndDate",
		"sql" => " and c.planEndDate=# "
	),
	array (
		"name" => "days",
		"sql" => " and c.days=# "
	),
	array (
		"name" => "personDays",
		"sql" => " and c.personDays=# "
	),
	array (
		"name" => "personCostDays",
		"sql" => " and c.personCostDays=# "
	),
	array (
		"name" => "personCost",
		"sql" => " and c.personCost=# "
	),
	array (
		"name" => "number",
		"sql" => " and c.number=# "
	),
	array (
		"name" => "price",
		"sql" => " and c.price=# "
	),
	array (
		"name" => "coefficient",
		"sql" => " and c.coefficient=# "
	),
	array (
		"name" => "status",
		"sql" => " and c.status=# "
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
	)
)
?>