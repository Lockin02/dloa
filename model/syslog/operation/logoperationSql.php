<?php

/**
 * @author huangzf
 * @Date 2011年11月1日 11:21:38
 * @version 1.0
 * @description:操作日志 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.logSettingId ,c.operationType ,c.pkValue ,c.logContent ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime  from oa_syslog_operation c where 1=1 ",
	"select_detail" => "select c.id ,c.logSettingId ,c.operationType ,c.pkValue ,c.logContent ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime,s.tableName,s.businessName  from oa_syslog_operation
			  c  left join oa_syslog_setting s on(s.id=c.logSettingId) where 1=1 "
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "logSettingId",
		"sql" => " and c.logSettingId=# "
	),
	array (
		"name" => "operationType",
		"sql" => " and c.operationType=# "
	),
	array (
		"name" => "pkValue",
		"sql" => " and c.pkValue=# "
	),
	array (
		"name" => "tableName",
		"sql" => " and s.tableName=# "
	),
	array (
		"name" => "tableNameStr",
		"sql" => " and s.tableName in(arr)"
	),
	array (
		"name" => "businessName",
		"sql" => " and s.businessName=# "
	),
	array (
		"name" => "logContent",
		"sql" => " and c.logContent=# "
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
	),
	array(
		"name" => "beginYearMonth",
		"sql" => "and date_format(c.createTime,'%Y%m') >= # "
	),
	array(
		"name" => "endYearMonth",
		"sql" => "and date_format(c.createTime,'%Y%m') <= # "
	)
)
?>