<?php
/**
 * @author Michael
 * @Date 2014年7月25日 15:13:03
 * @version 1.0
 * @description:基础信息-工序 sql配置文件
 */
$sql_arr = array (
	"select_default"=>"select c.id ,c.formbelong ,c.formBelongName ,c.businessBelong ,c.businessBelongName ,c.templateName ,c.isEnable ,c.remark ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime  from oa_manufacture_process c where 1=1 "
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
		"name" => "templateName",
		"sql" => " and c.templateName LIKE CONCAT('%' ,# ,'%') "
	),
	array(
		"name" => "isEnable",
		"sql" => " and c.isEnable=# "
	),
	array(
		"name" => "remark",
		"sql" => " and c.remark LIKE CONCAT('%' ,# ,'%') "
	),
	array(
		"name" => "createName",
		"sql" => " and c.createName LIKE CONCAT('%' ,# ,'%') "
	),
	array(
		"name" => "createId",
		"sql" => " and c.createId=# "
	),
	array(
		"name" => "createTime",
		"sql" => " and c.createTime LIKE CONCAT('%' ,# ,'%') "
	),
	array(
		"name" => "updateName",
		"sql" => " and c.updateName=# "
	),
	array(
		"name" => "updateId",
		"sql" => " and c.updateId=# "
	),
	array(
		"name" => "updateTime",
		"sql" => " and c.updateTime=# "
	)
)
?>