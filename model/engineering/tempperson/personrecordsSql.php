<?php

/**
 * @author Show
 * @Date 2012年7月17日 星期二 19:13:24
 * @version 1.0
 * @description:临聘人员记录 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.personId ,c.personName ,c.idCardNo ,c.money ,c.workContent ,c.workDays ,c.projectId ,
			c.projectCode ,c.projectName ,c.activityName ,c.activityId ,c.createId ,c.createName ,c.createTime ,c.updateId ,
			c.updateName ,c.updateTime,c.thisDate,c.worklogId
		from oa_esm_tempperson_records c where 1=1 ",
	"select_count" => "select sum(c.money) as allMoney,sum(c.workDays) as allDays from oa_esm_tempperson_records c where 1=1 "
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
    array (
        "name" => "worklogId",
        "sql" => " and c.worklogId=# "
    ),
	array (
		"name" => "personId",
		"sql" => " and c.personId=# "
	),
	array (
		"name" => "personName",
		"sql" => " and c.personName=# "
	),
	array (
		"name" => "personNameSearch",
		"sql" => " and c.personName like concat('%',#,'%') "
	),
	array (
		"name" => "idCardNo",
		"sql" => " and c.idCardNo=# "
	),
	array (
		"name" => "money",
		"sql" => " and c.money=# "
	),
	array (
		"name" => "workContent",
		"sql" => " and c.workContent=# "
	),
	array (
		"name" => "workDays",
		"sql" => " and c.workDays=# "
	),
	array (
		"name" => "projectId",
		"sql" => " and c.projectId=# "
	),
	array (
		"name" => "projectCode",
		"sql" => " and c.projectCode=# "
	),
	array (
		"name" => "projectName",
		"sql" => " and c.projectName=# "
	),
	array (
		"name" => "activityName",
		"sql" => " and c.activityName=# "
	),
	array (
		"name" => "activityId",
		"sql" => " and c.activityId=# "
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
	)
)
?>