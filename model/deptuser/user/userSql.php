<?php
$sql_arr = array (
	"select" => "select 'user' as icon,'user' as type,c.USER_ID as id,c.USER_ID,c.USER_NAME,c.PASSWORD,c.LogName,c.USER_NAME as name,b.NameCN as companyName,c.pwQue,c.pwAns,c.USER_PRIV,c.DEPT_ID,d.DEPT_NAME,c.SEX,c.EMAIL,c.NICK_NAME,c.AVATAR,c.CALL_SOUND,c.DUTY_TYPE,c.MENU_TYPE,c.MENU_HIDE,c.UIN,c.PIC_ID,c.AUTHORIZE,c.SORT,c.CANBROADCAST,c.HAS_LEFT,c.Company,c.AREA,c.NOT_LOGIN,c.DEL,c.Creator,c.CreatDT,c.Updator,c.UpdateDT,c.DeleteDT,c.Deletor,c.jobs_id,c.func_id_no,c.func_id_yes,d.comCode
	from user c
	left join department d on c.DEPT_ID=d.DEPT_ID
	left join branch_info b on b.NamePT=c.Company
	where c.DEL=0 and  c.has_left = 0 ",

	"selectAll" => "select 'user' as icon,'user' as type,c.USER_ID as id,c.USER_ID,c.USER_NAME,c.PASSWORD,c.LogName,
	c.USER_NAME as name,c.pwQue,c.pwAns,c.USER_PRIV,c.DEPT_ID,d.DEPT_NAME,c.SEX,c.EMAIL,c.NICK_NAME,c.AVATAR,
	c.CALL_SOUND,c.DUTY_TYPE,c.MENU_TYPE,c.MENU_HIDE,c.UIN,c.PIC_ID,c.AUTHORIZE,c.SORT,c.CANBROADCAST,c.HAS_LEFT,c.Company,c.AREA,c.NOT_LOGIN,c.DEL,c.Creator,c.CreatDT,c.Updator,c.UpdateDT,c.DeleteDT,c.Deletor,c.jobs_id,c.func_id_no,c.func_id_yes,
	d.needExpenseCheck,d.comCode,
	b.NameCN as CompanyName
	from user c
	left join department d on c.DEPT_ID=d.DEPT_ID
	left join branch_info b on b.NamePT=c.Company
	where c.DEL=0 ",

	"selectCount" => "select count(*) as count from user c where 1"
 );
$condition_arr = array (
	array (
		"name" => "USER_ID",
		"sql" => "and c.USER_ID =#"
	),
	array (
		"name" => "USER_NAME",
		"sql" => "and c.USER_NAME like CONCAT('%',#,'%')"
	),
	array (
		"name" => "userNameEq",
		"sql" => "and c.USER_NAME = #"
	),
	array (
		"name" => "userAndLogName",
		"sql" => "and (c.USER_NAME like CONCAT('%',#,'%') or c.LogName like CONCAT('%',#,'%'))"
	),
	array (
		"name" => "userName",
		"sql" => "and (c.USER_NAME like CONCAT('%',#,'%') or c.LogName like CONCAT('%',#,'%'))"
	),
	array (
		"name" => "DEPT_ID",
		"sql" => "and c.DEPT_ID =#"
	),
	array(
		"name" => "deptIds",
		"sql" => "and c.DEPT_ID in ($)"
	),
	array (
		"name" => "jobs_id",
		"sql" => "and c.jobs_id =#"
	),
	array (
		"name" => "has_left",
		"sql" => "and c.has_left =#"
	),
	array (
		"name" => "logNames",
		"sql" => "and c.logName in(arr) "
	),
	array (
		"name" => "userType",
		"sql" => "and c.userType =#"
	),
	array (
		"name" => "USER_IDS",
		"sql" => "and c.USER_ID in(arr) "
	),
	array (
		"name" => "comCode",
		"sql" => "and d.comCode =# "
	)
);
?>
