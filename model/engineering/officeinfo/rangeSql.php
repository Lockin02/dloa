<?php

/**
 * @author Show
 * @Date 2012年11月21日 星期三 15:16:37
 * @version 1.0
 * @description:责任范围 oa_esm_office_range sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id,c.proName,c.proId,c.proCode,c.officeId,c.officeName,c.mainManager,
			c.mainManagerId,c.managerName,c.managerId,c.head,c.headId,c.productLine,c.productLineName,c.assistant,
			c.assistantId
		from oa_esm_office_range c where 1 ",
    "show" => "select c.proName,c.officeName
		from oa_esm_office_range c where 1 "
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "proName",
		"sql" => " and c.proName=# "
	),
	array (
		"name" => "proId",
		"sql" => " and c.proId=# "
	),
	array (
		"name" => "proCode",
		"sql" => " and c.proCode=# "
	),
	array (
		"name" => "officeId",
		"sql" => " and c.officeId=# "
	),
	array (
		"name" => "officeName",
		"sql" => " and c.officeName=# "
	),
	array (
		"name" => "mainManager",
		"sql" => " and c.mainManager=# "
	),
	array (
		"name" => "mainManagerId",
		"sql" => " and c.mainManagerId=# "
	),
	array (
		"name" => "managerName",
		"sql" => " and c.managerName=# "
	),
	array (
		"name" => "managerId",
		"sql" => " and c.managerId=# "
	),
	array (
		"name" => "findManagerId",
		"sql" => " and find_in_set(#,c.managerId)"
	)
);