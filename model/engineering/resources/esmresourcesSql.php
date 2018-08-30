<?php
/**
 * @author Show
 * @Date 2011年12月10日 星期六 15:03:50
 * @version 1.0
 * @description:项目资源计划(oa_esm_project_resources) sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.resourceId ,c.resourceCode ,c.resourceName ,c.resourceTypeId ,c.resourceTypeCode ,
			c.resourceTypeName ,c.number ,c.unit ,c.planBeginDate ,c.planEndDate ,c.beignTime ,c.endTime ,c.useDays ,c.projectId ,
			c.projectCode ,c.projectName ,c.activityId ,c.activityName ,c.workContent ,c.remark ,c.createId ,
			c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime,c.resourceNature,c.dealStatus,c.dealManId,
			c.dealManName,c.dealDate,c.dealResult,c.price,c.amount,c.applyNo,c.applyId,c.sendNum,c.receviceNum,0 as thisType
		from oa_esm_project_resources c where 1=1 ",
	"select_tree" => "select c.id ,c.resourceId ,c.resourceCode as code ,c.resourceName as name from oa_esm_project_resources c where 1=1 ",
	"count_all" => "select sum(c.number) as number,sum(c.useDays) as useDays ,sum(c.amount) as amount from oa_esm_project_resources c where 1=1 ",
	"select_viewlist" => "select c.id ,c.resourceId ,c.resourceCode ,c.resourceName ,c.resourceTypeId ,c.resourceTypeCode ,
			c.resourceTypeName ,c.number ,c.unit ,c.planBeginDate ,c.planEndDate ,c.beignTime ,c.endTime ,c.useDays ,c.projectId ,
			c.projectCode ,c.projectName ,c.activityId ,c.activityName ,c.workContent ,c.remark ,c.createId ,
			c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime,c.resourceNature,c.dealStatus,c.dealManId,
			c.dealManName,c.dealDate,c.dealResult,c.price,c.amount,c.applyNo,c.applyId,c.sendNum,c.receviceNum,a.ExaStatus,a.status
		from oa_esm_project_resources c left join oa_esm_resource_apply a on c.applyId=a.id where 1=1 ",
	"select_change" => "select c.id ,c.changeId ,c.resourceId ,c.resourceCode ,c.resourceName ,
				c.resourceTypeId ,c.resourceTypeCode ,c.resourceTypeName ,c.number ,c.unit ,c.amount,c.price,
				c.planBeginDate ,c.planEndDate ,c.beignTime ,c.endTime ,c.useDays ,c.projectId ,c.projectCode ,
				c.projectName ,c.activityId ,c.activityName ,c.workContent ,c.remark ,c.createId ,
				c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime ,c.orgId ,c.isChanging ,c.changeAction,thisType,
				c.uid
			 from
			(select c.id ,'' as changeId ,c.resourceId ,c.resourceCode ,c.resourceName ,
				c.resourceTypeId ,c.resourceTypeCode ,c.resourceTypeName ,c.number ,c.unit ,c.amount,c.price,
				c.planBeginDate ,c.planEndDate ,c.beignTime ,c.endTime ,c.useDays ,c.projectId ,c.projectCode ,
				c.projectName ,c.activityId ,c.activityName ,c.workContent ,c.remark ,c.createId ,
				c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime ,'' as orgId ,'' as isChanging ,c.changeAction,
				0 as thisType,'' as uid
			from
				oa_esm_project_resources c
			where c.isChanging = 0
			union all
			select concat('change',cast(id as char(20))) as id ,c.changeId ,c.resourceId ,c.resourceCode ,c.resourceName ,
				c.resourceTypeId ,c.resourceTypeCode ,c.resourceTypeName ,c.number ,c.unit ,c.amount,c.price,
				c.planBeginDate ,c.planEndDate ,c.beignTime ,c.endTime ,c.useDays ,c.projectId ,c.projectCode ,
				c.projectName ,c.activityId ,c.activityName ,c.workContent ,c.remark ,c.createId ,
				c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime ,c.orgId ,c.isChanging ,c.changeAction,
				1 as thisType,c.id as uid
			from oa_esm_change_resources c
			where isChanging = 1 and c.changeAction <> 'delete') c
			where 1=1  ",
	"count_change" => "select max(c.changeId) as id,sum(c.amount) as amount,sum(c.number) as number,sum(c.useDays) as useDays
			 from
			(select c.id ,'' as changeId ,c.resourceId ,c.resourceCode ,c.resourceName ,
				c.resourceTypeId ,c.resourceTypeCode ,c.resourceTypeName ,c.number ,c.unit ,c.amount,c.price,
				c.planBeginDate ,c.planEndDate ,c.beignTime ,c.endTime ,c.useDays ,c.projectId ,c.projectCode ,
				c.projectName ,c.activityId ,c.activityName ,c.workContent ,c.remark ,c.createId ,
				c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime ,'' as orgId ,'' as isChanging ,c.changeAction
			from
				oa_esm_project_resources c
			where c.isChanging = 0
			union all
			select concat('change',cast(id as char(20))) as id ,c.changeId ,c.resourceId ,c.resourceCode ,c.resourceName ,
				c.resourceTypeId ,c.resourceTypeCode ,c.resourceTypeName ,c.number ,c.unit ,c.amount,c.price,
				c.planBeginDate ,c.planEndDate ,c.beignTime ,c.endTime ,c.useDays ,c.projectId ,c.projectCode ,
				c.projectName ,c.activityId ,c.activityName ,c.workContent ,c.remark ,c.createId ,
				c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime ,c.orgId ,c.isChanging ,c.changeAction
			from oa_esm_change_resources c
			where isChanging = 1 and c.changeAction <> 'delete') c
			where 1=1 "
);

$condition_arr = array (
    array (
        "name" => "applyId",
        "sql" => " and c.applyId=#"
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
		"name" => "resourceCodeSearch",
		"sql" => " and c.resourceCode like CONCAT('%',#,'%') "
	),
	array (
		"name" => "codeOrName",
		"sql" => " and (c.resourceCode like CONCAT('%',#,'%') or c.resourceName like CONCAT('%',#,'%'))"
	),
	array (
		"name" => "resourceName",
		"sql" => " and c.resourceName=# "
	),
	array (
		"name" => "resourceNameSearch",
		"sql" => " and c.resourceName like CONCAT('%',#,'%')  "
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
		"name" => "ids",
		"sql" => " and c.id in(arr)"
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
		"sql" => " and c.activityId in(arr)"
	),
	array (
		"name" => "activityName",
		"sql" => " and c.activityName=# "
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
		"name" => "resourceNature",
		"sql" => " and c.resourceNature=# "
	),
    array(
   		"name" => "dealStatus",
   		"sql" => " and c.dealStatus=# "
    )
);