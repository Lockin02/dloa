<?php

/**
 * @author Show
 * @Date 2012年6月1日 星期五 14:51:13
 * @version 1.0
 * @description:招聘管理-面试评估表 sql配置文件
 */
$sql_arr = array (
	"select_default" => "SELECT  c.id ,c.formCode ,c.invitationId ,c.invitationCode ,c.applyId ,c.applyCode ,c.interviewType ,c.parentId ,c.parentCode ,c.resumeId ,c.resumeCode ,c.userNo ,c.userAccount ,c.userName ,c.sexy ,c.phone ,c.email ,c.positionsName ,c.positionsId ,c.developPositionId ,c.developPositionName ,c.deptName ,c.deptCode ,c.deptId ,c.projectGroupId ,c.projectCode ,c.projectGroup ,c.useWriteEva ,c.useInterviewEva ,c.useInterviewResult ,c.useInterviewer ,c.useInterviewerId ,c.useInterviewDate ,c.useHireType ,c.useHireTypeName ,c.useJobName ,c.useJobId ,c.useAreaName ,c.useAreaId ,c.useTrialWage ,c.useFormalWage ,c.useDemandEqu ,c.isCompanyStandard ,c.computerConfiguration ,c.useSign ,c.useManager ,c.useManagerId ,c.useSignDate ,c.hrInterviewResult ,c.hrInterviewer ,c.hrInterviewerId ,c.hrInterviewDate ,c.hrHireType ,c.hrHireTypeName ,c.probation ,c.contractYear ,c.addType ,c.addTypeCode ,c.hrRequire ,c.hrSourceType1 ,c.hrSourceType1Name ,c.hrSourceType2 ,c.hrSourceType2Name ,c.hrJobName ,c.hrJobId ,c.hrIsManageJob ,c.hrIsMatch ,c.entryDate ,c.hrChargerId ,c.hrCharger ,c.hrManager ,c.hrManagerId ,c.manager ,c.managerId ,c.deputyManager ,c.deputyManagerId ,c.deptState ,c.hrState ,c.state ,c.ExaStatus ,c.ExaDT ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime ,c.sysCompanyName ,c.sysCompanyId ,c.applyResumeId ,c.formDate ,c.isLocal ,c.workProvinceId ,c.workProvince ,c.workCityId ,c.workCity ,c.compensation ,c.postType ,c.postTypeName ,c.changeTip ,c.eatCarSubsidy ,c.allInternship ,c.controlPost ,c.controlPostCode ,c.computerIntern ,c.mealCarSubsidy ,c.mealCarSubsidyFormal ,
		IF(v.deptIdO<>'',v.deptIdO,c.deptId) AS parentDeptId ,IF(v.deptNameO<>'',v.deptNameO,c.deptName) AS pdeptname ,
		e.state as entryState
		from oa_hr_recruitment_interview c
		left join department_view v on c.deptId = v.deptId
		left join (select parentId ,state from oa_hr_recruitment_entrynotice where parentId > 0 group by parentId)e on e.parentId = c.id
		where 1=1 ",
	'select_excelOut'=>"SELECT  c.userName ,e.userAccount ,e.userNo ,c.sexy ,c.positionsName ,c.deptName ,i.useWriteEva1 ,i.interviewEva1 ,if(c.useInterviewResult=1,'立即录用','储备人才') as useInterviewResult ,c.projectGroup ,c.useHireTypeName ,
		case e.state
			when '1' then '待入职'
			when '2' then '已入职'
			when '3' then '放弃入职'
			else ''
		end as entryState ,
		c.sysCompanyName ,c.useAreaName ,c.personLevel ,c.useTrialWage ,c.useFormalWage ,c.useDemandEqu ,IF(isCompanyStandard=1,IFNULL(computerConfiguration,'是'),'否') as isCompanyStandard ,c.useSign ,c.isLocal ,c.levelSubsidy ,c.levelSubsidyFormal ,c.areaSubsidy ,c.areaSubsidyFormal ,c.workBonus ,c.workBonusFormal ,c.phoneSubsidy ,c.phoneSubsidyFormal ,c.computerSubsidy ,c.computerSubsidyFormal ,c.tripSubsidy ,c.tripSubsidyFormal ,c.bonusLimit ,c.bonusLimitFormal ,c.manageSubsidy ,c.manageSubsidyFormal ,c.accommodSubsidy ,c.accommodSubsidyFormal ,c.otherSubsidy ,c.otherSubsidyFormal ,c.mealCarSubsidy ,c.mealCarSubsidyFormal ,c.allTrialWage ,c.allFormalWage ,c.eatCarSubsidy ,c.computerIntern ,if(c.useHireType<>'HRLYXX-03' ,'' ,CONCAT(c.internshipSalaryType ,':' ,CONVERT(c.internshipSalary ,char))) as internship ,c.allInternship ,c.useManager ,c.useSignDate ,j.interviewEva2 as hrInterviewList ,c.hrInterviewResult ,IF(c.useHireType<>'HRLYXX-03' ,CONCAT('试用期：' ,c.probation ,'个月，合同期限：' ,c.contractYear ,'年'),'') as probations ,IF(c.useHireType<>'HRLYXX-03' ,c.socialPlace ,'无') as socialPlace ,c.hrSourceType1Name ,c.hrSourceType2Name ,c.hrJobName ,c.hrIsManageJob ,IF(c.useHireType<>'HRLYXX-03' ,c.hrIsMatch ,'') as hrIsMatch ,IF(c.entryDate='0000-00-00' ,'' ,c.entryDate) as entryDate ,e.entryDate as actualEntryDate,c.manager ,c.SignDate ,e.cancelReason ,e.departReason
		FROM oa_hr_recruitment_interview c
		LEFT JOIN (select parentId ,state ,userNo ,userAccount ,entryDate ,cancelReason ,departReason from oa_hr_recruitment_entrynotice where parentId > 0 group by parentId)e on e.parentId = c.id
		LEFT JOIN (select GROUP_CONCAT(useWriteEva) as useWriteEva1 ,GROUP_CONCAT(interviewEva) as interviewEva1 ,interviewId from oa_hr_interview_comment where interviewerType = 1 and interviewId > 0 group by interviewId)i on c.id = i.interviewId
		LEFT JOIN (select GROUP_CONCAT(interviewEva) as interviewEva2 ,interviewId from oa_hr_interview_comment where interviewerType = 2 and interviewId > 0 group by interviewId)j on c.id = j.interviewId
		where 1=1 ",
	"select_list" => "SELECT c.id ,c.formCode ,c.invitationId ,c.invitationCode ,c.applyId ,c.applyCode ,c.interviewType ,c.parentId ,c.parentCode ,c.resumeId ,c.resumeCode ,c.userNo ,c.userAccount ,c.userName ,c.sexy ,c.phone ,c.email ,c.positionsName ,c.positionsId ,c.developPositionId ,c.developPositionName ,c.deptName ,c.deptCode ,c.deptId ,c.projectGroupId ,c.projectCode ,c.projectGroup ,c.useWriteEva ,c.useInterviewEva ,c.useInterviewResult ,c.useInterviewer ,c.useInterviewerId ,c.useInterviewDate ,c.useHireType ,c.useHireTypeName ,c.useJobName ,c.useJobId ,c.useAreaName ,c.useAreaId ,c.useTrialWage ,c.useFormalWage ,c.useDemandEqu ,c.isCompanyStandard ,c.computerConfiguration ,c.useSign ,c.useManager ,c.useManagerId ,c.useSignDate ,c.hrInterviewResult ,c.hrInterviewer ,c.hrInterviewerId ,c.hrInterviewDate ,c.hrHireType ,c.hrHireTypeName ,c.probation ,c.contractYear ,c.addType ,c.addTypeCode ,c.hrRequire ,c.hrSourceType1 ,c.hrSourceType1Name ,c.hrSourceType2 ,c.hrSourceType2Name ,c.hrJobName ,c.hrJobId ,c.hrIsManageJob ,c.hrIsMatch ,c.entryDate ,c.hrChargerId ,c.hrCharger ,c.hrManager ,c.hrManagerId ,c.manager ,c.managerId ,c.deputyManager ,c.deputyManagerId ,c.deptState ,c.hrState ,c.state ,c.ExaStatus ,c.ExaDT ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime ,c.sysCompanyName ,c.sysCompanyId ,c.applyResumeId ,c.formDate ,c.isLocal ,c.workProvinceId ,c.workProvince ,c.workCityId ,c.workCity ,c.compensation ,c.postType ,c.postTypeName ,c.changeTip ,c.eatCarSubsidy ,c.allInternship ,c.controlPost ,c.controlPostCode ,c.computerIntern ,c.mealCarSubsidy ,c.mealCarSubsidyFormal ,
		IF(v.deptIdO<>'',v.deptIdO,c.deptId) AS parentDeptId ,
		IF(v.deptIdO<>'',v.deptIdO,c.deptId) AS deptOId ,IF(v.deptNameO<>'',v.deptNameO,c.deptName) AS deptNameO ,v.deptIdS AS deptSId ,v.deptNameS AS deptNameS ,v.deptIdT AS deptTId ,v.deptNameT AS deptNameT ,v.deptIdF AS deptFId ,v.deptNameF AS deptNameF
		from oa_hr_recruitment_interview c
		left join department_view v on c.deptId = v.deptId
		where 1=1 "
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "resumeId",
		"sql" => " and c.resumeId=# "
	),
	array (
		"name" => "userNo",
		"sql" => " and c.userNo=# "
	),
	array (
		"name" => "userAccount",
		"sql" => " and c.userAccount=# "
	),
	array (
		"name" => "userName",
		"sql" => " and c.userName=# "
	),
	array (
		"name" => "userNameSearch",
		"sql" => " and c.userName LIKE CONCAT('%',#,'%')"
	),
	array (
		"name" => "sexy",
		"sql" => " and c.sexy=# "
	),
	array (
		"name" => "positionsName",
		"sql" => " and c.positionsName=# "
	),
	array (
		"name" => "positionsNameSearch",
		"sql" => " and c.positionsName LIKE CONCAT('%',#,'%') "
	),
	array (
		"name" => "deptName",
		"sql" => " and c.deptName=# "
	),
	array (
		"name" => "deptNamSearche",
		"sql" => " and c.deptName LIKE CONCAT('%',#,'%')"
	),
	array (
		"name" => "deptId",
		"sql" => " and c.deptId=# "
	),
	array (
		"name" => "deptIds",
		"sql" => " and c.deptId in(arr) "
	),
	array (
		"name" => "projectGroup",
		"sql" => " and c.projectGroup=# "
	),
	array (
		"name" => "positionsId",
		"sql" => " and c.positionsId=# "
	),
	array (
		"name" => "useWriteEva",
		"sql" => " and c.useWriteEva=# "
	),
	array (
		"name" => "useInterviewEva",
		"sql" => " and c.useInterviewEva=# "
	),
	array (
		"name" => "useInterviewResult",
		"sql" => " and c.useInterviewResult=# "
	),
	array (
		"name" => "useInterviewer",
		"sql" => " and c.useInterviewer=# "
	),
	array (
		"name" => "useInterviewerId",
		"sql" => " and c.useInterviewerId=# "
	),
	array (
		"name" => "useInterviewDate",
		"sql" => " and c.useInterviewDate=# "
	),
	array (
		"name" => "useHireType",
		"sql" => " and c.useHireType=# "
	),
	array (
		"name" => "useHireTypeName",
		"sql" => " and c.useHireTypeName=# "
	),
	array (
		"name" => "useJobName",
		"sql" => " and c.useJobName=# "
	),
	array (
		"name" => "useJobId",
		"sql" => " and c.useJobId=# "
	),
	array (
		"name" => "useAreaName",
		"sql" => " and c.useAreaName=# "
	),
	array (
		"name" => "useAreaId",
		"sql" => " and c.useAreaId=# "
	),
	array (
		"name" => "useTrialWage",
		"sql" => " and c.useTrialWage=# "
	),
	array (
		"name" => "useFormalWage",
		"sql" => " and c.useFormalWage=# "
	),
	array (
		"name" => "useDemandEqu",
		"sql" => " and c.useDemandEqu=# "
	),
	array (
		"name" => "useSign",
		"sql" => " and c.useSign=# "
	),
	array (
		"name" => "useManager",
		"sql" => " and c.useManager=# "
	),
	array (
		"name" => "useManagerId",
		"sql" => " and c.useManagerId=# "
	),
	array (
		"name" => "useSignDate",
		"sql" => " and c.useSignDate=# "
	),
	array (
		"name" => "hrInterviewResult",
		"sql" => " and c.hrInterviewResult=# "
	),
	array (
		"name" => "hrInterviewer",
		"sql" => " and c.hrInterviewer=# "
	),
	array (
		"name" => "hrInterviewerId",
		"sql" => " and c.hrInterviewerId=# "
	),
	array (
		"name" => "hrInterviewDate",
		"sql" => " and c.hrInterviewDate=# "
	),
	array (
		"name" => "hrHireType",
		"sql" => " and c.hrHireType=# "
	),
	array (
		"name" => "hrHireTypeName",
		"sql" => " and c.hrHireTypeName=# "
	),
	array (
		"name" => "hrRequire",
		"sql" => " and c.hrRequire=# "
	),
	array (
		"name" => "hrSourceType1",
		"sql" => " and c.hrSourceType1=# "
	),
	array (
		"name" => "hrSourceType1Name",
		"sql" => " and c.hrSourceType1Name LIKE CONCAT('%',#,'%') "
	),
	array (
		"name" => "hrSourceType2",
		"sql" => " and c.hrSourceType2=# "
	),
	array (
		"name" => "hrSourceType2Name",
		"sql" => " and c.hrSourceType2Name LIKE CONCAT('%',#,'%') "
	),
	array (
		"name" => "hrSourceType2NameSearch",
		"sql" => " and c.hrSourceType2Name LIKE CONCAT('%',#,'%') "
	),
	array (
		"name" => "hrJobName",
		"sql" => " and c.hrJobName=# "
	),
	array (
		"name" => "hrJobId",
		"sql" => " and c.hrJobId=# "
	),
	array (
		"name" => "hrIsManageJob",
		"sql" => " and c.hrIsManageJob=# "
	),
	array (
		"name" => "hrIsMatch",
		"sql" => " and c.hrIsMatch=# "
	),
	array (
		"name" => "hrChargerId",
		"sql" => " and c.hrChargerId=# "
	),
	array (
		"name" => "hrCharger",
		"sql" => " and c.hrCharger=# "
	),
	array (
		"name" => "hrManager",
		"sql" => " and c.hrManager=# "
	),
	array (
		"name" => "hrManagerId",
		"sql" => " and c.hrManagerId=# "
	),
	array (
		"name" => "manager",
		"sql" => " and c.manager=# "
	),
	array (
		"name" => "managerId",
		"sql" => " and c.managerId=# "
	),
	array (
		"name" => "deputyManager",
		"sql" => " and c.deputyManager=# "
	),
	array (
		"name" => "deputyManagerId",
		"sql" => " and c.deputyManagerId=# "
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
		"name" => "createTime",
		"sql" => " and c.createTime=# "
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
		"name" => "updateTime",
		"sql" => " and c.updateTime=# "
	),
	array (
		"name" => "sysCompanyName",
		"sql" => " and c.sysCompanyName=# "
	),
	array (
		"name" => "sysCompanyId",
		"sql" => " and c.sysCompanyId=# "
	),
	array (
		"name" => "recuitid",
		"sql" => " and c.sysCompanyId=# "
	),
	array (
		"name" => "interviewType",
		"sql" => " and c.interviewType=# "
	),
	array (
		"name" => "parentId",
		"sql" => " and c.parentId=# "
	),
	array(
		'name'=>'formDate',
		"sql" => " and c.formDate LIKE BINARY CONCAT('%',#,'%') "
	),
	array(
		'name'=>'DateBegin',
		"sql" => " and c.formDate >= BINARY # "
	),
	array(
		'name'=>'DateEnd',
		"sql" => " and c.formDate <= BINARY # "
	),
	array (
		"name" => "provinceArr",
		"sql" => " and c.workProvince in(arr) "
	),
	array (
		"name" => "formCode",
		"sql" => " and c.formCode LIKE CONCAT('%',#,'%') "
	),
	array (
		"name" => "ExaStatus",
		"sql" => " and c.ExaStatus LIKE CONCAT('%',#,'%') "
	),
	array (
		"name" => "deptState",
		"sql" => " and c.deptState=# "
	),
	array (
		"name" => "hrState",
		"sql" => " and c.hrState=# "
	),
	array (
		"name" => "state",
		"sql" => " and c.state=# "
	)
)
?>