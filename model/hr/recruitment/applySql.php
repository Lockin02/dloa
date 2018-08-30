<?php
/**
 * @author Administrator
 * @Date 2012年7月11日 星期三 13:20:21
 * @version 1.0
 * @description:增员申请表 sql配置文件
 */
$sql_arr = array (
	"select_default"=>"select c.id ,c.formCode ,c.deptName ,c.deptId ,c.formManId ,c.formManName ,c.formDate ,c.postType ,c.postTypeName ,c.useAreaName ,c.useAreaId ,c.positionId ,c.positionName ,c.developPositionId ,c.developPositionName ,c.network ,c.device ,c.positionLevel ,c.projectType ,c.projectGroupId ,c.projectCode ,c.projectGroup ,c.isEmergency ,c.needNum ,c.workPlace ,c.hopeDate ,c.wageRange ,c.addMode ,c.addModeCode ,c.addType ,c.addTypeCode ,c.leaveManId ,c.leaveManName ,c.employmentType ,c.employmentTypeCode ,c.sex ,c.age ,c.maritalStatus ,c.maritalStatusName ,c.educationName ,c.education ,c.professionalRequire ,c.workExperiernce ,c.otherRemark ,c.applyReason ,c.workDuty ,c.workArrange ,c.assessmentIndex ,c.resumeToId ,c.resumeToName ,c.entryNum ,c.beEntryNum ,c.recruitManId ,c.recruitManName ,c.assistManId ,c.assistManName ,c.assignedManId ,c.assignedManName ,c.assignedDate ,c.state ,c.ExaStatus ,c.ExaDT ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime ,c.sysCompanyName ,c.sysCompanyId,c.applyRemark ,c.jobRequire ,c.keyPoint ,c.attentionMatter ,c.leaderLove ,c.positionRequirement ,c.editHeadTime ,c.employName ,c.employId ,c.employRemark ,c.cancelContent ,c.stopStart ,c.tutor ,c.tutorId ,c.computerConfiguration ,c.stopCancelNum from oa_hr_recruitment_apply c where 1=1 ",

	//增员申请显示
	"select_list"=>"select c.id ,c.formCode ,c.deptName ,c.deptId ,c.formManId ,c.formManName ,c.formDate ,c.postType ,c.postTypeName ,c.useAreaName ,c.useAreaId ,c.positionId ,c.positionName ,c.developPositionId ,c.developPositionName ,c.network ,c.device ,c.positionLevel ,c.projectType ,c.projectGroupId ,c.projectCode ,c.projectGroup ,c.isEmergency ,c.needNum ,c.workPlace ,c.hopeDate ,c.wageRange ,c.addMode ,c.addModeCode ,c.addType ,c.addTypeCode ,c.leaveManId ,c.leaveManName ,c.employmentType ,c.employmentTypeCode ,c.sex ,c.age ,c.maritalStatus ,c.maritalStatusName ,c.educationName ,c.education ,c.professionalRequire ,c.workExperiernce ,c.otherRemark ,c.applyReason ,c.workDuty ,c.workArrange ,c.assessmentIndex ,c.resumeToId ,c.resumeToName ,c.entryNum ,c.beEntryNum ,c.recruitManId ,c.recruitManName ,c.assistManId ,c.assistManName ,c.assignedManId ,c.assignedManName ,c.assignedDate ,c.state ,c.ExaStatus ,c.ExaDT ,c.createName ,c.createId ,c.updateName ,c.updateId ,c.updateTime ,c.sysCompanyName ,c.sysCompanyId ,c.applyRemark ,c.jobRequire ,c.keyPoint ,c.attentionMatter ,c.leaderLove ,c.positionRequirement ,c.editHeadTime ,c.employName ,c.employId ,c.employRemark ,c.cancelContent ,c.stopStart ,c.computerConfiguration ,c.stopCancelNum ,c.tutor ,c.tutorId ,
		IF(f.deptIdO<>'',f.deptIdO,c.deptId) AS deptOId ,IF(f.deptNameO<>'',f.deptNameO,c.deptName) AS deptNameO ,f.deptIdS AS deptSId ,f.deptNameS AS deptNameS ,f.deptIdT AS deptTId ,f.deptNameT AS deptNameT ,f.deptIdF AS deptFId ,f.deptNameF AS deptNameF,
		d.CreatDT ,e.createTime ,e.lastOfferTime ,e.entryDate ,e.userName
		from oa_hr_recruitment_apply c
		LEFT JOIN department_view f ON (c.deptId = f.deptId)
		LEFT JOIN (
			SELECT e.sourceCode ,
			MIN(e.createTime) AS createTime ,MAX(e.createTime) AS lastOfferTime ,MIN(e.entryDate) AS entryDate ,GROUP_CONCAT(e.userName) AS userName
			FROM oa_hr_recruitment_entrynotice e
			LEFT JOIN oa_hr_recruitment_apply a ON a.formCode = e.sourceCode
			WHERE e.state IN (1, 2)
			GROUP BY a.id
			ORDER BY e.createTime ASC
		) e ON c.formCode = e.sourceCode
		LEFT JOIN (
			SELECT a.sourceCode ,u.CreatDT
			FROM oa_hr_recruitment_entrynotice a
			LEFT JOIN `user` u ON userAccount = u.USER_ID
			WHERE a.state = '2' AND u.CreatDT<>''
			ORDER BY u.CreatDT ASC
		) d ON c.formCode = d.sourceCode
		where 1 = 1 "
);

