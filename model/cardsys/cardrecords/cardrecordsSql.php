<?php

/**
 * @author Show
 * @Date 2012年1月5日 星期四 16:06:21
 * @version 1.0
 * @description:测试卡使用记录(oa_cardsys_cardrecords) sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.cardId ,c.cardNo ,c.worklogId ,c.projectId ,c.projectCode ,c.projectName ,c.ownerId ,
			c.ownerName ,c.useDate ,c.useAddress ,c.rechargerMoney ,c.useReson ,c.remark ,c.createId ,c.createName ,c.createTime ,
			c.updateId ,c.updateName ,c.updateTime,c.openMoney,c.activityId,c.activityName
		from oa_cardsys_cardrecords c where 1=1 ",
	'select_forweeklog' => "select
		c.id ,c.cardId ,c.cardNo ,c.worklogId ,c.projectId ,c.projectCode ,c.projectName ,c.ownerId ,c.ownerName ,c.useDate ,c.useAddress ,c.rechargerMoney ,c.useReson ,c.remark ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime
		from oa_cardsys_cardrecords c inner join oa_esm_worklog w on c.worklogId = w.id ",
	"select_allmoney" => "select sum(c.openMoney + c.rechargerMoney) as allMoney from oa_cardsys_cardrecords c where 1=1 ",
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "cardId",
		"sql" => " and c.cardId=# "
	),
	array (
		"name" => "cardNo",
		"sql" => " and c.cardNo=# "
	),
	array (
		"name" => "cardNoSearch",
		"sql" => " and c.cardNo like CONCAT('%',#,'%') "
	),
	array (
		"name" => "worklogId",
		"sql" => " and c.worklogId=# "
	),
	array (
		"name" => "projectId",
		"sql" => " and c.projectId=# "
	),
	array (
		"name" => "projectCode",
		"sql" => " and c.projectCode=# "
	),
	array (
		"name" => "projectName",
		"sql" => " and c.projectName=# "
	),
	array (
		"name" => "ownerId",
		"sql" => " and c.ownerId=# "
	),
	array (
		"name" => "ownerName",
		"sql" => " and c.ownerName=# "
	),
	array (
		"name" => "useDate",
		"sql" => " and c.useDate=# "
	),
	array (
		"name" => "rechargerMoney",
		"sql" => " and c.rechargerMoney=# "
	),
	array (
		"name" => "useReson",
		"sql" => " and c.useReson=# "
	),
	array (
		"name" => "createId",
		"sql" => " and c.createId=# "
	),
	array (
		"name" => "createName",
		"sql" => " and c.createName=# "
	),
	array (
		"name" => "updateId",
		"sql" => " and c.updateId=# "
	),
	array (
		"name" => "updateName",
		"sql" => " and c.updateName=# "
	),
	array (
		"name" => "weekId",
		"sql" => " and w.weekId=# "
	)
)
?>