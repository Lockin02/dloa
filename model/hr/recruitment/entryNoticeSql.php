<?php
/**
 * @author Administrator
 * @Date 2012年7月27日 星期五 13:22:05
 * @version 1.0
 * @description:入职通知 sql配置文件
 */
$sql_arr = array (
	"select_default"=>"select c.id ,c.formCode ,c.formDate ,c.invitationId ,c.invitationCode ,c.applyId ,c.applyCode ,c.interviewType ,c.sourceId ,c.sourceCode ,c.parentId ,c.parentCode ,c.resumeId ,c.resumeCode ,c.userNo ,c.userAccount ,c.userName ,c.sex ,c.phone ,c.email ,c.positionsName ,c.positionsId ,c.developPositionId ,c.developPositionName ,c.deptName ,c.deptCode ,c.deptId ,c.projectGroupId ,c.projectCode ,c.projectGroup ,c.useHireType ,c.useHireTypeName ,c.useJobName ,c.useJobId ,c.useAreaName ,c.useAreaId ,c.useTrialWage ,c.useFormalWage ,c.useDemandEqu ,c.useSign ,c.hrHireType ,c.hrHireTypeName ,c.probation ,c.contractYear ,c.hrRequire ,c.addType ,c.addTypeCode ,c.hrSourceType1 ,c.hrSourceType1Name ,c.hrSourceType2 ,c.hrSourceType2Name ,c.hrJobName ,c.hrJobId ,c.hrIsManageJob ,c.hrIsMatch ,c.entryDate ,c.state ,c.staffFileState ,c.contractState ,c.accountState ,c.ExaStatus ,c.ExaDT ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime ,c.sysCompanyName ,c.sysCompanyId ,c.applyResumeId ,c.socialPlace,c.assistManName,c.assistManId,c.entryRemark,c.personLevel,c.personLevelId ,c.compensation ,c.tutor ,c.tutorId ,c.isSave ,c.cancelReason ,c.departReason from oa_hr_recruitment_entrynotice c where 1=1 ",
	"entry_export_list"=>"select c.id ,c.formCode ,c.userAccount ,c.userName ,c.sex ,c.deptName ,c.useHireTypeName ,c.positionsName ,c.hrJobName ,c.entryDate ,c.useDemandEqu from oa_hr_recruitment_entrynotice c where 1=1",
	//入职列表
	"select_list"=>"select c.id ,c.formCode ,c.formDate ,c.invitationId ,c.invitationCode ,c.applyId ,c.applyCode ,c.interviewType ,c.sourceId ,c.sourceCode ,c.parentId ,c.parentCode ,c.resumeId ,c.resumeCode ,c.userNo ,c.userAccount ,c.userName ,c.sex ,c.phone ,c.email ,c.positionsName ,c.positionsId ,c.developPositionId ,c.developPositionName ,c.deptName ,c.deptCode ,c.deptId ,c.projectGroupId ,c.projectCode ,c.projectGroup ,c.useHireType ,c.useHireTypeName ,c.useJobName ,c.useJobId ,c.useAreaName ,c.useAreaId ,c.useTrialWage ,c.useFormalWage ,c.useDemandEqu ,c.useSign ,c.hrHireType ,c.hrHireTypeName ,c.probation ,c.contractYear ,c.hrRequire ,c.addType ,c.addTypeCode ,c.hrSourceType1 ,c.hrSourceType1Name ,c.hrSourceType2 ,c.hrSourceType2Name ,c.hrJobName ,c.hrJobId ,c.hrIsManageJob ,c.hrIsMatch ,c.entryDate ,c.state ,c.staffFileState ,c.contractState ,c.accountState ,c.ExaStatus ,c.ExaDT ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime ,c.sysCompanyName ,c.sysCompanyId ,c.applyResumeId ,c.socialPlace ,c.assistManName ,c.assistManId ,c.entryRemark ,c.personLevel ,c.personLevelId ,c.compensation ,c.tutor ,c.tutorId ,c.isSave ,c.cancelReason ,c.departReason ,d.workProvinceId ,d.workProvince ,d.workCityId ,d.workCity ,d.workPlace,d.internshipSalary
	from oa_hr_recruitment_entrynotice c
	left join oa_hr_recruitment_interview d on (c.parentId = d.id)
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
		"sql" => " and c.formDate LIKE CONCAT('%',#,'%') "
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
		"name" => "applyId",
		"sql" => " and c.applyId=# "
	),
	array(
		"name" => "applyCode",
		"sql" => " and c.applyCode LIKE CONCAT('%',#,'%') "
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
		"sql" => " and c.resumeCode LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "userNo",
		"sql" => " and c.userNo=# "
	),
	array(
		"name" => "userAccount",
		"sql" => " and c.userAccount=# "
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
		"name" => "developPositionId",
		"sql" => " and c.developPositionId=# "
	),
	array(
		"name" => "developPositionName",
		"sql" => " and c.developPositionName LIKE CONCAT('%',#,'%') "
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
		"name" => "projectGroupId",
		"sql" => " and c.projectGroupId=# "
	),
	array(
		"name" => "projectCode",
		"sql" => " and c.projectCode=# "
	),
	array(
		"name" => "projectGroup",
		"sql" => " and c.projectGroup=# "
	),
	array(
		"name" => "useHireType",
		"sql" => " and c.useHireType=# "
	),
	array(
		"name" => "useHireTypeName",
		"sql" => " and c.useHireTypeName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "useJobName",
		"sql" => " and c.useJobName=# "
	),
	array(
		"name" => "useJobId",
		"sql" => " and c.useJobId=# "
	),
	array(
		"name" => "useAreaName",
		"sql" => " and c.useAreaName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "useAreaId",
		"sql" => " and c.useAreaId=# "
	),
	array(
		"name" => "useTrialWage",
		"sql" => " and c.useTrialWage=# "
	),
	array(
		"name" => "useFormalWage",
		"sql" => " and c.useFormalWage=# "
	),
	array(
		"name" => "useDemandEqu",
		"sql" => " and c.useDemandEqu LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "useSign",
		"sql" => " and c.useSign=# "
	),
	array(
		"name" => "hrHireType",
		"sql" => " and c.hrHireType=# "
	),
	array(
		"name" => "hrHireTypeName",
		"sql" => " and c.hrHireTypeName=# "
	),
	array(
		"name" => "probation",
		"sql" => " and c.probation LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "contractYear",
		"sql" => " and c.contractYear LIKE BINARY CONCAT('%',#,'%') "
	),
	array(
		"name" => "hrRequire",
		"sql" => " and c.hrRequire=# "
	),
	array(
		"name" => "addType",
		"sql" => " and c.addType LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "addTypeCode",
		"sql" => " and c.addTypeCode=# "
	),
	array(
		"name" => "hrSourceType1",
		"sql" => " and c.hrSourceType1=# "
	),
	array(
		"name" => "hrSourceType1Name",
		"sql" => " and c.hrSourceType1Name LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "hrSourceType2",
		"sql" => " and c.hrSourceType2=# "
	),
	array(
		"name" => "hrSourceType2Name",
		"sql" => " and c.hrSourceType2Name LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "hrJobName",
		"sql" => " and c.hrJobName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "hrJobId",
		"sql" => " and c.hrJobId=# "
	),
	array(
		"name" => "hrIsManageJob",
		"sql" => " and c.hrIsManageJob=# "
	),
	array(
		"name" => "hrIsMatch",
		"sql" => " and c.hrIsMatch=# "
	),
	array(
		"name" => "entryDate",
		"sql" => " and c.entryDate LIKE BINARY CONCAT('%',#,'%') "
	),
	array(
		"name" => "state",
		"sql" => " and c.state=# "
	),
	array(
		"name" => "staffFileState",
		"sql" => " and c.staffFileState=# "
	),
	array(
		"name" => "contractState",
		"sql" => " and c.contractState=# "
	),
	array(
		"name" => "accountState",
		"sql" => " and c.accountState=# "
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
		"name" => "sysCompanyName",
		"sql" => " and c.sysCompanyName=# "
	),
	array(
		"name" => "sysCompanyId",
		"sql" => " and c.sysCompanyId=# "
	),
	array(
		"name" => "preDateHope",
		"sql" => " and c.formDate >= BINARY #"
	),
	array(
		"name" => "afterDateHope",
		"sql" => " and c.formDate <= BINARY #"
	),
	array(
		"name" => "entryDatefrom",
		"sql" => " and c.entryDate >= BINARY # "
	),
	array(
		"name" => "entryDateto",
		"sql" => " and c.entryDate <= BINARY # "
	),
	array(
		"name" => "userNameSame",
		"sql" => " and c.userName=# "
	),
	array(
		"name" => "stateArr",
		"sql" => " and c.state in(arr) "
	),
	array(
		"name" => "applyResumeId",
		"sql" => " and c.applyResumeId=# "
	),
	array(
		"name" => "isSave",
		"sql" => " and c.isSave=# "
	),
	array(
		"name" => "isSaveN",
		"sql" => " and (c.isSave is null or c.isSave !=#)"
	),
	array(
		"name" => "assistManName",
		"sql" => " and c.assistManName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "workPlace",
		"sql" => " and d.workPlace LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "socialPlace",
		"sql" => " and c.socialPlace LIKE CONCAT('%',#,'%') "
	)
)
?>