$condition_arr = array (
	array(
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array(
		"name" => "formCode" ,
		"sql" => " and c.formCode LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "deptName",
		"sql" => " and c.deptName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "deptNameO",
		"sql" => " and (f.deptNameO LIKE CONCAT('%',#,'%') OR c.deptName LIKE CONCAT('%',#,'%'))"
	),
	array(
		"name" => "deptNameS",
		"sql" => " and f.deptNameS LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "deptNameT",
		"sql" => " and f.deptNameT LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "deptNameF",
		"sql" => " and f.deptNameF LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "noDeptName",
		"sql" => " and c.deptName not in(arr) "
	),
	array(
		"name" => "postTypeName",
		"sql" => " and c.postTypeName LIKE CONCAT('%',#,'%') "
	),
    array(
        "name" => "deptId",
        "sql" => " and c.deptId=# "
    ),
    array(
        "name" => "deptIdArr",
        "sql" => " and c.deptId in(arr) "
    ),
	array(
		"name" => "formManId",
		"sql" => " and c.formManId=# "
	),
	array(
		"name" => "formManName",
		"sql" => " and c.formManName LIKE CONCAT('%',#,'%')  "
	),
	array(
		"name" => "formDate",
		"sql" => " and c.formDate LIKE BINARY CONCAT('%',#,'%') "
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
		"name" => "needNum",
		"sql" => " and c.needNum=# "
	),
	array(
		"name" => "workPlace",
		"sql" => " and c.workPlace=# "
	),
	array(
		"name" => "workPlaceSearch",
		"sql" => " and c.workPlace LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "hopeDate",
		"sql" => " and c.hopeDate=# "
	),
	array(
		"name" => "wageRange",
		"sql" => " and c.wageRange=# "
	),
	array(
		"name" => "addMode",
		"sql" => " and c.addMode=# "
	),
	array(
		"name" => "addModeCode",
		"sql" => " and c.addModeCode=# "
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
		"name" => "leaveManId",
		"sql" => " and c.leaveManId=# "
	),
	array(
		"name" => "leaveManName",
		"sql" => " and c.leaveManName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "employmentType",
		"sql" => " and c.employmentType=# "
	),
	array(
		"name" => "employmentTypeCode",
		"sql" => " and c.employmentTypeCode=# "
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
		"name" => "maritalStatus",
		"sql" => " and c.maritalStatus=# "
	),
	array(
		"name" => "maritalStatusName",
		"sql" => " and c.maritalStatusName=# "
	),
	array(
		"name" => "educationName",
		"sql" => " and c.educationName=# "
	),
	array(
		"name" => "education",
		"sql" => " and c.education=# "
	),
	array(
		"name" => "professionalRequire",
		"sql" => " and c.professionalRequire=# "
	),
	array(
		"name" => "workExperiernce",
		"sql" => " and c.workExperiernce=# "
	),
	array(
		"name" => "otherRemark",
		"sql" => " and c.otherRemark=# "
	),
	array(
		"name" => "applyReason",
		"sql" => " and c.applyReason=# "
	),
	array(
		"name" => "applyReasonSearch",
		"sql" => " and c.applyReason LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "workDuty",
		"sql" => " and c.workDuty=# "
	),
	array(
		"name" => "workDutySearch",
		"sql" => " and c.workDuty LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "workArrange",
		"sql" => " and c.workArrange=# "
	),
	array(
		"name" => "assessmentIndex",
		"sql" => " and c.assessmentIndex=# "
	),
	array(
		"name" => "resumeToId",
		"sql" => " and c.resumeToId=# "
	),
	array(
		"name" => "resumeToName",
		"sql" => " and c.resumeToName=# "
	),
	array(
		"name" => "resumeToNameSearch",
		"sql" => " and c.resumeToName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "entryNum",
		"sql" => " and c.entryNum=# "
	),
	array(
		"name" => "beEntryNum",
		"sql" => " and c.beEntryNum=# "
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
		"name" => "assistManId",
		"sql" => " and c.assistManId=# "
	),
	array(
		"name" => "assistManName",
		"sql" => " and c.assistManName=# "
	),
	array(
		"name" => "assistManNameSearch",
		"sql" => " and c.assistManName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "recruitManNameSearch",
		"sql" => " and c.recruitManName LIKE CONCAT('%',#,'%') "
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
		"name" => "assignedDateBef",
		"sql" => " and c.assignedDate >= BINARY # "
	),
	array(
		"name" => "assignedDateEnd",
		"sql" => " and c.assignedDate <= BINARY # "
	),
	array(
		"name" => "stateArr",
		"sql" => " and c.state in(arr) "
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
		"name" => "ExaStatusArr",
		"sql" => " and c.ExaStatus in(arr) "
	),
	array(
		"name" => "ExaDT",
		"sql" => " and c.ExaDT=# "
	),
	array(
		"name" => "ExaDTSea",
		"sql" => " and c.ExaDT LIKE BINARY CONCAT('%',#,'%') "
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
		"name" => "createTimeSea",
		"sql" => " and e.createTime LIKE BINARY CONCAT('%',#,'%') "
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
		"name" => "mylinkId",
		"sql" => " and c.id in (select parentId from oa_hr_apply_menber where assesManId=#) "
	),
	array(
		"name" => "isEmergency",
		"sql" => " and c.isEmergency=# "
	),
	array(
		"name" => "DateBegin",
		"sql" => " and c.hopeDate >= BINARY # "
	),
	array(
		"name" => "DateEnd",
		"sql" => " and c.hopeDate <= BINARY # "
	),
	array(
		"name" => "postType",
		"sql" => " and c.postType =# "
	),
	array(
		"name" => "postTypeSearch",
		"sql" => " and c.postType LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "positionLevelSearch",
		"sql" => " and c.positionLevel LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "projectGroupSearch",
		"sql" => " and c.projectGroup LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "formDateBefSearch",
		"sql" => " and c.formDate >= BINARY # "
	),
	array(
		"name" => "formDateEndSearch",
		"sql" => " and c.formDate <= BINARY # "
	),
	array(
		"name" => "ExaDTBefSearch",
		"sql" => " and c.ExaDT >= BINARY # "
	),
	array(
		"name" => "ExaDTEndSearch",
		"sql" => " and c.ExaDT <= BINARY # "
	),
	array(
		"name" => "createTimeBefSearch",
		"sql" => " and e.createTime >= BINARY # "
	),
	array(
		"name" => "createTimeEndSearch",
		"sql" => " and e.createTime <= BINARY # "
	),
	array(
		"name" => "entryDateSea",
		"sql" => " and e.entryDate LIKE BINARY CONCAT('%',#,'%') "
	),
	array(
		"name" => "entryDateBefSearch",
		"sql" => " and e.entryDate >= BINARY # "
	),
	array(
		"name" => "entryDateEndSearch",
		"sql" => " and e.entryDate <= BINARY # "
	),
	array(
		"name" => "jobRequireSearch",
		"sql" => " and c.jobRequire LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "applyRemarkSearch",
		"sql" => " and c.applyRemark LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "keyPoint",
		"sql" => " and c.keyPoint LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "attentionMatter",
		"sql" => " and c.attentionMatter LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "leaderLove",
		"sql" => " and c.leaderLove LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "positionRequirement",
		"sql" => " and c.positionRequirement LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "userName",
		"sql" => " and (e.userName LIKE CONCAT('%',#,'%') OR c.employName LIKE CONCAT('%',#,'%'))"
	),
	/*****add chenrf 20130508****/
	array(
		"name" => "entryDateBegin",
		"sql" => " and e.entryDate >= BINARY # "
	),
	array(
		"name" => "entryDateEnd",
		"sql" => " and e.entryDate <= BINARY # "
	),
	array(
		"name" => "ExaDTBegin",
		"sql" => " and c.ExaDT >= BINARY # "
	),
	array(
		"name" => "ExaDTEnd",
		"sql" => " and c.ExaDT <= BINARY # "
	),
	array(
		"name" => "state_d",
		"sql" => " and c.state !=# "
	),
	array(
		"name" => "moreThanNeednum",
		"sql" => " and c.needNum >(if(c.entryNum,c.entryNum,0) + if(c.beEntryNum,c.beEntryNum,0) + c.stopCancelNum) "
	)
)
?>