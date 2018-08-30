<?php
/**
 * @author Administrator
 * @Date 2012年7月9日 星期一 14:15:37
 * @version 1.0
 * @description:职位说明书 sql配置文件
 */
$sql_arr = array (
	"select_default"=>"select c.id ,c.formCode ,c.positionName ,c.positionCode ,c.positionEn ,c.positionType ,c.positionTypeName ,c.companyType ,c.companyName ,c.deptCode ,c.deptName ,c.deptId ,c.rewardGrade ,c.superiorPosition ,c.superiorPositionId ,c.suborPosition ,c.suborPositionId ,c.parallelPosition ,c.parallelPositionId ,c.promotionPosition ,c.promotionPositionId ,c.rotationPosition ,c.rotationPositionId ,c.downPosition ,c.downPositionId ,c.positionRemark ,c.professionalKnow ,c.companyKnow ,c.workProcess ,c.experience ,c.education ,c.profession ,c.age ,c.state ,c.ExaStatus ,c.ExaDT ,c.approvalName ,c.approvalId ,c.approvalTime ,c.trainNeed ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime  from oa_hr_position_description c where 1=1 "
);

$condition_arr = array (
	array(
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array(
		"name" => "formCode",
		"sql" => " and c.formCode=# "
	),
	array(
		"name" => "positionName",
		"sql" => " and c.positionName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "positionCode",
		"sql" => " and c.positionCode=# "
	),
	array(
		"name" => "positionEn",
		"sql" => " and c.positionEn=# "
	),
	array(
		"name" => "positionType",
		"sql" => " and c.positionType=# "
	),
	array(
		"name" => "positionTypeName",
		"sql" => " and c.positionTypeName=# "
	),
	array(
		"name" => "companyType",
		"sql" => " and c.companyType=# "
	),
	array(
		"name" => "companyName",
		"sql" => " and c.companyName=# "
	),
	array(
		"name" => "deptCode",
		"sql" => " and c.deptCode=# "
	),
	array(
		"name" => "deptName",
		"sql" => " and c.deptName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "deptId",
		"sql" => " and c.deptId=# "
	),
	array(
		"name" => "rewardGrade",
		"sql" => " and c.rewardGrade LIKE CONCAT('%',#,'%')"
	),
	array(
		"name" => "superiorPosition",
		"sql" => " and c.superiorPosition=# "
	),
	array(
		"name" => "superiorPositionId",
		"sql" => " and c.superiorPositionId=# "
	),
	array(
		"name" => "suborPosition",
		"sql" => " and c.suborPosition=# "
	),
	array(
		"name" => "suborPositionId",
		"sql" => " and c.suborPositionId=# "
	),
	array(
		"name" => "parallelPosition",
		"sql" => " and c.parallelPosition=# "
	),
	array(
		"name" => "parallelPositionId",
		"sql" => " and c.parallelPositionId=# "
	),
	array(
		"name" => "promotionPosition",
		"sql" => " and c.promotionPosition=# "
	),
	array(
		"name" => "promotionPositionId",
		"sql" => " and c.promotionPositionId=# "
	),
	array(
		"name" => "rotationPosition",
		"sql" => " and c.rotationPosition=# "
	),
	array(
		"name" => "rotationPositionId",
		"sql" => " and c.rotationPositionId=# "
	),
	array(
		"name" => "downPosition",
		"sql" => " and c.downPosition=# "
	),
	array(
		"name" => "downPositionId",
		"sql" => " and c.downPositionId=# "
	),
	array(
		"name" => "positionRemark",
		"sql" => " and c.positionRemark=# "
	),
	array(
		"name" => "professionalKnow",
		"sql" => " and c.professionalKnow=# "
	),
	array(
		"name" => "companyKnow",
		"sql" => " and c.companyKnow=# "
	),
	array(
		"name" => "workProcess",
		"sql" => " and c.workProcess=# "
	),
	array(
		"name" => "experience",
		"sql" => " and c.experience=# "
	),
	array(
		"name" => "education",
		"sql" => " and c.education=# "
	),
	array(
		"name" => "profession",
		"sql" => " and c.profession=# "
	),
	array(
		"name" => "age",
		"sql" => " and c.age=# "
	),
	array(
		"name" => "state",
		"sql" => " and c.state=# "
	),
	array(
		"name" => "ExaStatus",
		"sql" => " and c.ExaStatus=# "
	),
	array(
		"name" => "ExaDT",
		"sql" => " and c.ExaDT=# "
	),
	array(
		"name" => "approvalName",
		"sql" => " and c.approvalName=# "
	),
	array(
		"name" => "approvalId",
		"sql" => " and c.approvalId=# "
	),
	array(
		"name" => "approvalTime",
		"sql" => " and c.approvalTime=# "
	),
	array(
		"name" => "trainNeed",
		"sql" => " and c.trainNeed=# "
	),
	array(
		"name" => "createName",
		"sql" => " and c.createName=# "
	),
	array(
		"name" => "createId",
		"sql" => " and c.createId=# "
	),
	array(
		"name" => "createTime",
		"sql" => " and c.createTime=# "
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
	),
	array(
		"name" => "positionNameEq",
		"sql" => " and c.positionName=# "
	),
	array(
		"name" => "deptIdEq",
		"sql" => " and c.deptId=# "
	)
)
?>