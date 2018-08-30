<?php

/**
 * @author Administrator
 * @Date 2010年12月21日 20:57:46
 * @version 1.0
 * @description:盘点入库 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.checkType ,c.stockId ,c.stockName ,c.ExaStatus,c.ExaDT, c.stockCode ," .
	"c.auditStatus ,c.dealUserId ,c.dealUserName ,c.auditUserName ,c.auditUserId ,c.createName ,c.createId" .
	" ,c.createTime ,c.updateName ,c.updateId ,c.updateTime  from oa_stock_check_instock c where 1=1 ",
	/*****************************************工作流部分***********************************/
	"sql_examine" => "select " .
	"w.task as taskId,p.ID as spid ,c.id ,c.checkType ,c.stockId ,c.stockName ,c.ExaStatus,c.ExaDT, c.stockCode ," .
	"c.auditStatus ,c.dealUserId ,c.dealUserName ,c.auditUserName ,c.auditUserId ,c.createName ,c.createId" .
	" ,c.createTime ,c.updateName ,c.updateId ,c.updateTime" .
	" from flow_step_partent p left join wf_task w on p.wf_task_id = w.task left join user u on u.USER_ID = p.User ,oa_stock_check_instock c " .
	" where w.Pid =c.id and w.examines <> 'no' "
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "checkType",
		"sql" => " and c.checkType=# "
	),
	array (
		"name" => "stockId",
		"sql" => " and c.stockId=# "
	),
	array (
		"name" => "stockName",
		"sql" => " and c.stockName=# "
	),
	array (
		"name" => "stockCode",
		"sql" => " and c.stockCode=# "
	),
	array (
		"name" => "ExaStatus",
		"sql" => " and c.ExaStatus=# "
	),
	array (
		"name" => "ExaDT",
		"sql" => " and c.ExaDT=# "
	),
	array (
		"name" => "auditStatus",
		"sql" => " and c.auditStatus in(arr) "
	),

	array (
		"name" => "dealUserId",
		"sql" => " and c.dealUserId=# "
	),
	array (
		"name" => "dealUserName",
		"sql" => " and c.dealUserName=# "
	),
	array (
		"name" => "auditUserName",
		"sql" => " and c.auditUserName=# "
	),
	array (
		"name" => "auditUserId",
		"sql" => " and c.auditUserId=# "
	),
	array (
		"name" => "createName",
		"sql" => " and c.createName=# "
	),
	array (
		"name" => "createId",
		"sql" => " and c.createId=# "
	),
	array (
		"name" => "createTime",
		"sql" => " and c.createTime=# "
	),
	array (
		"name" => "updateName",
		"sql" => " and c.updateName=# "
	),
	array (
		"name" => "updateId",
		"sql" => " and c.updateId=# "
	),
	array (
		"name" => "updateTime",
		"sql" => " and c.updateTime=# "
	),
		//审核工作流
	array (
			"name" => "findInName", //审批人ID
	"sql" => " and  ( find_in_set( # , p.User ) > 0 ) "
	),
	array (
			"name" => "workFlowCode", //业务表
	"sql" => " and w.code =# "
	),
	array (
			"name" => "Flag", //业务表
	"sql" => " and p.Flag= # "
	),
	array (
		"name" => "taskId",
		"sql" => " and taskId = #"
	),
	array (
		"name" => "spid",
		"sql" => "and spid=#"
	)
)
?>