<?php
$sql_arr = array (
		"select_readAll" => "select " .
			"c.id,c.planCode,c.planName,c.planBeginDate as planDateStart,c.planEndDate as planDateClose,c.realBeginDate,c.realEndDate," .
			"c.effortRate,c.warpRate,c.warpRate as warpRateMig,c.appraiseWorkload,c.putWorkload,c.parentId,c.lft,c.rgt," .
			"c.projectId,c.projectCode,c.projectName,c.isTemplate,c.isUse,p.purviewKey," .
			"c.createId,if((c.rgt-c.lft)=1,1,0) as leaf " .
			" from oa_rd_project_plan c left join oa_rd_plan_purview p on c.id = p.planId where 1=1 ",
		"easy_list" => "select c.id,c.planName,c.planBeginDate,c.planEndDate,c.projectName,c.parentId,c.appraiseWorkload,c.putWorkload," .
				"c.effortRate from oa_rd_project_plan c where 1=1 ",
		"select_plan" => "select c.id,c.projectId,c.projectName,c.projectCode,c.planName,c.planBeginDate,c.planEndDate,c.projectName,c.parentId,c.appraiseWorkload,c.putWorkload," .
				"c.managerId,c.managerName,c.effortRate from oa_rd_project_plan c where id<>-1 and 1=1 ",
		"canDelete" => "select if((c.rgt-c.lft)=1,1,0) as leaf from oa_rd_project_plan c where 1=1"

);


$condition_arr = array (
	//通过Id查询
	array(
		"name" => "id",
		"sql" => "and c.id=#"
	)
	,array(
		"name" => "parentId", //通过父亲Id
		"sql" => " and c.parentId=# "
	)
	,array(
		"name" => "parentIdNull",
		"sql" => " and c.parentId is null "
	)
	,array(
		"name" => "projectId", //通过项目Id
		"sql" => " and c.projectId=# "
	)
	,array(
		"name" => "loginId",
		"sql" => " and p.userId = # "
	)
);
?>
