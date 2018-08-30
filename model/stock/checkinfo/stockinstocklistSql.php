<?php

/**
 * @author Administrator
 * @Date 2010��12��21�� 21:00:18
 * @version 1.0
 * @description:�̵�����嵥 sql�����ļ�
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.checkId ,c.typecode ,c.proType ,c.productId ,c.sequence ,c.productName ,c.adjust  from oa_stock_instock_list c where 1=1 ",
	/*****************************************����������***********************************/
	"sql_examine" => "select " .
	"w.task as taskId,p.ID as spid ,c.id ,c.checkId ,c.typecode ,c.proType ,c.productId ,c.sequence ,c.productName ,c.adjust ,c.ExaStatus ,c.ExaDT ,c.updateId ,c.updateName ,c.createTime ,c.createName ,c.createId ,c.updateTime from flow_step_partent p left join wf_task w on p.wf_task_id = w.task left join user u on u.USER_ID = p.User ,oa_stock_check_instock c " .
	" where w.Pid =c.id and w.examines <> 'no' "
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "checkId",
		"sql" => " and c.checkId=# "
	),
	array (
		"name" => "typecode",
		"sql" => " and c.typecode=# "
	),
	array (
		"name" => "proType",
		"sql" => " and c.proType=# "
	),
	array (
		"name" => "productId",
		"sql" => " and c.productId=# "
	),
	array (
		"name" => "sequence",
		"sql" => " and c.sequence=# "
	),
	array (
		"name" => "productName",
		"sql" => " and c.productName=# "
	),
	array (
		"name" => "adjust",
		"sql" => " and c.adjust=# "
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
		"name" => "updateId",
		"sql" => " and c.updateId=# "
	),
	array (
		"name" => "updateName",
		"sql" => " and c.updateName=# "
	),
	array (
		"name" => "createTime",
		"sql" => " and c.createTime=# "
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
		"name" => "updateTime",
		"sql" => " and c.updateTime=# "
	),
		//��˹�����
	array (
			"name" => "findInName", //������ID
	"sql" => " and  ( find_in_set( # , p.User ) > 0 ) "
	),
	array (
			"name" => "workFlowCode", //ҵ���
	"sql" => " and w.code =# "
	),
	array (
			"name" => "Flag", //ҵ���
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