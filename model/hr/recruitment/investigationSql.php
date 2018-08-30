<?php
/**
 * @author Administrator
 * @Date 2012年8月18日 15:23:52
 * @version 1.0
 * @description:背景调查记录表 sql配置文件
 */
$sql_arr = array (
	"select_default"=>"select c.id ,c.formCode ,c.formDate ,c.invitationId ,c.invitationCode ,c.interviewType ,c.sourceId ,c.sourceCode ,c.applyResumeId ,c.parentId ,c.parentCode ,c.resumeId ,c.resumeCode ,c.userName ,c.sex ,c.positionsName ,c.positionsId ,c.deptName ,c.deptCode ,c.deptId ,c.consultationName ,c.consultationCompanyName ,c.consultationPostiton ,c.consultationTel ,c.consultationEmail ,c.workBeginDate ,c.workEndDate ,c.userCompany ,c.userPosition ,c.relationshipName ,c.relationshipCode ,c.workDuty ,c.evaluation ,c.relationship ,c.consultationAdvantage ,c.consultationImprove ,c.leaveReason ,c.isCooperation ,c.referenceProject ,c.overallEvaluation ,c.InvestigationMan ,c.InvestigationManId ,c.InvestigationDate ,c.state ,c.ExaStatus ,c.ExaDT ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime  from oa_hr_recruitment_investigation c where 1=1 "
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
		"name" => "formDate",
		"sql" => " and c.formDate LIKE BINARY CONCAT('%',#,'%') "
	),
	array(
		"name" => "invitationId",
		"sql" => " and c.invitationId=# "
	),
	array(
		"name" => "invitationCode",
		"sql" => " and c.invitationCode=# "
	),
	array(
		"name" => "interviewType",
		"sql" => " and c.interviewType=# "
	),
	array(
		"name" => "sourceId",
		"sql" => " and c.sourceId=# "
	),
	array(
		"name" => "sourceCode",
		"sql" => " and c.sourceCode=# "
	),
	array(
		"name" => "applyResumeId",
		"sql" => " and c.applyResumeId=# "
	),
	array(
		"name" => "parentId",
		"sql" => " and c.parentId=# "
	),
	array(
		"name" => "parentCode",
		"sql" => " and c.parentCode=# "
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
		"name" => "userName",
		"sql" => " and c.userName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "sex",
		"sql" => " and c.sex=# "
	),
	array(
		"name" => "positionsName",
		"sql" => " and c.positionsName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "positionsId",
		"sql" => " and c.positionsId=# "
	),
	array(
		"name" => "deptName",
		"sql" => " and c.deptName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "deptCode",
		"sql" => " and c.deptCode=# "
	),
	array(
		"name" => "deptId",
		"sql" => " and c.deptId=# "
	),
	array(
		"name" => "consultationName",
		"sql" => " and c.consultationName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "consultationCompanyName",
		"sql" => " and c.consultationCompanyName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "consultationPostiton",
		"sql" => " and c.consultationPostiton LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "consultationTel",
		"sql" => " and c.consultationTel LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "consultationEmail",
		"sql" => " and c.consultationEmail LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "workBeginDate",
		"sql" => " and c.workBeginDate LIKE BINARY CONCAT('%',#,'%') "
	),
	array(
		"name" => "workEndDate",
		"sql" => " and c.workEndDate LIKE BINARY CONCAT('%',#,'%') "
	),
	array(
		"name" => "userCompany",
		"sql" => " and c.userCompany LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "userPosition",
		"sql" => " and c.userPosition LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "relationshipName",
		"sql" => " and c.relationshipName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "relationshipCode",
		"sql" => " and c.relationshipCode=# "
	),
	array(
		"name" => "workDuty",
		"sql" => " and c.workDuty=# "
	),
	array(
		"name" => "evaluation",
		"sql" => " and c.evaluation=# "
	),
	array(
		"name" => "relationship",
		"sql" => " and c.relationship=# "
	),
	array(
		"name" => "consultationAdvantage",
		"sql" => " and c.consultationAdvantage=# "
	),
	array(
		"name" => "consultationImprove",
		"sql" => " and c.consultationImprove=# "
	),
	array(
		"name" => "leaveReason",
		"sql" => " and c.leaveReason=# "
	),
	array(
		"name" => "isCooperation",
		"sql" => " and c.isCooperation=# "
	),
	array(
		"name" => "referenceProject",
		"sql" => " and c.referenceProject=# "
	),
	array(
		"name" => "overallEvaluation",
		"sql" => " and c.overallEvaluation=# "
	),
	array(
		"name" => "InvestigationMan",
		"sql" => " and c.InvestigationMan LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "InvestigationManId",
		"sql" => " and c.InvestigationManId=# "
	),
	array(
		"name" => "InvestigationDate",
		"sql" => " and c.InvestigationDate LIKE BINARY CONCAT('%',#,'%') "
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
	),
	array(
		"name" => "formCode_d",
		"sql" => " and c.formCode LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "state_d",
		"sql" => " and c.state LIKE CONCAT('%',#,'%') "
	)
)
?>