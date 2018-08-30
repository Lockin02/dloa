<?php
/**
 * @author Administrator
 * @Date 2012年7月18日 星期三 15:18:37
 * @version 1.0
 * @description:面试通知 sql配置文件
 */
$sql_arr = array (
	"select_default"=>"select c.id ,c.formCode ,c.formDate ,c.interviewType ,c.parentId ,c.parentCode ,c.applyResumeId ,c.resumeId ,c.resumeCode ,c.applicantName ,c.sex ,c.workSeniority ,c.phone ,c.email ,c.postType ,c.postTypeName ,c.useAreaName ,c.useAreaId ,c.positionsName ,c.positionsId ,c.developPositionId ,c.developPositionName ,c.positionLevel ,c.workPlace ,c.deptName ,c.deptId ,c.interviewerId ,c.interviewerName ,c.hrInterviewer ,c.hrInterviewerId ,c.interviewDate ,c.interviewPlace ,c.projectGroupId ,c.projectCode ,c.projectGroup ,c.isAddInterview ,c.state ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime ,c.sysCompanyName ,c.sysCompanyId  from oa_hr_recruitment_invitation c where 1=1 "
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
		"sql" => " and c.formDate=# "
	),
	array(
		"name" => "interviewType",
		"sql" => " and c.interviewType=# "
	),
	array(
		"name" => "parentId",
		"sql" => " and c.parentId=# "
	),
	array(
		"name" => "parentCode",
		"sql" => " and c.parentCode LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "resumeId",
		"sql" => " and c.resumeId=# "
	),
	array(
		"name" => "resumeCode",
		"sql" => " and c.resumeCode LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "applicantName",
		"sql" => " and c.applicantName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "sex",
		"sql" => " and c.sex=# "
	),
	array(
		"name" => "workSeniority",
		"sql" => " and c.workSeniority=# "
	),
	array(
		"name" => "phone",
		"sql" => " and c.phone LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "email",
		"sql" => " and c.email LIKE CONCAT('%',#,'%') "
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
		"sql" => " and c.deptName LIKE CONCAT('%',#,'%')"
	),
	array(
		"name" => "deptId",
		"sql" => " and c.deptId=# "
	),
	array(
		"name" => "interviewDateSea",
		"sql" => " and c.deptId LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "interviewDate",
		"sql" => " and CURDATE() <= DATE(interviewDate) AND DATE(interviewDate) <= DATE_ADD(CURDATE(), INTERVAL 7 DAY)"
	),
	array(
		"name" => "interviewPlace",
		"sql" => " and c.interviewPlace LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "projectGroup",
		"sql" => " and c.projectGroup=# "
	),
	array(
		"name" => "state",
		"sql" => " and c.state=# "
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
		"name" => "sysCompanyName",
		"sql" => " and c.sysCompanyName=# "
	),
	array(
		"name" => "sysCompanyId",
		"sql" => " and c.sysCompanyId=# "
	),
	array (
		"name" => "linkid",
		"sql" => " and c.id in (select parentId from oa_hr_invitation_interviewer where interviewerId=#) "
	),
	array(
		"name" => "stateArr",
		"sql" => " and c.state in(arr)"
	),
	array(
		"name" => "interviewerName",
		"sql" => " and c.interviewerName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "hrInterviewer",
		"sql" => " and c.hrInterviewer LIKE CONCAT('%',#,'%') "
	)
)
?>