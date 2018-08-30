<?php
$sql_arr = array (
	//Ĭ�Ϻ�ͬ�����б�
	"contract_list" => "select c.id,c.formalNo,c.temporaryNo,c.contNumber,c.contName,c.principalId,c.executorId,c.customerName,c.customerId,c.principalName,c.executorName,c.customerContNum,c.ExaStatus,c.version,c.changeStatus,c.contStatus,c.closeTime,c.signStatus,c.customerType,c.province from oa_contract_sales c where isUsing = '1' ",

	//���ۺ�ͬ�����������б�
	"contract_examining" => "select
							w.task,p.ID as id,c.id as contractId, c.contName, c.contNumber, c.principalName, c.customerName ,c.temporaryNo ,c.formalNo,c.province,c.customerType,c.customerContNum
							from  flow_step_partent p left join wf_task w on p.wf_task_id = w.task left join user u on u.USER_ID = p.User ,oa_contract_sales c
							where  p.Flag='0' and w.Pid =c.id and w.examines <> 'no'",
	"contract_examined" => "select
							w.task,p.ID as id,c.id as contractId, c.contName, c.contNumber, c.principalName,c.customerName ,c.temporaryNo ,c.formalNo ,c.ExaStatus,c.province,c.customerType,c.customerContNum
							from flow_step_partent p left join wf_task w on w.task = p.Wf_task_ID  left join user u on u.USER_ID = p.User ,oa_contract_sales c
							where p.Flag='1' and w.Pid =c.id",
	"contract_changing" => "select c.id,c.formalNo,c.temporaryNo,c.contNumber,c.contName,c.money,c.customerName,c.principalName,s.applyTime,S.id as changeId,s.ExaStatus as changeFormStatus,s.formNumber from oa_contract_sales c,oa_contract_change s where c.id = s.oldContId ",
	"contract_close" => "select c.id,c.formalNo,c.temporaryNo,c.contNumber,c.contName,s.doType,c.customerName,c.customerContNum,c.principalName,s.excuteDate from oa_contract_sales c left join oa_contract_common_bcinfo s on c.id = s.contractId where c.isUsing = '1' ",
	"contract_begin" => "select c.id,c.signStatus from oa_contract_sales c where 1=1 ",
	"version_list" => "select s.id,s.beginTime,s.contName,s.ExaStatus,c.formNumber,c.id as formId,c.applyTime,s.contStatus,s.updateTime from oa_contract_sales s left join oa_contract_change c on s.id = c.newContId where s.isUsing <> 0 "
);
$condition_arr = array (
	array(
		"name" => "equcontNumber",
		"sql" => "and c.contNumber = # "
	),
	array(
		"name" => "ajaxContNumber",
		"sql" => "and c.contNumber = # "
	),
	array(
		"name" => "exaStatus",
		"sql" => "and c.ExaStatus = # "
	),
	array(
	    "name" => "ChangeStatus",
	    "sql" => "and c.changeStatus",
	),
	array(
		"name" => "contStatus",
		"sql" => "and c.contStatus in(arr) "
	),
	array(
		"name" => "ExaStatus",
		"sql" => "and c.ExaStatus = # "
	),
	array(
		"name" => "shipCondition",
		"sql" => "and c.shipCondition in(arr) "
	),
	array (
		"name" => "contStatusEqual",//��ͬ״̬
		"sql" => "and c.contStatus = # "
	),
	array(
		"name" => "customerId",//�͑�
		"sql"=>" and c.customerId = # "
	),
	array(
		"name" => "principalId",//������
		"sql"=>" and c.principalId = # "
	),
	array(
		"name" => "executorId",//ִ����
		"sql"=>" and c.executorId = # "
	),
	array(
		"name" => "createId",//������
		"sql"=>" and c.createId = # "
	),
	array(
		"name" => "contName",//��������-��ͬ����
		"sql"=>" and c.contName like CONCAT('%',#,'%') "
	),
	array(
		"name" => "formalNo",//��������-��ʽ��ͬ��
		"sql"=>" and (c.formalNo like CONCAT('%',#,'%') or c.temporaryNo like CONCAT('%',#,'%') ) "
	),
	array(
		"name" => "contNumber",//��������-��ʱ��ͬ��
		"sql"=>" and c.contNumber like CONCAT('%',#,'%') "
	),
	array(
		"name" => "equC",
		"sql"=>" and c.contNumber = # "
	),
	array(
		"name" => "equformalNo",
		"sql"=>" and c.formalNo = # "
	),
	array(
		"name" => "equtemporaryNo",
		"sql"=>" and c.temporaryNo = # "
	),
	array(
		"name" => "equalCont",
		"sql"=>" and s.contNumber = # "
	),
	array(
		"name" => "findInName",//������
		"sql"=>" and  ( find_in_set( # , p.User ) > 0 ) "
	),
	array(
		"name" => "workFlow",//����������
		"sql"=>" and w.name in (#) "
	),
	array(
		"name" => "workFlowCode",//����������
		"sql"=>" and w.code =# "
	),
	array(
		"name" => "changeStatus",//ȷ�Ϻ�ͬ�б��״̬
		"sql"=>" and c.changeStatus = # "
	),
	array(
		"name" => "changingstatus",//
		"sql"=>" and s.ExaStatus in ('��������','���','���')  "
	),
	array(
		"name" => "isUsing",
		"sql"  => " and s.isUsing <> 0 "
	),
	array(
		"name" => "customerId",
		"sql"  => " and c.customerId =# "
	),
	array(
		"name" => "signStatusIn",
		"sql" => " and c.signStatus in(arr)"
	),
	array(
		"name" => "signStatus",
		"sql" => " and c.signStatus = #"
	)
);


?>