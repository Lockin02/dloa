<?php
/**
 * @author Administrator
 * @Date 2011年12月12日 17:06:50
 * @version 1.0
 * @description:项目范围(oa_esm_project_activity) sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.activityName ,c.parentId ,c.parentName ,c.lft ,c.rgt ,
			if(c.isLeaf = 1,0,1) as isParent,c.projectId ,c.projectCode ,c.projectName ,c.workContent ,c.remark ,c.planBeginDate ,
			c.planEndDate ,c.days ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime,c.workRate,
			c.actBeginDate,c.actEndDate,c.process,c.workedDays,c.needDays,c.actDays,c.workload,c.workloadUnit,c.workloadUnitName,c.feeAll,c.budgetAll,
			c.memberId,c.memberName,c.workloadDone,c.isTrial
		from oa_esm_project_activity c where 1=1 ",
	"select_treelist" => "select c.id,c.activityName as name,c.lft ,c.rgt,if(c.rgt - c.lft = 1,'none','closed') as state,
			case (c.rgt-c.lft) when 1 then 0 else 1 end as isParent ,c.parentId,c.parentId as _parentId,c.planBeginDate,c.planEndDate,
			c.days,c.workRate ,c.workContent ,c.remark,c.workload,c.workloadUnitName,c.feeAll,c.budgetAll,c.memberId,c.memberName,0 as thisType,c.workloadDone
		from oa_esm_project_activity c where c.id<>-1",
	"select_treelistRtBoolean" => "select c.id,c.activityName as name,c.lft ,c.rgt,if(c.rgt - c.lft = 1,'none','closed') as state,
			case (c.rgt-c.lft) when 1 then 'false' else 'true' end as isParent,c.parentId,c.parentId as _parentId ,c.planBeginDate,c.planEndDate,
			c.days,c.workRate ,c.workContent ,c.remark,c.workload,c.workloadUnitName,c.feeAll,c.budgetAll,c.memberId,c.memberName,c.workloadDone
		from oa_esm_project_activity c where c.id<>-1",
	"count_list" => "select sum(c.actDays) as actDays ,
			sum(c.needDays) as needDays ,sum(if(c.parentId = -1 ,c.workRate,0)) as workRate ,
			min(c.planBeginDate) as planBeginDate,max(c.planEndDate) as planEndDate,
			round((UNIX_TIMESTAMP( max(c.planEndDate) ) - UNIX_TIMESTAMP( min(c.planBeginDate) ) )/(3600 *24)) + 1 as days,
			min(c.actBeginDate) as actBeginDate,max(c.actEndDate) as actEndDate,
			sum(c.workedDays) as workedDays,sum(c.feeAll) as feeAll,sum(c.budgetAll) as budgetAll,
			round(sum(if(c.parentId = -1,c.process,0)*if(c.parentId = -1,c.workRate,0)/100),2) as process,
			round(sum(if(c.parentId = -1,if(c.planBeginDate > CURRENT_DATE,0,
				if(
					(c.rgt-c.lft) = 1 AND c.planBeginDate <> '0000-00-00' AND c.planBeginDate IS NOT NULL AND c.planEndDate <> '0000-00-00' AND c.planEndDate IS NOT NULL,
					round(
						if(
							((
								(
									UNIX_TIMESTAMP(if(CURRENT_DATE > c.planEndDate ,c.planEndDate,CURRENT_DATE))
									-
									UNIX_TIMESTAMP(c.planBeginDate)
								)/86400 + 1
							)/c.days*100) > 100,
							100,
							((
								(
									UNIX_TIMESTAMP(if(CURRENT_DATE > c.planEndDate ,c.planEndDate,CURRENT_DATE))
									-
									UNIX_TIMESTAMP(c.planBeginDate)
								)/86400 + 1
							)/c.days*100)
						),2
					),
					''
				)
			),0)*if(c.parentId = -1,c.workRate,0)/100),2) as planProcess,
			sum(c.workload) as workload,
			sum(c.workloadDone) as workloadDone,
			sum(if(c.isTrial = 1,round(c.process*c.workRate/100,2),0)) as triProcess
		from oa_esm_project_activity c where 1 ",
	"sum_list" => "select sum(c.days) as days,sum(c.actDays) as actDays ,sum(c.needDays) as needDays ,
			sum(c.workRate) as workRate ,min(c.planBeginDate) as planBeginDate,max(c.planEndDate) as planEndDate,
			min(c.actBeginDate) as actBeginDate,max(c.actEndDate) as actEndDate,sum(c.workedDays) as workedDays,
			sum(round(c.process*c.workRate/100,2)) as process,sum(c.workload) as workload,sum(c.feeAll) as feeAll,sum(c.budgetAll) as budgetAll,
			sum(c.workloadDone) as workloadDone
		from oa_esm_project_activity c where 1 ",
	"treelist" => "select c.id,c.activityName,c.lft ,c.rgt,c.process,
			case (c.rgt-c.lft) when 1 then 'false' else 'true' end as isParent,c.parentId,c.parentId as _parentId ,c.planBeginDate,c.planEndDate,
			c.actBeginDate,c.actEndDate,c.days,c.workRate ,c.workContent ,c.remark,c.workloadUnitName,c.workload,c.feeAll,c.projectId,
			c.budgetAll,c.memberId,c.memberName,0 as isChanging,0 as thisType,c.workedDays,c.workloadDone,c.isTrial,
			if(c.planBeginDate > CURRENT_DATE,0,
				if(
					(c.rgt-c.lft) = 1 AND c.planBeginDate <> '0000-00-00' AND c.planBeginDate IS NOT NULL AND c.planEndDate <> '0000-00-00' AND c.planEndDate IS NOT NULL,
					round(
						(
							(
								UNIX_TIMESTAMP(if(CURRENT_DATE > c.planEndDate ,c.planEndDate,CURRENT_DATE))
								-
								UNIX_TIMESTAMP(c.planBeginDate)
							)/86400 + 1
						)/c.days*100,2
					),
					''
				)
			) as planProcess,
			c.confirmId,c.confirmName,c.confirmDate,c.status,c.stopDate,c.confirmDays
		from oa_esm_project_activity c where c.id<>-1 and 1=1 ",
	"parent_process" => "select sum(c.workRate*c.process/100) as process from oa_esm_project_activity c where 1",
    "select_change" => "select
			c.id ,c.id as uid,c.activityId,c.activityName,c.lft ,c.rgt,if(c.rgt - c.lft = 1,'none','opened') as state,
            case (c.rgt-c.lft) when 1 then 0 else 1 end as isParent ,c.parentId,c.parentId as _parentId,c.planBeginDate,c.planEndDate,
            c.days,c.workRate ,c.workContent ,c.remark,c.workload,c.workloadUnitName,0 as feeAll,0 as budgetAll,'' as memberId,'' as memberName,
			c.projectId,c.isChanging,c.changeAction,1 as thisType,c.process,c.changeId,c.isChanging,c.isTrial,
			if(a.id is null,0,if(a.planBeginDate > CURRENT_DATE,0,
				if(
					(a.rgt-a.lft) = 1 AND a.planBeginDate <> '0000-00-00' AND a.planBeginDate IS NOT NULL AND a.planEndDate <> '0000-00-00'
					AND a.planEndDate IS NOT NULL,
					round(
						(
							(
								UNIX_TIMESTAMP(if(CURRENT_DATE > a.planEndDate ,a.planEndDate,CURRENT_DATE))
								-
								UNIX_TIMESTAMP(a.planBeginDate)
							)/86400 + 1
						)/a.days*100,2
					),
					''
				)
			)) as planProcess,if(a.id is null,0,a.workloadDone) as workloadDone,
			'' as confirmId,'' as confirmName,'' as confirmDate,'' as status,'' as stopDate,'' as confirmDays
        from
            oa_esm_change_activity c
				left join
			oa_esm_project_activity a on c.activityId = a.id
        where c.changeAction <> 'delete' and c.isRoot = 0 ",
    "count_change" => "select
            sum(if(c.parentId = -1 ,c.workRate,0)) as workRate ,
	        min(c.planBeginDate) as planBeginDate,max(c.planEndDate) as planEndDate,
	        round((UNIX_TIMESTAMP( max(c.planEndDate) ) - UNIX_TIMESTAMP( min(c.planBeginDate) ) )/(3600 *24)) + 1 as days,
	        sum(round(if(c.parentId = -1,c.process,0)*if(c.parentId = -1,c.workRate,0)/100,2)) as process,sum(c.workload) as workload
        from
            oa_esm_change_activity c
        where c.changeAction <> 'delete' and c.isRoot = 0",
	"count_process" => "select (c.workloadDone/c.workload) * 100 as process from oa_esm_project_activity c where 1"
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.id=# "
	),
    array (
        "name" => "isTrial",
        "sql" => " and c.isTrial=# "
    ),
	array (
		"name" => "bigID",
		"sql" => " and c.id > #"
	),
	array (
		"name" => "activityName",
		"sql" => " and c.activityName like CONCAT('%',#,'%') "
	),
	array (
		"name" => "activityNameEq",
		"sql" => " and c.activityName  = # "
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
		"name" => "isLeaf",
		"sql" => " and c.rgt - c.lft = 1"
	),
	array (
		"name" => "lft",
		"sql" => " and c.lft=# "
	),
	array (
		"name" => "rgt",
		"sql" => " and c.rgt=# "
	),
	array (
		"name" => "biglft",
		"sql" => " and c.lft >= #"
	),
	array (
		"name" => "smallrgt",
		"sql" => " and c.rgt <= # "
	),
	array (
		"name" => "biglftNoEqu",
		"sql" => " and c.lft > #"
	),
	array (
		"name" => "smallrgtNoEqu",
		"sql" => " and c.rgt <= # "
	),
	array (
		"name" => "projectId",
		"sql" => " and c.projectId=# "
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
		"name" => "changeId",
		"sql" => " and c.changeId=# "
	),
	array (
		"name" => "memberIn",
		"sql" => " and id in (select activityId from oa_esm_project_activitymember where memberId = #)"
	)
);