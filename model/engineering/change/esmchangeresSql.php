<?php
/**
 * @author Show
 * @Date 2013年6月6日 星期四 15:38:39
 * @version 1.0
 * @description:项目资源计划变更表 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.changeId ,c.projectResourceId ,c.resourceId ,c.resourceCode ,c.resourceName ,
			c.projectResourceTypeId ,c.resourceTypeId ,c.resourceTypeCode ,c.resourceTypeName ,c.number ,c.unit ,
			c.planBeginDate ,c.planEndDate ,c.beignTime ,c.endTime ,c.useDays ,c.projectId ,c.projectCode ,
			c.projectName ,c.activityId ,c.activityName ,c.workContent ,c.remark ,c.createId ,
			c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime ,c.orgId ,c.isChanging ,c.changeAction,
			c.price,c.amount
		from oa_esm_change_resources c where 1=1 "
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
		"name" => "projectResourceId",
		"sql" => " and c.projectResourceId=# "
	),
	array (
		"name" => "resourceId",
		"sql" => " and c.resourceId=# "
	),
	array (
		"name" => "resourceCode",
		"sql" => " and c.resourceCode=# "
	),
	array (
		"name" => "resourceName",
		"sql" => " and c.resourceName=# "
	),
	array (
		"name" => "projectResourceTypeId",
		"sql" => " and c.projectResourceTypeId=# "
	),
	array (
		"name" => "resourceTypeId",
		"sql" => " and c.resourceTypeId=# "
	),
	array (
		"name" => "resourceTypeCode",
		"sql" => " and c.resourceTypeCode=# "
	),
	array (
		"name" => "resourceTypeName",
		"sql" => " and c.resourceTypeName=# "
	),
	array (
		"name" => "number",
		"sql" => " and c.number=# "
	),
	array (
		"name" => "unit",
		"sql" => " and c.unit=# "
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
		"name" => "beignTime",
		"sql" => " and c.beignTime=# "
	),
	array (
		"name" => "endTime",
		"sql" => " and c.endTime=# "
	),
	array (
		"name" => "useDays",
		"sql" => " and c.useDays=# "
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
		"name" => "workContent",
		"sql" => " and c.workContent=# "
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
		"name" => "isChanging",
		"sql" => " and c.isChanging=# "
	),
	array (
		"name" => "changeAction",
		"sql" => " and c.changeAction=# "
	)
)
?>