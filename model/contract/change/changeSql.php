<?php
$sql_arr = array (
	"change_list2" => "select c.id as id,s.contName,c.applyId,c.applyTime,s.contNumber,c.formNumber,c.ExaStatus,s.customerName,s.customerType,s.province,s.principalName,s.contStatus from oa_contract_change c left join oa_contract_sales s on s.id = c.newContId where c.isUsing='1' ",
	"change_exemine" =>"select
						w.task,p.ID as id ,c.id as changeId, c.contNumber, c.ExaStatus, c.applyName, c.applyTime ,c.formNumber ,c.formalNo
						from flow_step_partent p left join wf_task w on  w.task = p.Wf_task_ID left join user u on u.USER_ID = p.User ,oa_contract_change c
						where  p.Flag='0' and w.Pid =c.id and w.examines <> 'no'",
	"change_exemined" =>"select
						w.task,p.ID as id,c.id as changeId, c.contNumber, c.ExaStatus, c.applyName, c.applyTime ,c.formNumber ,c.formalNo
						from flow_step_partent p left join wf_task w on w.task = p.Wf_task_ID left join user u on u.USER_ID = p.User ,oa_contract_change c
						where p.Flag='1' and w.Pid =c.id "
);
$condition_arr = array (
   array (
		"name" => "applyId",
		"sql" => " and applyId =  # "
	),
	array (
		"name" => "contnumber",
		"sql" => " and contNumber = # "
	),
	array (
		"name" => "contid",
		"sql" => " and contId = # "
	),
	array (
		"name" => "createId",
		"sql" => " and c.createId = # "
	),
	array(
		"name" => "findInName",//负责人
		"sql"=>" and  ( find_in_set( # , p.User ) > 0 ) "
	),
	array(
		"name" => "workFlow",//负责人
		"sql"=>" and w.name in (#) "
	),
	array(
		"name" => "workFlowCode",//负责人
		"sql"=>" and w.code = # "
	)
	,array(
		"name" => "formNumber",//搜索条件-合同名称
		"sql"=>" and c.formNumber like CONCAT('%',#,'%') "
	),
	array(
		"name" => "seachformnumber",//搜索条件-合同名称
		"sql"=>" and c.formNumber like CONCAT('%',#,'%') "
	),
	array(
		"name" => "seachcontnumber",//搜索条件-合同编号
		"sql"=>" and c.contNumber like CONCAT('%',#,'%') "
	)
);
?>