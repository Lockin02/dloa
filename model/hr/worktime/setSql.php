<?php
/**
 * @author Michael
 * @Date 2014年4月24日 9:50:35
 * @version 1.0
 * @description:法定节假日 sql配置文件
 */
$sql_arr = array (
	"select_default"=>"select c.id ,c.formBelong ,c.formBelongName ,c.businessBelong ,c.businessBelongName ,c.year ,c.remark ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime  from oa_hr_worktime_set c where 1=1 "
);

$condition_arr = array (
	array(
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array(
		"name" => "formBelong",
		"sql" => " and c.formBelong=# "
	),
	array(
		"name" => "formBelongName",
		"sql" => " and c.formBelongName=# "
	),
	array(
		"name" => "businessBelong",
		"sql" => " and c.businessBelong=# "
	),
	array(
		"name" => "businessBelongName",
		"sql" => " and c.businessBelongName=# "
	),
	array(
		"name" => "year",
		"sql" => " and c.year=# "
	),
	array(
		"name" => "remark",
		"sql" => " and c.remark=# "
	),
	array(
		"name" => "createId",
		"sql" => " and c.createId=# "
	),
	array(
		"name" => "createName",
		"sql" => " and c.createName=# "
	),
	array(
		"name" => "createTime",
		"sql" => " and c.createTime=# "
	),
	array(
		"name" => "updateId",
		"sql" => " and c.updateId=# "
	),
	array(
		"name" => "updateName",
		"sql" => " and c.updateName=# "
	),
	array(
		"name" => "updateTime",
		"sql" => " and c.updateTime=# "
	)
)
?>