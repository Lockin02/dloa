<?php

/**
 * @author huangzf
 * @Date 2011年11月1日 11:21:38
 * @version 1.0
 * @description:操作日志 sql配置文件
 */
$sql_arr = array (
	"select_default"=>"select c.id ,c.logSettingId ,c.pkValue ,c.columnCname ,c.oldValue ,c.newValue ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime  from oa_syslog_operation_item c where 1=1 ",
	"select_detail" => "select c.id ,c.logSettingId ,c.pkValue ,c.columnCname ,c.oldValue ,c.newValue ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime,s.tableName,s.businessName  from oa_syslog_operation_item
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
		"name" => "pkValue",
		"sql" => " and c.pkValue=# "
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