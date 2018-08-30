<?php
/**
 * @author Administrator
 * @Date 2012年8月6日 21:39:45
 * @version 1.0
 * @description:员工转正考核评估表 sql配置文件
 */
$sql_arr = array (
	"select_default"=>"select c.id ,c.formCode ,c.formDate ,c.userNo ,c.userAccount ,c.useName ,c.sex ,c.age ,c.deptId ,c.deptCode ,c.deptName ,c.positionId ,c.positionName ,c.educationCode ,c.education ,c.school ,c.professional ,c.selfScore ,c.totalScore ,c.leaderScore,c.tutor ,c.tutorId ,c.masterComment ,c.tutorDate ,c.leaderId ,c.leaderName ,c.leaderComment ,c.leaderDate ,c.directorComment ,c.phone ,c.workSeniority ,c.reformDT ,c.begintime ,c.finishtime ,c.summary ,c.plan ,c.planstatus ,c.summarystatus ,c.permanentType ,c.permanentTypeCode ,c.permanentDate ,c.interviewSalary,c.suggestSalary,c.beforeSalary ,c.afterSalary ,c.afterPositionId ,c.afterPositionName ,c.levelName ,c.levelCode ,c.positionCode ,c.schemeId ,c.aboutIt ,c.isAgree ,c.comment ,c.status ,c.ExaStatus ,c.ExaDT ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime ,c.deptId,c.deptIdS,c.deptIdT  from oa_hr_permanent_examine c where 1=1 ",

	"examine_export_list" => "select c.id ,c.userNo ,c.useName,c.companyName ,c.deptName ,c.deptNameS ,c.deptNameT ,c.deptNameF ,c.positionName ,c.permanentDate ,c.begintime ,c.deptId ,c.deptIdS ,c.deptIdT ,c.deptIdF ,c.finishtime ,c.beforeSalary ,c.afterSalary ,c.formDate
		from (
			select c.id ,c.userNo ,c.useName,d.companyName ,d.deptName ,d.deptNameS ,d.deptNameT ,d.deptNameF ,c.positionName ,c.permanentDate ,c.begintime ,c.deptId ,d.deptIdS ,d.deptIdT ,d.deptIdF ,c.finishtime ,c.beforeSalary ,c.afterSalary ,c.formDate
			from
				oa_hr_permanent_examine c join oa_hr_personnel d where c.userNo=d.userNo
			union
			select c.id ,c.userNo ,c.userName ,d.companyName ,d.deptName ,d.deptNameS ,d.deptNameT ,d.deptNameF ,c.afterPositionName as positionName ,c.permanentDate ,d.entryDate as begintime ,c.deptId ,d.deptIdS ,d.deptIdT ,d.deptIdF ,d.becomeDate as finishtime ,c.beforeSalary ,c.afterSalary ,date_format(c.createTime ,'%Y-%m-%d') as formDate
			from
				oa_hr_trialdeptsuggest c join oa_hr_personnel d where c.userNo=d.userNo
		)c where 1=1 ",

	"hrsql" => "select c.id ,c.formCode ,c.formDate ,c.userNo ,c.userAccount ,c.useName ,c.sex ,c.age ,c.deptId ,c.deptCode ,c.deptName ,c.positionId ,c.positionName ,c.educationCode ,c.education ,c.school ,c.professional ,c.selfScore ,c.totalScore ,c.leaderScore,c.tutor ,c.tutorId ,c.masterComment ,c.tutorDate ,c.leaderId ,c.leaderName ,c.leaderComment ,c.leaderDate ,c.directorComment ,c.phone ,c.workSeniority ,c.reformDT ,c.begintime ,c.finishtime ,c.summary ,c.plan ,c.planstatus ,c.summarystatus ,c.permanentType ,c.permanentTypeCode ,c.permanentDate ,c.interviewSalary,c.suggestSalary,c.beforeSalary ,c.afterSalary ,c.afterPositionId ,c.afterPositionName ,c.levelName ,c.levelCode ,c.positionCode ,c.schemeId ,c.aboutIt ,c.isAgree ,c.comment ,c.status ,c.statuss ,c.ExaStatus ,c.ExaDT ,c.permanentMonth,c.schemeName ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime,c.finishMonth,c.finishYear,c.suggestJobName ,c.suggestJobId
		from (
			select c.id ,c.formCode ,c.formDate ,c.userNo ,c.userAccount ,c.useName ,c.sex ,c.age ,c.deptId ,c.deptCode ,c.deptName ,c.positionId ,c.positionName ,c.educationCode ,c.education ,c.school ,c.professional ,c.selfScore ,c.totalScore ,c.leaderScore,c.tutor ,c.tutorId ,c.masterComment ,c.tutorDate ,c.leaderId ,c.leaderName ,c.leaderComment ,c.leaderDate ,c.directorComment ,c.phone ,c.workSeniority ,c.reformDT ,c.begintime ,c.finishtime ,c.summary ,c.plan ,c.planstatus ,c.summarystatus ,c.permanentType ,c.permanentTypeCode ,c.permanentDate ,c.interviewSalary,c.suggestSalary ,c.beforeSalary ,c.afterSalary ,c.afterPositionId ,c.afterPositionName ,c.levelName ,c.levelCode ,c.positionCode ,c.schemeId ,c.aboutIt ,c.isAgree ,c.comment ,c.status ,CASE c.status WHEN '9' THEN '0' WHEN '2' THEN '1' WHEN '3' THEN '2' WHEN '6' THEN '3' WHEN '8' THEN '4' WHEN '5' THEN '5' WHEN '7' THEN '6' ELSE '' END  as statuss,c.ExaStatus,c.ExaDT ,date_format(c.permanentDate ,'%Y-%m') as permanentMonth ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime ,c.schemeName ,year(c.finishtime) as finishYear ,month(c.finishtime) as finishMonth ,c.suggestJobName ,c.suggestJobId
			from
				oa_hr_permanent_examine c
			where
				1 = 1
			union
			select
				concat('person',c.id) as id ,'' ,'' ,c.userNo ,c.userAccount ,c.userName ,c.sex ,c.age ,c.deptId ,c.deptCode ,c.deptName ,c.jobId as position,c.jobName as positionName ,'' ,'' ,'' ,'' ,'' ,'' ,'' as leaderScore ,'' ,'' ,'' ,'' ,'' ,'' ,'' ,'' ,'' as directorComment ,'' ,'' ,'' ,c.entryDate ,c.becomeDate,'' ,'' ,'' ,'' as summarystatus ,'' ,'' ,'' ,'' ,'' ,'' ,'' as afterSalary ,'' ,'' ,'','' ,'' ,'' ,'' ,'0' as isAgree ,'' as comment ,'9' as status ,'0' as statuss ,'' as ExaStatus ,'' as ExaDT ,'' as permanentMonth ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime ,'' ,'' ,'' ,'' ,c.schemeName
			from
				oa_hr_personnel c
			where c.staffState = 'YGZTSY' and c.userNo not in (
				select c.userNo from oa_hr_permanent_examine c where status <> 0
			)
		) c where 1",
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
		"name" => "userNo",
		"sql" => " and c.userNo LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "userAccount",
		"sql" => " and c.userAccount=# "
	),
	array(
		"name" => "useName",
		"sql" => " and c.useName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "sex",
		"sql" => " and c.sex=# "
	),
	array(
		"name" => "age",
		"sql" => " and c.age=# "
	),
	array(
		"name" => "deptId",
		"sql" => " and c.deptId=# "
	),
	array(
		"name" => "deptCode",
		"sql" => " and c.deptCode=# "
	),
	array(
		"name" => "deptName",
		"sql" => " and c.deptName=# "
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
		"name" => "educationCode",
		"sql" => " and c.educationCode=# "
	),
	array(
		"name" => "education",
		"sql" => " and c.education=# "
	),
	array(
		"name" => "school",
		"sql" => " and c.school=# "
	),
	array(
		"name" => "professional",
		"sql" => " and c.professional=# "
	),
	array(
		"name" => "selfScore",
		"sql" => " and c.selfScore LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "totalScore",
		"sql" => " and c.totalScore LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "tutor",
		"sql" => " and c.tutor=# "
	),
	array(
		"name" => "tutorId",
		"sql" => " and c.tutorId=# "
	),
	array(
		"name" => "masterComment",
		"sql" => " and c.masterComment=# "
	),
	array(
		"name" => "tutorDate",
		"sql" => " and c.tutorDate=# "
	),
	array(
		"name" => "leaderScore",
		"sql" => " and c.leaderScore LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "leaderId",
		"sql" => " and c.leaderId=# "
	),
	array(
		"name" => "leaderName",
		"sql" => " and c.leaderName=# "
	),
	array(
		"name" => "leaderComment",
		"sql" => " and c.leaderComment=# "
	),
	array(
		"name" => "leaderDate",
		"sql" => " and c.leaderDate=# "
	),
	array(
		"name" => "directorComment",
		"sql" => " and c.directorComment=# "
	),
	array(
		"name" => "phone",
		"sql" => " and c.phone=# "
	),
	array(
		"name" => "workSeniority",
		"sql" => " and c.workSeniority=# "
	),
	array(
		"name" => "reformDT",
		"sql" => " and c.reformDT LIKE BINARY CONCAT('%',#,'%') "
	),
	array(
		"name" => "begintime",
		"sql" => " and c.begintime LIKE BINARY CONCAT('%',#,'%') "
	),
	array(
		"name" => "finishtime",
		"sql" => " and c.finishtime LIKE BINARY CONCAT('%',#,'%') "
	),
	array(
		"name" => "summary",
		"sql" => " and c.summary=# "
	),
	array(
		"name" => "plan",
		"sql" => " and c.plan=# "
	),
	array(
		"name" => "planstatus",
		"sql" => " and c.planstatus=# "
	),
	array(
		"name" => "summarystatus",
		"sql" => " and c.summarystatus=# "
	),
	array(
		"name" => "permanentType",
		"sql" => " and c.permanentType LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "permanentTypeCode",
		"sql" => " and c.permanentTypeCode=# "
	),
	array(
		"name" => "permanentDate",
		"sql" => " and c.permanentDate LIKE BINARY CONCAT('%',#,'%') "
	),
	array(
		"name" => "beforeSalary",
		"sql" => " and c.beforeSalary=# "
	),
	array(
		"name" => "afterSalary",
		"sql" => " and c.afterSalary=# "
	),
	array(
		"name" => "afterPositionId",
		"sql" => " and c.afterPositionId=# "
	),
	array(
		"name" => "afterPositionName",
		"sql" => " and c.afterPositionName=# "
	),
	array(
		"name" => "levelName",
		"sql" => " and c.levelName=# "
	),
	array(
		"name" => "levelCode",
		"sql" => " and c.levelCode=# "
	),
	array(
		"name" => "positionCode",
		"sql" => " and c.positionCode=# "
	),
	array(
		"name" => "schemeId",
		"sql" => " and c.schemeId=# "
	),
	array(
		"name" => "aboutIt",
		"sql" => " and c.aboutIt=# "
	),
	array(
		"name" => "isAgree",
		"sql" => " and c.isAgree LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "permanentMonth",
		"sql" => " and c.permanentMonth LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "comment",
		"sql" => " and c.comment=# "
	),
	array(
		"name" => "status",
		"sql" => " and c.status=# "
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
		"name" => "preDateHope",
		"sql" => " and c.formDate >= BINARY #"
	),
	array(
		"name" => "afterDateHope",
		"sql" => " and c.formDate <= BINARY #"
	),
	array(
		"name" => "begintimeCmp",
		"sql" => " and c.begintime >= BINARY # "
	),
	array(
		"name" => "finishtimeCmp",
		"sql" => " and c.finishtime <= BINARY # "
	),
	array(
		"name" => "deptNameSame",
		"sql" => " and c.deptId =# "
	),
	array(
		"name" => "deptNameSSame",
		"sql" => " and c.deptIds =# "
	),
	array(
		"name" => "deptNameTSame",
		"sql" => " and c.deptIdT =# "
	),
	array(
		"name" => "deptIdF",
		"sql" => " and c.deptIdF =# "
	),
	array(
		"name" => "userNameSame",
		"sql" => " and c.userName =# "
	),
	array(
		"name" => "statusArr",
		"sql" => " and c.status in(arr) "
	),
	array(
		"name" => "DeptArr",
		"sql" => " and c.deptId in (SELECT d.DEPT_ID FROM department d WHERE d.Leader_id = #) "
	),
	array(
		"name" => "LinkAccount",
		"sql" => " and (c.userAccount = # || (c.tutorId = # and c.status!=1)|| (c.leaderId = # and c.status in (3,4,5,6,7,8))|| c.deptId IN (SELECT d.DEPT_ID FROM department d WHERE d.Leader_id = #)) "
	),
	array(
		"name" => "finishMonth",
		"sql" => " and c.finishMonth = #"
	),
	array(
		"name" => "finishYear",
		"sql" => " and c.finishYear = #"
	),
	array(
		"name" => "suggestJobName",
		"sql" => " and c.suggestJobName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "schemeName",
		"sql" => " and c.schemeName LIKE CONCAT('%',#,'%') "
	)
)
?>