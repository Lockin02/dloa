<?php
$sql_arr = array (
	"select_invcost" => "select c.id,c.pickingCode,c.pickingType,otherSubjects,
	c.departments,c.departmentsId,c.toUse,c.sourceType,c.formCode,
	c.stockName,c.stockId,c.pickName,c.pickId,c.sendName,c.sendId,c.createId,c.createName,c.createTime,c.updateId,c.updateName,c.updateTime,c.status,c.ExaStatus,c.ExaDT
	 from oa_stock_pickingapply c where 1=1 ",
	 "shipapply_auditing" =>
	"select
		w.task ,p.ID as id,u.USER_NAME as UserName, c.id as applyId,c.pickingCode,c.pickingType,
		c.sendName,c.stockName,c.createName,
		c.pickName,
		c.ExaStatus,c.ExaDT
		from
		flow_step_partent p
		left join wf_task w on p.wf_task_id = w.task
		left join user u on u.USER_ID = p.User ,
		oa_stock_pickingapply c
		 where
		p.Flag='0' and
		w.Pid =c.id and" .
		" w.examines <> 'no'" ,
	"shipapply_audited" =>
		"select
		w.task ,p.ID as id,u.USER_NAME as UserName, c.id as applyId,c.pickingCode,c.pickingType,
		c.sendName,c.stockName,c.createName,
		c.pickName,
		c.ExaStatus,c.ExaDT
		from
		flow_step_partent p
		left join wf_task w on p.wf_task_id = w.task
		left join user u on u.USER_ID = p.User ,
		oa_stock_pickingapply c
		 where
		p.Flag='1' and
		w.Pid =c.id ",

		/**
		 * 过滤已重新编辑数据
		 */
	"myapply_list" => "select c.id,c.pickingCode,c.pickingType,otherSubjects,
	c.departments,c.departmentsId,c.toUse,c.sourceType,c.formCode,
	c.stockName,c.stockId,c.pickName,c.pickId,c.sendName,c.sendId,c.createId,c.createName,c.createTime,c.updateId,c.updateName,c.updateTime,c.status,c.ExaStatus,c.ExaDT
	 from oa_stock_pickingapply c where c.status<>'reedit' and 1=1 ",


);
$condition_arr = array (
	array (
		"name" => "id",
		"sql" => "and c.id =#"
	),
	array (
		"name" => "pickingCode",
		"sql" => "and c.pickingCode =#"
	),
	array (
		"name" => "createId",
		"sql" => "and c.createId =#"
	),
	array (
		"name" => "pickingType",
		"sql" => "and c.pickingType like CONCAT('%',#,'%')"
	),
	array (
		"name" => "departments",
		"sql" => "and c.departments like CONCAT('%',#,'%')"
	),
	array(
		"name" => "findInName",//负责人
		"sql"=>" and  ( find_in_set( # , p.User ) > 0 ) "
	),
	array(
		"name" => "workFlow",//流程名称
		"sql"=>" and w.name in (#) "
	),
	array (
		"name" => "status",
		"sql" => "and c.status like CONCAT('%',#,'%')"
	),
	array(
		"name" => "workFlowCode",//流程名称
		"sql"=>" and w.code = # "
	)
);
?>