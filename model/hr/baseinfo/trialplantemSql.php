<?php

/**
 * @author Show
 * @Date 2012��8��30�� ������ 16:55:56
 * @version 1.0
 * @description:Ա��������ѵ�ƻ�ģ�� sql�����ļ�
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.planName ,c.baseScore,c.description ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime ,c.scoreAll ,c.weightsAll  from oa_hr_baseinfo_trialplantem c where 1=1 "
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "planName",
		"sql" => " and c.planName=# "
	),
	array (
		"name" => "description",
		"sql" => " and c.description=# "
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
	array (
		"name" => "scoreAll",
		"sql" => " and c.scoreAll=# "
	),
	array (
		"name" => "weightsAll",
		"sql" => " and c.weightsAll=# "
	)
)
?>