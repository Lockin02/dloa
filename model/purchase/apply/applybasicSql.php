<?php
$sql_arr = array (
//	"list_page" => "select  " .
//			" id,applyNumb,applyVersionNumb,isUse,typeTabName,typeTabId,createId,createName,createTime,updateId,updateName,updateTime,sendUserId,sendName,sendTime,dateHope,dateReceive,dateFact,instruction,remark,state,suppId,suppName,suppTel,suppBank,suppAccount,suppAddress,billingType,paymetType,ExaStatus,ExaDT  " .
//			"from " .
//			"oa_purch_apply_basic where 1=1",
	"list_page" => "select  " .
			" id,applyNumb,createId,createName,createTime,updateId,updateName,updateTime,sendUserId,sendName,sendTime,dateHope,dateReceive,dateFact,instruction,remark,state,suppId,suppName,suppTel,suppBank,suppAccount,suppAddress,billingType,paymetType,ExaStatus,ExaDT  " .
			"from " .
			"oa_purch_apply_basic where 1=1",
	"list_Approval" => "select " .
							"w.task , p.ID as flowId, w.code ,w.Pid as Pid,w.name, u.USER_NAME as UserName , w.start,d.DEPT_NAME as DeptName,i.*  " .
						"from " .
							"flow_step_partent p , " .
							"oa_purch_apply_basic i left join wf_task w on ( i.Id=w.Pid ), " .
							"user u left join department d on( d.DEPT_ID = u.DEPT_ID ) " .
						"where 1=1 ",
						         "select_default"=>"select c.id ,c.objCode ,c.applyNumb ,c.sendUserId ,c.sendName ,c.sendTime ,c.dateHope ,c.dateReceive ,c.dateFact ,c.instruction ,c.remark ,c.state ,c.suppId ,c.suppName ,c.suppTel ,c.suppBank ,c.suppAccount ,c.suppAddress ,c.billingType ,c.paymetType ,c.ExaStatus ,c.ExaDT ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime  from oa_purch_apply_basic c where 1=1 ",
	"select_amount" => "select  " .
		        		"SUM(amountAll) " .
			        	"from " .
			        		"oa_purch_apply_basic c " .
			        	"left join oa_purch_apply_equ d on " .
			        		"c.id = d.basicId " .
			        	"where ".
			        		"c.isTemp <> '1' and " .
			        		"c.ExaStatus='Íê³É'  "
);
$condition_arr = array (
	array (
		"name" => "seachApplyNumb",
		"sql" => " and applyNumb like CONCAT('%',#,'%') "
	),
	array (
		"name" => "applyNumb",
		"sql" => " and applyNumb=# "
	),
//	array(
//		"name" => "isUse",
//		"sql" => " and isUse=# "
//	),

	array(
		"name" => "createName",
		"sql" => " and createName =# ",
	),
	array(
		"name" => "createId",
		"sql" => " and createId =# ",
	),

	array(
		"name" => "state",
		"sql" => " and state=# "
	),
	array(
		"name" => "stateArr",
		"sql" => " and state in(arr) "
	),
	array(
		"name" => "id",
		"sql" => " and id=# "
	),


	array(
		"name" => "wfCode",
		"sql" => " and w.code=# "
	),
	array(
		"name" => "wfFlag",
		"sql" => " and p.flag=# "
	),
	array(
		"name" => "wfTake",
		"sql" => " and p.wf_task_id=w.task "
	),
	array(
		"name" => "wfUser",
		"sql" => " and find_in_set(#,p.User)>0 "
	),
	array(
		"name" => "wfStatus",
		"sql" => " and w.Status=# "
	),
	array(
		"name" => "wfExamines",
		"sql" => " and w.examines='' "
	),
	array(
		"name" => "wfEnter_user",
		"sql" => " and u.USER_ID=w.Enter_user "
	),
	array(
		"name" => "wfName",
		"sql" => " and w.name in(arr) "
	),
	array(
		"name" => "id",
		"sql" => " and c.id=#"
	),
	array(
		"name" => "productId",
		"sql" => " and d.productId=#"
	),
	array(
		"name" => "nearYear",
		"sql" => " and c.formDate like CONCAT('%',#,'%') "
	)
);
?>
