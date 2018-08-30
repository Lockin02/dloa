<?php
/**
 * @author Administrator
 * @Date 2012年7月20日 星期五 11:33:19
 * @version 1.0
 * @description:内部推荐奖金 sql配置文件
 */
$sql_arr = array (
	"select_default"=>"select c.id ,c.formCode ,c.formDate ,c.formManId ,c.formManName ,c.isRecommendName ,c.resumeId ,c.resumeCode ,c.positionId ,c.positionName ,c.developPositionId ,c.developPositionName ,c.job ,c.jobName ,c.entryDate ,c.becomeDate ,c.beBecomDate ,c.recommendName ,c.recommendId ,c.recommendReason ,c.state ,c.isBonus ,c.bonus ,c.bonusProprotion ,c.firstGrantDate ,c.firstGrantBonus ,c.secondGrantDate ,c.secondGrantBonus ,c.remark ,c.ExaStatus ,c.ExaDT ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime,c.deptName,c.deptId  from oa_hr_recommend_bonus c where 1=1 "
);

$condition_arr = array (
	array(
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array(
		"name" => "formCode",
		"sql" => " and c.formCode LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "formDate",
		"sql" => " and c.formDate LIKE BINARY CONCAT('%',#,'%') "
	),
	array(
		"name" => "formManId",
		"sql" => " and c.formManId=# "
	),
	array(
		"name" => "formManName",
		"sql" => " and c.formManName=# "
	),
	array(
		"name" => "isRecommendName",
		"sql" => " and c.isRecommendName LIKE CONCAT('%',#,'%')  "
	),
	array(
		"name" => "resumeId",
		"sql" => " and c.resumeId=# "
	),
	array(
		"name" => "resumeCode",
		"sql" => " and c.resumeCode=# "
	),
	array(
		"name" => "positionId",
		"sql" => " and c.positionId=# "
	),
	array(
		"name" => "positionName",
		"sql" => " and c.positionName LIKE CONCAT('%',#,'%')  "
	),
	array(
		"name" => "developPositionId",
		"sql" => " and c.developPositionId=# "
	),
	array(
		"name" => "developPositionName",
		"sql" => " and c.developPositionName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "job",
		"sql" => " and c.job=# "
	),
	array(
		"name" => "jobName",
		"sql" => " and c.jobName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "entryDate",
		"sql" => " and c.entryDate LIKE BINARY CONCAT('%',#,'%') "
	),
	array(
		"name" => "becomeDate",
		"sql" => " and c.becomeDate LIKE BINARY CONCAT('%',#,'%') "
	),
	array(
		"name" => "beBecomDate",
		"sql" => " and c.beBecomDate LIKE BINARY CONCAT('%',#,'%') "
	),
	array(
		"name" => "recommendName",
		"sql" => " and c.recommendName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "recommendId",
		"sql" => " and c.recommendId=# "
	),
	array(
		"name" => "recommendReason",
		"sql" => " and c.recommendReason=# "
	),
	array(
		"name" => "state",
		"sql" => " and c.state=# "
	),
	array(
		"name" => "isBonus",
		"sql" => " and c.isBonus=# "
	),
	array(
		"name" => "bonus",
		"sql" => " and c.bonus=# "
	),
	array(
		"name" => "bonusProprotion",
		"sql" => " and c.bonusProprotion=# "
	),
	array(
		"name" => "firstGrantDate",
		"sql" => " and c.firstGrantDate=# "
	),
	array(
		"name" => "firstGrantBonus",
		"sql" => " and c.firstGrantBonus=# "
	),
	array(
		"name" => "secondGrantDate",
		"sql" => " and c.secondGrantDate=# "
	),
	array(
		"name" => "secondGrantBonus",
		"sql" => " and c.secondGrantBonus=# "
	),
	array(
		"name" => "remark",
		"sql" => " and c.remark LIKE CONCAT('%',#,'%') "
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
		"name" => "statedNo",
		"sql" => " and c.state != # "
	)
)
?>