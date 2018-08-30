<?php
/**
 * @author zengq
 * @Date 2012年10月16日 9:21:33
 * @version 1.0
 * @description:招聘计划 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.formCode ,c.deptName ,c.deptId ,c.formManId ,c.formManName ,c.formDate ,c.postType ,c.postTypeName ,c.useAreaName ,c.useAreaId ,c.positionId ,c.positionName ,c.developPositionId ,c.developPositionName ,c.positionLevel ,c.projectType ,c.projectGroupId ,c.projectCode ,c.projectGroup ,c.isEmergency ,c.needNum ,c.workPlace ,c.hopeDate ,c.wageRange ,c.addMode ,c.addModeCode ,c.addType ,c.addTypeCode ,c.leaveManId ,c.leaveManName ,c.employmentType ,c.employmentTypeCode ,c.sex ,c.age ,c.maritalStatus ,c.maritalStatusName ,c.educationName ,c.education ,c.professionalRequire ,c.workExperiernce ,c.otherRemark ,c.applyReason ,c.workDuty ,c.workArrange ,c.assessmentIndex ,c.resumeToId ,c.resumeToName ,c.entryNum ,c.beEntryNum ,c.recruitManId ,c.recruitManName ,c.assistManId ,c.assistManName ,c.assignedManId ,c.assignedManName ,c.assignedDate ,c.state ,c.ExaStatus ,c.ExaDT ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime ,c.sysCompanyName ,c.sysCompanyId  from oa_hr_recruitplan_plan c where 1=1 "
);

$condition_arr = array (
array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
   		),
   		array(
   		"name" => "formCode",
   		"sql" => " and c.formCode like concat('%',#,'%')"
   		),
   		array(
   		"name" => "deptName",
   		"sql" => " and c.deptName like concat('%',#,'%') "
   		),
   		array(
   		"name" => "deptId",
   		"sql" => " and c.deptId=# "
   		),
   		array(
   		"name" => "formManId",
   		"sql" => " and c.formManId=# "
   		),
   		array(
   		"name" => "formManName",
   		"sql" => " and c.formManName like concat('%',#,'%') "
   		),
   		array(
   		"name" => "formDate",
   		"sql" => " and c.formDate=# "
   		),
   		array(
   		"name" => "postType",
   		"sql" => " and c.postType=# "
   		),
   		array(
   		"name" => "postTypeName",
   		"sql" => " and c.postTypeName=# "
   		),
   		array(
   		"name" => "useAreaName",
   		"sql" => " and c.useAreaName=# "
   		),
   		array(
   		"name" => "useAreaId",
   		"sql" => " and c.useAreaId=# "
   		),
   		array(
   		"name" => "positionId",
   		"sql" => " and c.positionId=# "
   		),
   		array(
   		"name" => "positionName",
   		"sql" => " and c.positionName=# "
   		),
   		array(
   		"name" => "developPositionId",
   		"sql" => " and c.developPositionId=# "
   		),
   		array(
   		"name" => "developPositionName",
   		"sql" => " and c.developPositionName=# "
   		),
   		array(
   		"name" => "positionLevel",
   		"sql" => " and c.positionLevel=# "
   		),
   		array(
   		"name" => "projectType",
   		"sql" => " and c.projectType=# "
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
   		"name" => "isEmergency",
   		"sql" => " and c.isEmergency=# "
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
   		"sql" => " and c.addType=# "
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
   		"sql" => " and c.leaveManName=# "
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
   		"name" => "workDuty",
   		"sql" => " and c.workDuty=# "
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
   		"sql" => " and c.recruitManName=# "
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
   		"name" => "sysCompanyName",
   		"sql" => " and c.sysCompanyName=# "
   		),
   		array(
   		"name" => "sysCompanyId",
   		"sql" => " and c.sysCompanyId=# "
   		)
   		)
   		?>