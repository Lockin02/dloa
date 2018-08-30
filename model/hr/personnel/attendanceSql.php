<?php

/**
 * @author Administrator
 * @Date 2012年5月31日 17:03:17
 * @version 1.0
 * @description:考勤信息 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.userNo ,c.userAccount ,c.userName ,c.companyType ,c.companyName ,c.deptNameS ,
			c.deptIdS ,c.deptNameT ,c.deptIdT ,c.beginDate ,c.endDate ,c.days ,c.typeCode ,c.typeName ,c.docStatus ,
			c.docStatusName ,c.inputId ,c.inputName ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,
			c.updateTime  from oa_hr_personnel_attendance c where 1=1 "
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "userNoM",
		"sql" => " and c.userNo like CONCAT('%',#,'%') "
	),
	array (
		"name" => "userNo",
		"sql" => " and c.userNo=# "
	),
	array (
		"name" => "userAccount",
		"sql" => " and c.userAccount=# "
	),
	array (
		"name" => "userName",
		"sql" => " and c.userName=# "
	),
	array (
		"name" => "userNameM",
		"sql" => " and c.userName like CONCAT('%',#,'%') "
	),
	array (
		"name" => "companyType",
		"sql" => " and c.companyType=# "
	),
	array (
		"name" => "companyName",
		"sql" => " and c.companyName=# "
	),
	array (
		"name" => "deptName",
		"sql" => " and (c.deptNameS like CONCAT('%',#,'%') or c.deptNameT like CONCAT('%',#,'%') ) "
	),
	array (
		"name" => "deptNameS",
		"sql" => " and c.deptNameS=# "
	),
	array (
		"name" => "deptIdS",
		"sql" => " and c.deptIdS=# "
	),
	array (
		"name" => "deptNameT",
		"sql" => " and c.deptNameT=# "
	),
	array (
		"name" => "deptIdT",
		"sql" => " and c.deptIdT=# "
	),
	array (
		"name" => "beginDate",
		"sql" => " and c.beginDate=# "
	),
	array (
		"name" => "endDate",
		"sql" => " and c.endDate=# "
	),
	array (
		"name" => "days",
		"sql" => " and c.days=# "
	),
	array (
		"name" => "typeCode",
		"sql" => " and c.typeCode=# "
	),
	array (
		"name" => "typeName",
		"sql" => " and c.typeName=# "
	),
	array (
		"name" => "docStatus",
		"sql" => " and c.docStatus=# "
	),
	array (
		"name" => "docStatusName",
		"sql" => " and c.docStatusName=# "
	),
	array (
		"name" => "inputId",
		"sql" => " and c.inputId=# "
	),
	array (
		"name" => "inputName",
		"sql" => " and c.inputName=# "
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
	)
)
?>