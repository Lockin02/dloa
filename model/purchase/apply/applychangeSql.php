<?php
$sql_arr = array (
	"list_page" => "select " .
			"id,changeNumb,name,reason,effect,createId,createName,createTime,updateId,updateName,updateTime,ExaStatus,ExaDT,basicNumb,idOld,idNew,state   " .
			"from oa_purch_apply_change where 1=1",
	"list_Approval" =>  "select " .
							"w.task , p.ID as flowId, w.code ,w.Pid as Pid,w.name, u.USER_NAME as UserName , w.start,d.DEPT_NAME as DeptName,i.*  " .
						"from " .
							"flow_step_partent p , " .
							"oa_purch_apply_change i left join wf_task w on ( i.Id=w.Pid ), " .
							"user u left join department d on( d.DEPT_ID = u.DEPT_ID ) " .
						"where 1=1 "
);
$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and id=# "
	),
	array (
		"name" => "createId",
		"sql" => " and createId=# "
	),
	array(
		"name" => "state",
		"sql" => " and state=# "
	),
	array (
		"name" => "stateArr",
		"sql" => " and state in(arr) "
	),
	array (
		"name" => "seachChangeNumb",
		"sql" => " and changeNumb like CONCAT('%',#,'%') "
	),
	array (
		"name" => "seachBasicNumb",
		"sql" => " and basicNumb like CONCAT('%',#,'%') "
	),

	//审批使用到搜索条件
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
);
?>
