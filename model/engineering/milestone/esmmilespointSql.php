<?php
$sql_arr = array (
	//Ĭ��sql���
	"select_default" => "select c.id,c.pointName,c.planBeginDate,c.planEndDate,c.realBeginDate,c.realEndDate,c.projectId,c.projectCode,c.projectName,c.milestoneId,c.milestoneCode,c.createId,c.createName,c.createTime,c.updateId,c.updateName,c.updateTime,c.isUsing,c.versionNum,c.code,c.status,c.parentId,c.frontCode " .
			"from oa_rd_milestone_point c from oa_rd_milestone_point c where 1=1"
	,"select_readCenter" => "select c.id,c.pointName,c.planBeginDate,c.planEndDate,c.realBeginDate,c.realEndDate,c.projectId,c.projectCode,c.projectName,c.milestoneId,c.milestoneCode,c.createId,c.createName,c.createTime,c.updateId,c.updateName,c.updateTime,c.isUsing,c.versionNum,c.code,c.status,c.parentId,c.frontCode,c.effortRate,c.warpRate " .
			"from oa_rd_milestone_point c where 1=1"
	,"select_readSelect" => "select c.id,c.pointName,c.frontCode,c.code,c.status,c.realBeginDate,c.realEndDate,c.effortRate,c.warpRate from oa_rd_milestone_point c where 1=1"
);

$condition_arr = array (
	//ͨ��Id��ѯ
	array(
		"name" => "id",
		"sql" => "and c.id=#"
	)

	,array(
		"name" => "milestonId", //��̱�Id
		"sql" => "and c.milestoneId=#"
	)
	,array(
		"name" => "pjId", //��ĿId
		"sql" => "and c.projectId=# "
	)
	,array(
		"name" => "statusArr", //״̬����
		"sql" => "and c.status in(arr) "
	)
	,array(
		"name" => "frontCode", //�����
		"sql" => "and c.frontCode=# "
	)
);
?>

