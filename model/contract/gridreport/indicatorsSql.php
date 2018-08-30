<?php
/**
 * @author yxin1
 * @Date 2014年12月2日 15:21:28
 * @version 1.0
 * @description:指标值表 sql配置文件
 */
$sql_arr = array (
	"select_default"=>"select c.id ,c.objCode ,c.objName ,c.indicatorsCode ,c.indicatorsName ,c.setCode ,c.setName ,c.monthJan ,c.monthFeb ,c.monthMar ,c.monthApr ,c.monthMay ,c.monthJun ,c.monthJul ,c.monthAug ,c.monthSep ,c.monthOct ,c.monthNov ,c.monthDec  from oa_system_indicators c where 1=1 "
);

$condition_arr = array (
	array(
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array(
		"name" => "objCode",
		"sql" => " and c.objCode=# "
	),
	array(
		"name" => "objName",
		"sql" => " and c.objName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "indicatorsCode",
		"sql" => " and c.indicatorsCode=# "
	),
	array(
		"name" => "indicatorsName",
		"sql" => " and c.indicatorsName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "setCode",
		"sql" => " and c.setCode=# "
	),
	array(
		"name" => "setName",
		"sql" => " and c.setName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "monthJan",
		"sql" => " and c.monthJan=# "
	),
	array(
		"name" => "monthFeb",
		"sql" => " and c.monthFeb=# "
	),
	array(
		"name" => "monthMar",
		"sql" => " and c.monthMar=# "
	),
	array(
		"name" => "monthApr",
		"sql" => " and c.monthApr=# "
	),
	array(
		"name" => "monthMay",
		"sql" => " and c.monthMay=# "
	),
	array(
		"name" => "monthJun",
		"sql" => " and c.monthJun=# "
	),
	array(
		"name" => "monthJul",
		"sql" => " and c.monthJul=# "
	),
	array(
		"name" => "monthAug",
		"sql" => " and c.monthAug=# "
	),
	array(
		"name" => "monthSep",
		"sql" => " and c.monthSep=# "
	),
	array(
		"name" => "monthOct",
		"sql" => " and c.monthOct=# "
	),
	array(
		"name" => "monthNov",
		"sql" => " and c.monthNov=# "
	),
	array(
		"name" => "monthDec",
		"sql" => " and c.monthDec=# "
	)
)
?>