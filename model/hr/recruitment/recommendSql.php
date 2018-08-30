<?php
/**
 * @author Administrator
 * @Date 2012年7月11日 星期三 16:13:46
 * @version 1.0
 * @description:内部推荐 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.formCode ,c.formDate ,c.isRecommendName ,c.positionId ,c.positionName ,c.source ,c.recommendName ,c.recommendId ,c.recommendReason ,c.recruitManId ,c.recruitManName ,c.assistManId ,c.assistManName ,c.assignedManId ,c.assignedManName ,c.assignedDate ,c.state ,c.closeRemark ,c.isBonus ,c.bonus ,c.bonusProprotion ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime ,e.hrJobName ,p.becomeDate ,p.realBecomeDate ,p.quitDate
	from oa_hr_recruitment_recommend c
	left join oa_hr_recruitment_entrynotice e on (c.id=e.recommendId and c.state in(2,5,8,9) and e.state<>0)
	left join oa_hr_personnel p on (e.userAccount=p.userAccount)
	where 1=1 ",

	//关联面试评估
	"select_interview" => "select c.id ,c.formCode ,c.formDate ,c.isRecommendName ,c.positionId ,c.positionName ,c.source ,c.recommendName ,c.recommendId ,c.recommendReason ,c.recruitManId ,c.recruitManName ,c.assistManId ,c.assistManName ,c.assignedManId ,c.assignedManName ,c.assignedDate ,c.state ,c.closeRemark ,c.isBonus ,c.bonus ,c.bonusProprotion ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime
	from oa_hr_recruitment_recommend c
	left join oa_hr_recruitment_interview i on c.id=i.recommendId
	where 1=1 "
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
		"name" => "formDateSta",
		"sql" => " and c.formDate >= BINARY # "
	),
	array(
		"name" => "formDateEnd",
		"sql" => " and c.formDate <= BINARY # "
	),
	array(
		"name" => "isRecommendName",
		"sql" => " and c.isRecommendName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "positionId",
		"sql" => " and c.positionId=# "
	),
	array(
		"name" => "positionName",
		"sql" => " and c.positionName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "source",
		"sql" => " and c.source=# "
	),
	array(
		"name" => "recommendName",
		"sql" => " and c.recommendName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "recommendNameArr",
		"sql" => " and c.recommendName in(arr) "
	),
	array(
		"name" => "recommendId",
		"sql" => " and c.recommendId=# "
	),
	array(
		"name" => "recommendReason",
		"sql" => " and c.recommendReason LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "recruitManId",
		"sql" => " and c.recruitManId=# "
	),
	array(
		"name" => "recruitManName",
		"sql" => " and c.recruitManName LIKE CONCAT('%',#,'%')  "
	),
	array(
		"name" => "recruitManNameArr",
		"sql" => " and c.recruitManName in(arr) "
	),
	array(
		"name" => "assistManId",
		"sql" => " and c.assistManId=# "
	),
	array(
		"name" => "assistManName",
		"sql" => " and c.assistManName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "assignedManId",
		"sql" => " and c.assignedManId=# "
	),
	array(
		"name" => "assignedManName",
		"sql" => " and c.assignedManName=# "
	),
	array(
		"name" => "assignedDate",
		"sql" => " and c.assignedDate=# "
	),
	array(
		"name" => "state",
		"sql" => " and c.state=#"
	),
	array(
		"name" => "stateArr",
		"sql" => " and c.state in(arr)"
	),
	array(
		"name" => "closeRemark",
		"sql" => " and c.closeRemark LIKE CONCAT('%',#,'%') "
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
		"name" => "myjoinId",
		"sql" => " and c.id in (select parentId from oa_hr_recommend_menber where assesManId=#) "
	),
	array(
		"name" => "noInterviewId",
		"sql" => " and ((i.id is null or i.id='') or (i.useInterviewResult=0 AND i.ExaStatus='完成')) "
	)
)
?>