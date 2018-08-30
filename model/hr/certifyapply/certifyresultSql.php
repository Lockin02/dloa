<?php

/**
 * @author Show
 * @Date 2012年8月28日 星期二 11:17:10
 * @version 1.0
 * @description:任职资格认证评价结果审核表 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.periodName ,c.periodId ,c.careerDirection ,c.careerDirectionName ,c.formDate ,c.formUserId ,c.formUserName ,c.ExaStatus ,c.ExaDT ,c.status ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime ,c.sysCompanyName ,c.sysCompanyId  from oa_hr_certifyresult c where 1=1 "
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "periodName",
		"sql" => " and c.periodName=# "
	),
	array (
		"name" => "periodId",
		"sql" => " and c.periodId=# "
	),
	array (
		"name" => "careerDirection",
		"sql" => " and c.careerDirection=# "
	),
	array (
		"name" => "careerDirectionName",
		"sql" => " and c.careerDirectionName=# "
	),
	array (
		"name" => "careerDirectionNameSearch",
		"sql" => " and c.careerDirectionName like concat('%',#,'%')"
	),
	array (
		"name" => "formDate",
		"sql" => " and c.formDate=# "
	),
	array (
		"name" => "formUserId",
		"sql" => " and c.formUserId=# "
	),
	array (
		"name" => "formUserName",
		"sql" => " and c.formUserName=# "
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
		"name" => "status",
		"sql" => " and c.status=# "
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