<?php
$sql_arr = array ("select_mail" => "select c.id,c.applyId,c.applyNo,c.applyType,
		c.mailDate,c.expectDate,c.customerId,c.customerName,
		c.linkman,c.tel,c.zipCode,c.mailAddress,c.mailType,c.remark,
		c.notifyId,c.notifyName,c.status,c.signInfo,c.createId,c.createName,
		c.createTime,c.updateId,c.updateName,c.updateTime,c.ExaStatus,c.ExaDT
		 from oa_mail_apply c where 1=1  ",

 "select_mails" => "select c.id,c.applyId,c.applyNo,c.applyType,
		c.mailDate,c.expectDate,c.customerId,c.customerName,
		c.linkman,c.tel,c.zipCode,c.mailAddress,c.mailType,c.remark,
		c.notifyId,c.notifyName,c.status,c.signInfo,c.createId,c.createName,
		c.createTime,c.updateId,c.updateName,c.updateTime,c.ExaStatus,c.ExaDT,o.docType
		 from oa_mail_apply c left join oa_stock_outapply o on c.applyId = o.id where 1=1  ",

	"sql_examine" => "select w.task as taskId,p.ID as spid , c.id,c.applyId,c.applyNo,c.applyType,
		c.mailDate,c.expectDate,c.customerId,c.customerName,
		c.linkman,c.tel,c.zipCode,c.mailAddress,c.mailType,c.remark,
		c.notifyId,c.notifyName,c.status,c.signInfo,c.createId,c.createName,
		c.createTime,c.updateId,c.updateName,c.updateTime,c.ExaStatus,c.ExaDT" .
		" from flow_step_partent p left join wf_task w on p.wf_task_id = w.task left join user u on u.USER_ID = p.User ,oa_mail_apply c " .
		" where w.Pid =c.id and w.examines <> 'no' ",

	"sql_audited"=> "select w.task as taskId,p.ID as spid ,c.id,c.applyId,c.applyNo,c.applyType,
		c.mailDate,c.expectDate,c.customerId,c.customerName,
		c.linkman,c.tel,c.zipCode,c.mailAddress,c.mailType,c.remark,
		c.notifyId,c.notifyName,c.status,c.signInfo,c.createId,c.createName,
		c.createTime,c.updateId,c.updateName,c.updateTime,c.ExaStatus,c.ExaDT " .
		" from flow_step_partent p left join wf_task w on p.wf_task_id = w.task left join user u on u.USER_ID = p.User ,oa_mail_apply c " .
		" where p.Flag='1' and w.Pid =c.id "
 );
$condition_arr = array (
	array (
		"name" => "applyId",
		"sql" => "and c.applyId =#"
	),
	array (
		"name" => "docType",
		"sql" => "and c.docType =#"
	),
	array (
		"name" => "createId",
		"sql" => "and c.createId =#"
	),
	array (
		"name" => "applyType",
		"sql" => "and c.applyType =#"
	),
	array (
		"name" => "applyTypes",
		"sql" => "and c.applyType !=#"
	),
	array (
		"name" => "status",
		"sql" => "and c.status =#"
	),
	array (
		"name" => "ExaStatus",
		"sql" => "and c.ExaStatus =#"
	),
	array (
		"name" => "applyNo",
		"sql" => "and c.applyNo like CONCAT('%',#,'%')"
	),
	array (
		"name" => "outApplyExaStatus",
		"sql" => "and (c.applyId in(select id from oa_stock_outapply where ExaStatus =#) or c.applyId is null)"
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
);
?>
