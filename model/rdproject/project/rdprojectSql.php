<?php
$sql_arr = array (
	"select_default" => "select c.id,c.projectCode,c.projectName,c.projectName as name,c.simpleName,c.businessCode,c.groupId,c.groupId as parentId," .
		"c.groupSName,c.projectType,c.projectLevel,c.depId,c.depName,c.managerId,c.managerName," .
		"c.assistantId,c.assistantName,c.description,c.planDateStart,c.planDateClose,c.actBeginDate,c.actEndDate," .
		"c.effortRate,c.warpRate,c.appraiseWorkload,c.putWorkload,c.clientName,c.clientId," .
		"c.clientAddress,c.status,c.createId,c.createName,c.createTime,c.updateId,c.updateName,c.updateTime,c.ExaStatus,c.ExaDT,c.lft,c.rgt " .
	" from oa_rd_project c where 1=1 "

	,"select_readAll"  => "select c.id,c.projectCode,c.projectName,c.projectName as name,c.simpleName,c.businessCode,c.groupId," .
		"c.groupSName,c.projectType,c.projectLevel,c.depId,c.depName,c.managerId,c.managerName," .
		"c.assistantId,c.assistantName,c.description,c.planDateStart,c.planDateClose,c.actBeginDate,c.actEndDate," .
		"c.effortRate,c.warpRate,c.appraiseWorkload,c.putWorkload,c.clientName,c.clientId," .
		"c.clientAddress,c.status,c.createId,c.createName,c.createTime,c.updateId,c.updateName,c.updateTime,c.updateTime,c.ExaStatus,c.ExaDT,c.lft,c.rgt,c.closeDescription " .
	" from oa_rd_project c where 1=1 "

	,"select_center_page" => "select c.id,c.projectCode,c.projectName,c.simpleName,c.projectName as name,c.businessCode,c.groupId," .
		"c.groupSName,c.projectType,c.projectLevel,c.depId,c.depName,c.managerId,c.managerName," .
		"c.assistantId,c.assistantName,c.planDateStart,c.planDateClose,c.actBeginDate,c.actEndDate," .
		"c.effortRate,c.warpRate,c.appraiseWorkload,c.putWorkload,c.status,c.lft,c.rgt" .
		",mp.pointName  " .
	" from " .
		" oa_rd_project c left join oa_rd_milestone_point mp on (c.id=mp.projectId and mp.status='2') " .
	" where 1=1 "

	,"select_Approval" =>"select c.id,c.projectCode,c.projectName,c.simpleName,c.groupSName,c.projectType,c.projectLevel," .
		"c.managerId,c.managerName,c.planDateStart,c.planDateClose,c.effortRate,c.warpRate,c.appraiseWorkload,c.putWorkload,c.status,c.createTime," .
		"w.task as wTask,p.ID as pId " .
	" from " .
		"oa_rd_project c,flow_step_partent p left join wf_task w on p.wf_task_id = w.task left join user u on u.USER_ID = p.User " .
	"where 1=1 " .
		" and w.Pid =c.id and w.examines <> 'no' "

	,"select_ManageUser"=>" select c.managerId,c.managerName,c.assistantId,c.assistantName from oa_rd_project c where 1=1 "
	,"select_pjdata"=>"select c.projectCode,c.id,c.status,c.projectName,c.simpleName,c.managerName,c.projectType, c.groupSName ,d.dataName  as projectLevel from oa_rd_project c, oa_system_datadict d where d.dataCode=c.projectLevel  "
	//项目组合及项目的视图
	,"select_view"=>"select * from(select c.*,
		case when c.status=6 then '99999'
			 when c.status=7 then '22222'
			 when c.status=8 then '11111'
			else '55555' end as statusGroup
		from oa_rd_project_view_group c where 1=1) c where 1=1"
	//项目组合及项目的视图
	,"select_DL"=>"select c.id ,c.number as projectCode,c.name as projectName,u.USER_NAME as managerName,c.description,c.manager as managerId,c.dept_id as deptId,d.DEPT_NAME as deptName
			from project_rd c left join user u on u.USER_ID = c.manager
					left join
				department d on c.dept_id = d.DEPT_ID where 1=1 and c.status = 0 "
	//项目组合及项目的视图
	,"select_all"=>"select
			c.projectType,c.id,c.projectId,c.projectCode,
			c.projectName,c.managerName,c.description,c.managerId,c.deptId,c.deptName
		from
		(
			select
				'esm' as projectType,concat('esm', cast(c.id as char(10))) as id,c.id as projectId,c.projectCode,
				c.projectCode as number,c.projectName as name,
				c.projectName,c.managerName,c.description,c.managerId,c.deptId as deptId,c.deptName as deptName
			from
				oa_esm_project c where c.contractType = 'GCXMYD-01' AND c.status IN('GCXMZT02', 'GCXMZT04', 'GCXMZT00')
			union all
			select
				'con' as projectType,concat('con', cast(c.id as char(10))) as id,p.id as projectId,p.projectCode,
				p.projectCode as number,p.projectName as name,
				p.projectName,prinvipalName as managerName,null AS description,prinvipalId AS managerId,
				c.prinvipalDeptId as deptId,c.prinvipalDept as deptName
			from oa_contract_project p
            LEFT JOIN oa_contract_contract c ON p.contractId = c.id
            where p.esmProjectId is null AND c.state = 2
		) c where 1 AND c.projectType <> 'con' "

);
$condition_arr = array (
	//项目Id
	array(
		"name" => "rpid",
		"sql" => " and c.Id=# "
	),
	array(
		"name" => "yprojectCode",
		"sql" => " and c.projectCode=# "
	)
	,array (
		"name" => "ajaxProjectName",//为了ajax判断groupName
		"sql" => "and c.projectName =# "
	)
	,array (
		"name" => "managerUser",//判断是否项可负责项目
		"sql" => "and ( c.managerId=# or  c.assistantId=# or c.assistantId like CONCAT('%,',#,',%') or c.assistantId like CONCAT('%,',#) or c.assistantId like CONCAT(#,',%')  ) "
	)
	,array (
		"name" => "managerId",//判断是否项可负责项目
		"sql" => "and c.managerId=#"
	)
	,array(
		"name" => "approvalUser",//判断是待审批人员
		"sql" => " and ( find_in_set( # , p.User ) > 0 ) "
	)
	,array(
		"name" => "statusArr",//业务状态条件
		"sql"=>" and c.status in(arr) "
	)
	,array(
		"name" => "deptIdArr",//业务状态条件
		"sql"=>" and c.deptId = '' "
	)
	,array(
		"name" => "status",//业务状态条件
		"sql"=>" and c.status  =# "
	)
	,array(
		"name" => "createUser",//业务状态条件
		"sql"=>" and c.createId=# "
	)
	,array(
		"name" => "myPUser",//我的项目 团队成员等人查看
		"sql"=>" and ( c.managerId=# or c.assistantId=# or c.assistantId like CONCAT('%,',#,',%') or c.assistantId like CONCAT('%,',#) or c.assistantId like CONCAT(#,',%') or exists(select 1 from oa_rd_team_member m where m.memberId = # and c.id=m.projectId) ) "
	)
	,array(
		"name" => "findInName",//审批人ID
		"sql"=>" and  ( find_in_set( # , p.User ) > 0 ) "
	),
	array(
		"name" => "Flag",//是否审批过
		"sql"=>" and p.Flag=#  "
	),
	array(
		"name" => "workFlowCode",//业务表
		"sql"=>" and w.code =# "
	)
	,array(
		"name" => "seachGroupSName",//
		"sql"=>" and c.groupSName like CONCAT('%',#,'%')  "
	)
	,array(
		"name" => "seachProjectCode",//
		"sql"=>" and c.projectCode like CONCAT('%',#,'%')  "
	)
	,array(
		"name" => "seachProjectName",//
		"sql"=>" and c.projectName like CONCAT('%',#,'%')  "
	),array(
		"name" => "projectName",//
		"sql"=>" and c.projectName like CONCAT('%',#,'%')  "
	)
	,array(
		"name" => "seachSimpleName",//
		"sql"=>" and c.simpleName like CONCAT('%',#,'%')  "
	)
	,array(
		"name" => "seachProjectType",//
		"sql"=>" and c.projectType like CONCAT('%',#,'%')  "
	)
	,array(
		"name" => "seachProjectLevel",//
		"sql"=>" and c.projectLevel like CONCAT('%',#,'%')  "
	)
	,array(
		"name" => "seachManagerName",//
		"sql"=>" and c.managerName like CONCAT('%',#,'%')  "
	)
	,array(
		"name" => "seachAssistantName",//
		"sql"=>" and c.assistantName like CONCAT('%',#,'%')  "
	)
	,array(
		"name" => "seachDescription",//
		"sql"=>" and c.description like CONCAT('%',#,'%')  "
	)
	,array(
		"name" => "seachPlanDateStartS",//
		"sql"=>" and c.planDateStart >=#  "
	)
	,array(
		"name" => "seachPlanDateStartB",//
		"sql"=>" and c.planDateStart <=#  "
	)
	,array(
		"name" => "seachPlanDateCloseS",//
		"sql"=>" and c.planDateClose >=#  "
	)
	,array(
		"name" => "seachPlanDateCloseB",//
		"sql"=>" and c.planDateClose <=#  "
	)
	,array (
		"name" => "name",//为了适应前台同时搜索项目组合跟项目，统一采用name
		"sql" => "and c.projectName like CONCAT('%',#,'%')"
	),
	array (
		"name" => "businessCode",
		"sql" => "and c.businessCode like CONCAT('%',#,'%')"
	),
	array (
		"name" => "groupId",
		"sql" => "and c.groupId = #"
	),
	//add by chengl 2011-04-09 添加为了支持视图
	array (
		"name" => "parentId",
		"sql" => "and parentId = #"
	),
	//add by chengl 2011-04-07 添加没有项目组合的项目条件
	array (
		"name" => "groupIdNull",
		"sql" => "and c.groupId is null"
	),
	//add by chengl 2011-04-07 添加过滤组合或者项目条件
	array (
		"name" => "ortype",
		"sql" => "or $"
	),
	array (
		"name" => "groupIds",
		"sql" => "and c.groupId in(arr)"
	),
	array (
		"name" => "parentCode",
		"sql" => "and c.parentCode = #"
	),
	array (
		"name" => "parentCodes",
		"sql" => "and c.parentCode in(arr)"
	),
	array (
		"name" => "ecode",
		"sql" => "and c.dataCode = #"
	),
	//权限过滤使用
	array (
 		"name" => "ft_projectType",
 		"sql" => " and c.projectType in(arr)"
	),
	array (
 		"name" => "ft_projectLevel",
 		"sql" => " and c.projectLevel in(arr)"
	),
	array (
 		"name" => "projectType",
 		"sql" => " and (c.projectType=# or c.projectType is null and c.id<>-1)"
	),
	array (
 		"name" => "ondoStatus",
 		"sql" => " and (c.status in(1,2,3,4,5,6) or c.projectType is null and c.id<>-1)"
	),
	array (
 		"name" => "finishedStatus",
 		"sql" => " and (c.status in(7,8) or c.projectType is null and c.id<>-1)"
	),
	array (
 		"name" => "deptIdStr",
 		"sql" => "$"
	),
	array (
 		"name" => "searhDProjectName",
 		"sql" => " and c.name like CONCAT('%',#,'%')"
	),
    array (
        "name" => "rdProjectCode",
        "sql" => " and c.number = #"
    ),
	array (
 		"name" => "searhDProjectCode",
 		"sql" => " and c.number like CONCAT('%',#,'%')"
	),
	array (
 		"name" => "is_delete",
 		"sql" => " and c.is_delete = #"
	),
	array (
 		"name" => "project_typeNo",
 		"sql" => " and c.project_type <> #"
	),
    array (
        "name" => "project_type",
        "sql" => " and c.project_type = #"
    ),
	array (
 		"name" => "projectNameSearch",
 		"sql" => " and c.projectName like concat('%',#,'%')"
	),
	array (
 		"name" => "projectCodeSearch",
 		"sql" => " and c.projectCode like concat('%',#,'%')"
	),
	array (
 		"name" => "projectType",
 		"sql" => " and c.projectType = #"
	),
	//add chenrf 合同分摊明细导入使用
	array(
		'name'=>'searchName',
		'sql'=>'and c.name = #'

	)
);