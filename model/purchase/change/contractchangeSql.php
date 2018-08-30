<?php
$sql_arr = array(
	//Ĭ���������
	"select_default" => "select c.id ,c.objCode ,c.applyNumb ,c.sendUserId ,c.sendName ,c.sendTime ,c.dateHope ,c.dateFact ," .
			"c.dateReceive ,c.instruction ,c.remark ,c.state ,c.suppId ,c.suppName ,c.suppTel ,c.suppBank ,c.suppAccount ,c.suppAddress ," .
			"c.billingType ,c.billingTypeName,c.paymentCondition ,c.paymentType ,c.ExaStatus ,c.ExaDT ,c.hwapplyNumb ,c.signStatus ,c.isChange ,c.createId ,c.createName ," .
			"c.createTime ,c.updateId ,c.updateName ,c.updateTime ,c.isTemp ,c.originalId  from oa_purch_apply_basic c where c.isTemp =1",
	/*****************************************����������***********************************/
	"sql_examine" => "select " .
		"w.task as taskId,p.ID as spid ,c.id ,c.objCode ,c.applyNumb ,c.sendUserId ,c.sendName ,c.sendTime ,c.dateHope ,c.dateFact ," .
			"c.dateReceive ,c.instruction ,c.remark ,c.state ,c.suppId ,c.suppName ,c.suppTel ,c.suppBank ,c.suppAccount ,c.suppAddress ," .
			"c.billingType ,c.paymentCondition ,c.paymentType ,c.ExaStatus ,c.ExaDT ,c.hwapplyNumb ,c.signStatus ,c.isChange ,c.createId ,c.createName   " .
		"from flow_step_partent p left join wf_task w on p.wf_task_id = w.task left join user u on u.USER_ID = p.User ,oa_purch_apply_basic c " .
		" where c.isTemp=1 and w.Pid =c.id and w.examines <> 'no' ",
	"sql_examine2" => "select " .
		"w.task as taskId,p.ID as spid ,c.id ,c.objCode ,c.applyNumb ,c.sendUserId ,c.sendName ,c.sendTime ,c.dateHope ,c.dateFact ," .
			"c.dateReceive ,c.instruction ,c.remark ,c.state ,c.suppId ,c.suppName ,c.suppTel ,c.suppBank ,c.suppAccount ,c.suppAddress ," .
			"c.billingType ,c.paymentCondition ,c.paymentType ,c.ExaStatus ,c.ExaDT ,c.hwapplyNumb ,c.signStatus ,c.isChange ,c.createId ,c.createName  " .
		"from flow_step_partent p left join wf_task w on p.wf_task_id = w.task left join user u on u.USER_ID = p.User ,oa_purch_apply_basic c " .
		" where c.isTemp=1 and w.Pid =c.id ",
);


$condition_arr = array(
	array(
		"name" => "id",
		"sql" => "and c.id=# "
	),
	//ͨ���汾��������
	array(
		"name" => "version",
		"sql" => "and c.version = # "
	),
	array(
		"name"=>"sendName",
		"sql"=>" and c.sendName like CONCAT('%',#,'%')"
	),
	array(
		"name"=>"suppName",
		"sql"=>" and c.suppName like CONCAT('%',#,'%')"
	),
	array(
		"name"=>"hwapplyNumb",
		"sql"=>" and c.hwapplyNumb like CONCAT('%',#,'%')"
	),
	array(
		"name"=>"state",
		"sql"=>" and c.state = #"
	),
	array(
		"name" => "createName",
		"sql" => "and c.createName = #"
	)
	,array(
		"name" => "ExaStatus",
		"sql" => "and c.ExaStatus in(arr)"
	)
	//��˹�����
	,array(
		"name" => "findInName",//������ID
		"sql"=>" and  ( find_in_set( # , p.User ) > 0 ) "
	),
	array(
		"name" => "workFlowCode",//ҵ���
		"sql"=>" and w.code =# "
	),
	array(
		"name" => "Flag",//ҵ���
		"sql"=>" and p.Flag= # "
	),
	//����������������
	array (
		"name" => "seachApplyNumb",
		"sql" => " and hwapplyNumb like CONCAT('%',#,'%') "
	),
	array (
		"name" => "applyNumb",
		"sql" => " and c.applyNumb=# "
	),
	array(
		"name" => "createId",
		"sql" => " and c.createId =# ",
	),

	array(
		"name" => "state",
		"sql" => " and c.state=# "
	),
	array(
		"name" => "stateArr",
		"sql" => " and c.state in(arr) "
	),
	array(
		"name" => "notState",
		"sql" => " and c.state not in(arr) "
	),
	//��ͬ�ŵ�Ψһ����֤
	array(
		"name" => "ajaxContractNumb",//ajax��֤���
		"sql"=>" and c.hwapplyNumb = # "
	),
	array(
		"name" => "updateTime",//ajax��֤���
		"sql"=>" and c.updateTime = # "
	),
	array(
		"name" => "orderTime",
		"sql" => "and date_format(c.createTime,'%Y-%m-%d') like CONCAT('%',#,'%')"
	),
	array(
		"name" => "productNumb",
		"sql" => "and c.id in(select basicId from oa_purch_apply_equ where productNumb like CONCAT('%',#,'%'))"
	),
	array(
		"name" => "productName",
		"sql" => "and c.id in(select basicId from oa_purch_apply_equ where productName like CONCAT('%',#,'%'))"
	)



);


?>