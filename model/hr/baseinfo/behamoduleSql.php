<?php

/**
 * @author Show
 * @Date 2012年8月20日 星期一 20:12:40
 * @version 1.0
 * @description:行为模块配置 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.moduleName ,c.remark  from oa_hr_baseinfo_behamodule c where 1=1 "
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "moduleName",
		"sql" => " and c.moduleName=# "
	),
	array (
		"name" => "moduleNameSearch",
		"sql" => " and c.moduleName like concat('%',#,'%') "
	),
	array (
		"name" => "remark",
		"sql" => " and c.remark=# "
	)
)
?>