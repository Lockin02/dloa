<?php

/**
 * @author Show
 * @Date 2012年8月20日 星期一 20:13:09
 * @version 1.0
 * @description:行为要项配置表 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.moduleId ,c.moduleName,c.detailName ,c.remark  from oa_hr_baseinfo_behamoduledetail c where 1=1 "
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "moduleId",
		"sql" => " and c.moduleId=# "
	),
	array (
		"name" => "moduleName",
		"sql" => " and c.moduleName = #"
	),
	array (
		"name" => "moduleNameSearch",
		"sql" => " and c.moduleName like concat('%',#,'%')"
	),
	array (
		"name" => "detailName",
		"sql" => " and c.detailName=# "
	),
	array (
		"name" => "detailNameSearch",
		"sql" => " and c.detailName like concat('%',#,'%')"
	),
	array (
		"name" => "remark",
		"sql" => " and c.remark=# "
	)
)
?>