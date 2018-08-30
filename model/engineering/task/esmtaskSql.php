<?php
/*
 * Created on 2010-12-2
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

$sql_arr = array(
			"esmtaskInfo" => "select c.id,c.name,c.projectId,c.projectCode,c.projectName,c.priority,c.status,c.effortRate,c.warpRate,c.chargeId,c.chargeName," .
			"c.publishId,c.publishName,c.taskType,c.planBeginDate,c.planEndDate,c.actBeginDate,c.actEndDate,c.planDuration,c.realDuration," .
			"c.appraiseWorkload,c.putWorkload,c.actWorkload,c.remark,c.endDayNum,c.planId,c.planCode,c.planName," .
			"c.belongNode,c.belongNodeId,c.inspectInfo,c.isStone,c.markStoneName,c.stoneId,c.createId,c.createName,c.createTime,c.updateId,c.updateName,c.updateTime".
			" from oa_esm_task c where 1=1 "
);
$condition_arr = array(
	array(
		"name" => "status",
		"sql" => " and c.status=# "
	),array(
		"name" => "status",
		"sql" => " and c.status like CONCAT('%',#,'%') "
	),array(
		"name" => "name",
		"sql" => " and c.name like CONCAT('%',#,'%') "
	),array(
		"name" => "status2",
		"sql" => " or c.status=#"
	),array(
		"name" => "status1",
		"sql" => " or c.status=#"
	),array(
		"name" => "chargeId",
		"sql" => " and c.chargeId=#"
	),
);
?>


