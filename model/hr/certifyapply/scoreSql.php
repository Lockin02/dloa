<?php

/**
 * @author Show
 * @Date 2012年8月24日 星期五 11:43:39
 * @version 1.0
 * @description:任职资格评委打分表 - 主表 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.cassessId ,c.userName ,c.userAccount ,c.managerName ,c.managerId ,c.assessDate ,c.createId ,
			c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime ,c.sysCompanyName ,c.sysCompanyId,c.score
		from oa_hr_certifyapplyassess_score c where 1=1 ",
	"select_score" => "select s.id ,s.userName ,s.userAccount , s.managerName,s.managerId ,s.createId ,s.createName ,
			s.createTime ,s.updateId ,s.updateName ,s.updateTime ,s.sysCompanyName ,s.sysCompanyId,s.status,
			c.id as scoreId,ifnull(c.id,0) as scoreStatus,c.score,c.assessDate,c.managerId as scoreManagerId
		from oa_hr_certifyapplyassess s left join oa_hr_certifyapplyassess_score c on s.id = c.cassessId where 1"
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "cassessId",
		"sql" => " and c.cassessId=# "
	),
	array (
		"name" => "userName",
		"sql" => " and c.userName=# "
	),
	array (
		"name" => "suserNameSearch",
		"sql" => " and s.userName like concat('%',#,'%') "
	),
	array (
		"name" => "userAccount",
		"sql" => " and c.userAccount=# "
	),
	array (
		"name" => "managerName",
		"sql" => " and c.managerName=# "
	),
	array (
		"name" => "managerNameSearch",
		"sql" => " and c.managerName like concat('%',#,'%') "
	),
	array (
		"name" => "managerId",
		"sql" => " and c.managerId=# "
	),
	array (
		"name" => "assessUser",
		"sql" => " and (s.managerId=# or find_in_set(#,s.memberId))"
	),
	array(
		"name" => "scoreUser",
		"sql" => " and ifnull(c.managerId = #,1) "
	),
	array (
		"name" => "assessDate",
		"sql" => " and c.assessDate=# "
	),
	array (
		"name" => "createId",
		"sql" => " and c.createId=# "
	),
	array (
		"name" => "createName",
		"sql" => " and c.createName=# "
	),
	array (
		"name" => "createTime",
		"sql" => " and c.createTime=# "
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
		"name" => "updateTime",
		"sql" => " and c.updateTime=# "
	),
	array (
		"name" => "sysCompanyName",
		"sql" => " and c.sysCompanyName=# "
	),
	array (
		"name" => "sysCompanyId",
		"sql" => " and c.sysCompanyId=# "
	)
)
?>