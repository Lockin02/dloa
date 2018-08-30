<?php

/**
 * @author Show
 * @Date 2012年8月31日 星期五 14:53:12
 * @version 1.0
 * @description:员工试用计划明细 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.planId ,c.taskName ,c.description ,c.managerName ,c.managerId ,c.weights ,c.memberName ,c.memberId ,c.status ,c.score ,c.scoreDesc ,c.beforeId ,c.beforeName,c.taskScore,c.baseScore,c.closeType ,c.isNeed ,c.isRule,c.handupDate,c.scoreDate
	from oa_hr_trialplan_detail c where 1=1 ",
	"countAll" => "select sum(c.baseScore) as baseScore,sum(c.taskScore) as taskScore from oa_hr_trialplan_detail c where 1=1 "
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "noId",
		"sql" => " and c.Id <> #"
	),
	array (
		"name" => "planId",
		"sql" => " and c.planId=# "
	),
	array (
		"name" => "taskName",
		"sql" => " and c.taskName=# "
	),
    array (
        "name" => "taskNameSearch",
        "sql" => " and c.taskName LIKE CONCAT('%',#,'%') "
    ),
	array (
		"name" => "description",
		"sql" => " and c.description=# "
	),
	array (
		"name" => "managerName",
		"sql" => " and c.managerName=# "
	),
	array (
		"name" => "managerId",
		"sql" => " and c.managerId=# "
	),
	array (
		"name" => "weights",
		"sql" => " and c.weights=# "
	),
	array (
		"name" => "memberName",
		"sql" => " and c.memberName=# "
	),
	array (
		"name" => "memberId",
		"sql" => " and c.memberId=# "
	),
	array (
		"name" => "status",
		"sql" => " and c.status=# "
	),
	array (
		"name" => "statusNo",
		"sql" => " and c.status <> #"
	),
	array (
		"name" => "score",
		"sql" => " and c.score=# "
	),
	array (
		"name" => "scoreDesc",
		"sql" => " and c.scoreDesc=# "
	),
	array (
		"name" => "beforeId",
		"sql" => " and c.beforeId=# "
	),
	array (
		"name" => "beforeName",
		"sql" => " and c.beforeName=# "
	),
	array(
		"name" => "isNeed",
		"sql" => " and c.isNeed = # "
	)
)
?>