<?php
/**
 * @author Administrator
 * @Date 2013��6��13�� ������ 19:54:04
 * @version 1.0
 * @description:��Ա�����ȼ� sql�����ļ�
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.personLevel ,c.remark ,c.createName ,c.createId ,c.createTime ,c.updateName ,
			c.updateId ,c.updateTime,c.esmLevel,c.esmLevelId
		from oa_hr_level c where 1=1 "
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "personLevel",
		"sql" => " and c.personLevel like CONCAT('%',#,'%')  "
	),
	array (
		"name" => "remark",
		"sql" => " and c.remark like CONCAT('%',#,'%')  "
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
	)
)
?>