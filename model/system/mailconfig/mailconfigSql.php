<?php

/**
 * @author Show
 * @Date 2013年7月11日 星期四 13:30:10
 * @version 1.0
 * @description:通用邮件配置 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.objCode ,c.objName ,c.description ,c.mailTitle ,c.mailContent ,c.defaultUserName ,
			c.defaultUserId ,c.ccUserName ,c.ccUserId ,c.bccUserName ,c.bccUserId ,c.mainSource ,c.isItem ,c.itemSource,c.isMain
		from oa_system_mailconfig c where 1=1 "
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "objCode",
		"sql" => " and c.objCode=# "
	),
	array (
		"name" => "objCodeSearch",
		"sql" => " and c.objCode like concat('%',#,'%') "
	),
	array (
		"name" => "objName",
		"sql" => " and c.objName=# "
	),
	array (
		"name" => "objNameSearch",
		"sql" => " and c.objName like concat('%',#,'%') "
	),
	array (
		"name" => "description",
		"sql" => " and c.description=# "
	),
	array (
		"name" => "mailTitle",
		"sql" => " and c.mailTitle=# "
	),
	array (
		"name" => "mailTitleSearch",
		"sql" => " and c.mailTitle like concat('%',#,'%') "
	),
	array (
		"name" => "mailContent",
		"sql" => " and c.mailContent=# "
	),
	array (
		"name" => "mailContentSearch",
		"sql" => " and c.mailContent like concat('%',#,'%') "
	),
	array (
		"name" => "defaultUserName",
		"sql" => " and c.defaultUserName=# "
	),
	array (
		"name" => "defaultUserId",
		"sql" => " and c.defaultUserId=# "
	),
	array (
		"name" => "ccUserName",
		"sql" => " and c.ccUserName=# "
	),
	array (
		"name" => "ccUserId",
		"sql" => " and c.ccUserId=# "
	),
	array (
		"name" => "bccUserName",
		"sql" => " and c.bccUserName=# "
	),
	array (
		"name" => "bccUserId",
		"sql" => " and c.bccUserId=# "
	),
	array (
		"name" => "isMain",
		"sql" => " and c.isMain=# "
	),
	array (
		"name" => "mainSource",
		"sql" => " and c.mainSource=# "
	),
	array (
		"name" => "isItem",
		"sql" => " and c.isItem=# "
	),
	array (
		"name" => "itemSource",
		"sql" => " and c.itemSource=# "
	)
)
?>