<?php
$sql_arr = array (
	//默认sql语句
	"select_default" => "select c.id,c.pointName,c.planBeginDate,c.planEndDate,c.realBeginDate,c.realEndDate,c.projectId,c.projectCode,c.projectName,c.milestoneId,c.milestoneCode,c.createId,c.createName,c.createTime,c.updateId,c.updateName,c.updateTime,c.isUsing,c.versionNum,c.code,c.status,c.parentId,c.frontCode " .
			"from oa_rd_milestone_point c from oa_rd_milestone_point c where 1=1"
	,"select_readCenter" => "select c.id,c.pointName,c.planBeginDate,c.planEndDate,c.realBeginDate,c.realEndDate,c.projectId,c.projectCode,c.projectName,c.milestoneId,c.milestoneCode,c.createId,c.createName,c.createTime,c.updateId,c.updateName,c.updateTime,c.isUsing,c.versionNum,c.code,c.status,c.parentId,c.frontCode,c.effortRate,c.warpRate " .
			"from oa_rd_milestone_point c where 1=1"
	,"select_readSelect" => "select c.id,c.pointName,c.frontCode,c.code,c.status,c.realBeginDate,c.realEndDate,c.effortRate,c.warpRate from oa_rd_milestone_point c where 1=1",
    "sum_mildestoneEff" => " select sum(c.effortRate) as thisEff , sum(c.warpRate) as thisWarp ,count(1) as taskNum from oa_rd_task c where 1=1 ",
    "sum_mildestoneCom" => " select count(1) as taskNum ,count(if(c.status !='TG',null,1)) as taskCompleted from oa_rd_task c where 1=1 "
);

$condition_arr = array (
	//通过Id查询
	array(
		"name" => "id",
		"sql" => "and c.id=#"
	)

	,array(
		"name" => "milestonId", //里程碑Id
		"sql" => "and c.milestoneId=#"
	)
	,array(
		"name" => "pjId", //项目Id
		"sql" => "and c.projectId=# "
	)
	,array(
		"name" => "statusArr", //状态数组
		"sql" => "and c.status in(arr) "
	)
	,array(
		"name" => "frontCode", //父编号
		"sql" => "and c.frontCode=# "
	)
    ,array(
        "name" => "stoneId", //里程碑id
        "sql" => "and c.stoneId=# "
    )
    ,array(
        "name" => "isStone", //是否里程碑
        "sql" => "and c.isStone=# "
    )
    ,array(
        "name" => "status", //状态
        "sql" => "and c.status in(arr) "
    )
);
?>

