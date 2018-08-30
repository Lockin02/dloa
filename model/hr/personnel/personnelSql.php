<?php

/**
 * @author Administrator
 * @Date 2012年5月25日 星期五 15:21:04
 * @version 1.0
 * @description:人事管理-基本信息 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.userNo ,c.userAccount ,c.staffName ,c.userName ,c.englishName ,c.sex ,c.birthdate ,c.nativePlacePro ,c.age ,c.nativePlaceCity ,c.nation ,c.identityCard ,c.politicsStatus ,c.companyType ,c.companyName ,c.belongDeptCode ,c.belongDeptName ,c.belongDeptId ,c.highEducation ,c.highEducationName ,c.highSchool ,c.professionalName ,c.deptCode ,c.deptName ,c.deptId ,c.deptNameS ,c.deptCodeS ,c.deptIdS ,c.deptNameT ,c.deptCodeT ,c.deptIdT,c.deptNameF ,c.deptCodeF ,c.deptIdF ,c.jobId ,c.jobName ,c.regionId ,c.regionName ,c.employeesState ,c.employeesStateName ,c.staffState ,c.staffStateName ,c.personnelType ,c.personnelTypeName ,c.position ,c.positionName ,c.personnelClass ,c.functionName ,c.englishSkillName ,c.oftenCardNum ,c.oftenAccount ,c.oftenBank ,c.personnelClassName ,c.wageLevelCode ,c.wageLevelName ,c.jobLevel ,c.healthState ,c.isMedicalHistory ,c.medicalHistory ,c.InfectDiseases ,c.height ,c.weight ,c.blood ,c.maritalStatus ,c.maritalStatusName ,c.birthStatus ,c.hobby ,c.speciality ,c.professional ,c.openingBank ,c.bankCardNum ,c.accountNumb ,c.archivesCode ,c.archivesLocation ,c.householdType ,c.collectResidence ,c.residencePro ,c.residenceCity ,c.tutor ,c.tutorId ,c.telephone ,c.mobile ,c.personEmail ,c.compEmail ,c.QQ ,c.MSN ,c.fetion ,c.information ,c.homePhone ,c.emergencyName ,c.emergencyRelation ,c.emergencyTel ,c.nowPlacePro ,c.nowPlaceCity ,c.nowAddress ,c.nowPost ,c.homeAddressPro ,c.homeAddressCity ,c.homeAddress ,c.homePost ,c.entryDate ,c.becomeDate ,c.realBecomeDate ,c.becomeFraction ,c.entryPlace ,c.quitDate ,c.quitTypeCode ,c.quitTypeName ,c.quitReson ,c.quitInterview ,c.remark ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime ,c.deleteName ,c.deleteNameId ,c.deieteTime ,c.deleteRemark ,c.personLevel ,c.personLevelId ,c.projectId ,c.projectCode ,c.projectName ,c.planEndDate ,c.taskName ,c.taskId ,c.taskPlanEnd ,c.officeId ,c.officeName ,c.ecountry ,c.ecountryId ,c.eprovinceId ,c.eprovince ,c.isNeedTutor ,c.ecityId ,concat(c.eprovince , c.ecity) AS eprovinceCity ,c.ecity ,c.socialPlace ,c.socialBuyer ,c.fundPlace ,c.fundPlaceId ,c.fundBuyer ,c.fundCardinality ,c.fundProportion ,c.workBeginDate ,c.seniority ,c.companyYear ,c.deviceCode ,c.deviceName ,c.networkName ,c.networkCode ,c.technologyName ,c.technologyCode ,c.outsourcingSupp ,c.outsourcingName ,c.unitPhone ,c.extensionNum ,c.unitFax ,c.mobilePhone ,c.shortNum ,c.otherPhone ,c.otherPhoneNum ,c.graduateDate ,c.salaryAreaCode ,c.identityCardDate ,c.identityCardAddress from oa_hr_personnel c where 1 = 1 ",

	"select_simple" => "select c.id ,c.userNo ,c.userAccount ,c.staffName,c.userName ,c.sex ,c.birthdate ,c.nativePlacePro ,c.age ,c.nativePlaceCity ,c.nation ,c.highEducation,c.highEducationName,c.highSchool,c.professionalName,c.identityCard ,c.politicsStatus ,c.companyType ,c.companyId,c.companyName ,c.belongDeptName ,belongDeptCode,c.belongDeptId ,c.deptName ,c.deptCode,c.deptId ,c.deptNameS ,c.deptCodeS,c.deptIdS ,c.deptNameT ,c.deptIdT ,c.deptCodeT,c.deptNameF ,c.deptCodeF ,c.deptIdF,c.jobId ,c.jobName ,c.regionId ,c.regionName ,c.employeesState ,c.employeesStateName ,c.personnelType,c.staffState ,c.staffStateName  ,c.personnelTypeName ,c.position ,c.positionName ,c.personnelClass ,c.personnelClassName ,c.wageLevelCode ,c.wageLevelName ,c.jobLevel,c.entryDate ,c.becomeDate ,c.becomeFraction ,c.entryPlace ,c.quitDate ,c.quitTypeCode ,c.quitTypeName ,c.quitReson ,c.quitInterview ,c.socialPlace ,c.identityCardDate ,c.identityCardAddress from oa_hr_personnel c where 1=1 ",

	"select_userNo" => "select max(cast( right(p.userNo,6) as SIGNED )) as muc from oa_hr_personnel p LEFT JOIN department d on p.belongDeptId=d.DEPT_ID  where 1=1 ",

	//外聘人员编号
	"select_extend_userNo" => "select max(cast( right(p.userNo,7) as SIGNED )) as muc from oa_hr_personnel  p LEFT JOIN department d on p.belongDeptId=d.DEPT_ID   where 1=1 and d.empFlag!=1  and locate('C',p.userNo) ",

	//试用转正列表
	"select_waitentry" => "select c.id ,c.userNo ,c.userAccount ,c.staffName ,c.userName ,c.sex ,c.birthdate ,c.nativePlacePro ,c.age ,c.nativePlaceCity ,c.nation ,c.identityCard ,c.politicsStatus ,c.companyType ,c.companyName ,c.belongDeptName ,c.belongDeptId ,c.deptNameS ,c.deptIdS ,c.deptNameT ,c.deptIdT ,c.jobId ,c.jobName ,c.regionId ,c.regionName ,c.employeesState ,c.employeesStateName ,c.personnelType ,c.personnelTypeName ,c.position ,c.positionName ,c.personnelClass ,c.personnelClassName ,c.wageLevelCode ,c.wageLevelName ,c.jobLevel ,c.entryDate ,c.becomeDate ,c.becomeFraction ,c.entryPlace ,c.quitDate ,c.quitTypeCode ,c.quitTypeName ,c.quitReson ,c.quitInterview ,c.suggestion ,c.trialPlan ,c.trialPlanId ,c.trialTask ,c.trialTaskId ,concat(c.nativePlacePro ,c.nativePlaceCity) AS nativePlace ,c.deptSuggest ,c.deptSuggestName ,c.trialPlanProcess ,c.personLevel ,c.personLevelId ,c.officeName ,c.baseScore ,c.allScore ,date_format(c.becomeDate , '%m') AS becomeMonth ,
		IF (
			c.deptNameT IS NULL
			OR c.deptNameT = '' ,
			c.deptNameS ,
			c.deptNameT
		) AS deptName ,
		IF (c.deptIdT IS NULL OR c.deptIdT = '' ,
			c.deptIdS ,
			c.deptIdT
		) AS deptId ,
		IF (
			CURRENT_DATE >= c.becomeDate ,
			0 ,
			round((UNIX_TIMESTAMP(c.becomeDate) - UNIX_TIMESTAMP(CURRENT_DATE)) / 86400 + 1)
		) AS lastBecomeDate ,
		IF (
			trialPlan IS NULL ,
			0 ,
			IF (
				CURRENT_DATE >= becomeDate ,
				100 ,
				round(((UNIX_TIMESTAMP(CURRENT_DATE) - UNIX_TIMESTAMP(entryDate)) / 86400 + 1) / ((UNIX_TIMESTAMP(becomeDate) - UNIX_TIMESTAMP(entryDate)) / 86400 + 1) * 100 ,2)
			)
		) AS trialPlanProcessC ,
		c.trialPlanProcess -
		IF (
			trialPlan IS NULL ,
			0 ,
			IF (CURRENT_DATE >= becomeDate ,100 ,round(((UNIX_TIMESTAMP(CURRENT_DATE) - UNIX_TIMESTAMP(entryDate)) / 86400 + 1) / ((UNIX_TIMESTAMP(becomeDate) - UNIX_TIMESTAMP(entryDate)) / 86400 + 1) * 100 ,2)
			)
		) AS diff ,
		t.id AS suggestId ,t.ExaDT ,t.ExaStatus
		from
			oa_hr_personnel c
		LEFT JOIN oa_hr_trialdeptsuggest t ON c.userAccount = t.userAccount
		WHERE 1 = 1 ",

	//人事档案列表
	"select_personnelList" => "SELECT c.id ,d.id AS recordsId ,c.userNo ,c.userAccount ,c.staffName ,c.userName ,c.englishName ,c.sex ,c.birthdate ,c.nativePlacePro ,c.age ,c.nativePlaceCity ,c.nation ,c.identityCard ,c.politicsStatus ,c.companyType ,c.companyName ,c.belongDeptCode ,c.belongDeptName ,c.belongDeptId ,c.highEducation ,c.highEducationName ,c.highSchool ,c.professionalName ,c.deptCode ,c.deptName ,c.deptId ,c.deptNameS ,c.deptCodeS ,c.deptIdS ,c.deptNameT ,c.deptCodeT ,c.deptIdT,c.deptNameF ,c.deptCodeF ,c.deptIdF ,c.jobId ,c.jobName ,c.regionId ,c.regionName ,c.employeesState ,c.employeesStateName ,c.staffState ,c.staffStateName ,c.personnelType ,c.personnelTypeName ,c.position ,c.positionName ,c.personnelClass ,c.functionName ,c.englishSkillName ,c.oftenCardNum ,c.oftenAccount ,c.oftenBank ,c.personnelClassName ,c.wageLevelCode ,c.wageLevelName ,c.jobLevel ,c.healthState ,c.isMedicalHistory ,c.medicalHistory ,c.InfectDiseases ,c.height ,c.weight ,c.blood ,c.maritalStatus ,c.maritalStatusName ,c.birthStatus ,c.hobby ,c.speciality ,c.professional ,c.openingBank ,c.bankCardNum ,c.accountNumb ,c.archivesCode ,c.archivesLocation ,c.householdType ,c.collectResidence ,c.residencePro ,c.residenceCity ,c.tutor ,c.tutorId ,c.telephone ,c.mobile ,c.personEmail ,e.EMAIL AS compEmail ,c.QQ ,c.MSN ,c.fetion ,c.information ,c.homePhone ,c.emergencyName ,c.emergencyRelation ,c.emergencyTel ,c.nowPlacePro ,c.nowPlaceCity ,c.nowAddress ,c.nowPost ,c.homeAddressPro ,c.homeAddressCity ,c.homeAddress ,c.homePost ,c.entryDate ,c.becomeDate ,c.realBecomeDate ,c.becomeFraction ,c.entryPlace ,c.quitDate ,c.quitTypeCode ,c.quitTypeName ,c.quitReson ,c.quitInterview ,c.remark ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime ,c.deleteName ,c.deleteNameId ,c.deieteTime ,c.deleteRemark ,c.personLevel ,c.personLevelId ,c.projectId ,c.projectCode ,c.projectName ,c.planEndDate ,c.taskName ,c.taskId ,c.taskPlanEnd ,c.officeId ,c.officeName ,c.ecountry ,c.ecountryId ,c.eprovinceId ,c.eprovince ,c.isNeedTutor ,c.ecityId ,concat(c.eprovince ,c.ecity) AS eprovinceCity ,c.ecity ,c.socialPlace ,c.socialBuyer ,c.fundPlace ,c.fundPlaceId ,c.fundBuyer ,c.fundCardinality ,c.fundProportion ,c.workBeginDate ,c.seniority ,c.companyYear ,c.deviceCode ,c.deviceName ,c.networkName ,c.networkCode ,c.technologyName ,c.technologyCode ,c.outsourcingSupp ,c.outsourcingName ,c.unitPhone ,c.extensionNum ,c.unitFax ,c.mobilePhone ,c.shortNum ,c.otherPhone ,c.otherPhoneNum ,c.graduateDate ,c.salaryAreaCode ,c.identityCardDate ,c.identityCardAddress ,c.isBack ,c.realReason
		from oa_hr_personnel c
		LEFT JOIN oa_hr_tutor_records d ON (c.userAccount = d.studentAccount)
		LEFT JOIN USER e ON (c.userAccount = e.USER_ID)
		WHERE 1 = 1 ",

	//人事档案，导出信息
	"select_personnelExport" => "select c.id ,d.id AS recordsId ,c.userNo ,c.userAccount ,c.staffName ,c.userName ,c.englishName ,c.sex ,c.birthdate ,c.nativePlacePro ,c.age ,c.nativePlaceCity ,c.nation ,c.identityCard ,c.politicsStatus ,c.companyType ,c.companyName ,c.belongDeptCode ,c.belongDeptName ,c.belongDeptId ,c.highEducation ,c.highEducationName ,c.highSchool ,c.professionalName ,c.deptCode ,c.deptName ,c.deptId ,c.deptNameS ,c.deptCodeS ,c.deptIdS ,c.deptNameT ,c.deptCodeT ,c.deptIdT ,c.deptNameF ,c.deptCodeF ,c.deptIdF ,c.jobId ,c.jobName ,c.regionId ,c.regionName ,c.employeesState ,c.employeesStateName ,c.staffState ,c.staffStateName ,c.personnelType ,c.personnelTypeName ,c.position ,c.positionName ,c.personnelClass ,c.functionName ,c.englishSkillName ,c.oftenCardNum ,c.oftenAccount ,c.oftenBank ,c.personnelClassName ,c.wageLevelCode ,c.wageLevelName ,c.jobLevel ,c.healthState ,c.isMedicalHistory ,c.medicalHistory ,c.InfectDiseases ,c.height ,c.weight ,c.blood ,c.maritalStatus ,c.maritalStatusName ,c.birthStatus ,c.hobby ,c.speciality ,c.professional ,c.openingBank ,c.bankCardNum ,c.accountNumb ,c.archivesCode ,c.archivesLocation ,c.householdType ,c.collectResidence ,c.residencePro ,c.residenceCity ,c.tutor ,c.tutorId ,c.telephone ,c.mobile ,c.personEmail ,e.EMAIL AS compEmail ,c.QQ ,c.MSN ,c.fetion ,c.information ,c.homePhone ,c.emergencyName ,c.emergencyRelation ,c.emergencyTel ,c.nowPlacePro ,c.nowPlaceCity ,c.nowAddress ,c.nowPost ,c.homeAddressPro ,c.homeAddressCity ,c.homeAddress ,c.homePost ,c.entryDate ,c.becomeDate ,c.realBecomeDate ,c.becomeFraction ,c.entryPlace ,c.quitDate ,c.quitTypeCode ,c.quitTypeName ,c.quitReson ,c.quitInterview ,c.remark ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime ,c.deleteName ,c.deleteNameId ,c.deieteTime ,c.deleteRemark ,c.personLevel ,c.personLevelId ,c.projectId ,c.projectCode ,c.projectName ,c.planEndDate ,c.taskName ,c.taskId ,c.taskPlanEnd ,c.officeId ,c.officeName ,c.ecountry ,c.ecountryId ,c.eprovinceId ,c.eprovince ,c.isNeedTutor ,c.ecityId ,concat(c.eprovince ,c.ecity) AS eprovinceCity ,c.ecity ,c.socialPlace ,c.socialBuyer ,c.fundPlace ,c.fundPlaceId ,c.fundBuyer ,c.fundCardinality ,c.fundProportion ,c.workBeginDate ,c.seniority ,c.companyYear ,c.deviceCode ,c.deviceName ,c.networkName ,c.networkCode ,c.technologyName ,c.technologyCode ,c.outsourcingSupp ,c.outsourcingName ,c.unitPhone ,c.extensionNum ,c.unitFax ,c.mobilePhone ,c.shortNum ,c.otherPhone ,c.otherPhoneNum ,c.graduateDate ,c.salaryAreaCode ,c.identityCardDate ,c.identityCardAddress
		FROM oa_hr_personnel c
		LEFT JOIN oa_hr_tutor_records d ON (c.userAccount = d.studentAccount)
		LEFT JOIN USER e ON (c.userAccount = e.USER_ID)
		WHERE 1 = 1 ",
	//人事管理，联系信息列表
	"select_contactInformation" => "select c.id ,c.userNo ,c.userAccount ,c.staffName ,c.userName ,c.englishName ,c.sex ,c.birthdate ,c.nativePlacePro ,c.age ,c.nativePlaceCity ,c.nation ,c.identityCard ,c.politicsStatus ,c.companyType ,c.companyName ,c.belongDeptCode ,c.belongDeptName ,c.belongDeptId ,c.highEducation ,c.highEducationName ,c.highSchool ,c.professionalName ,c.deptCode ,c.deptName ,c.deptId ,c.deptNameS ,c.deptCodeS ,c.deptIdS ,c.deptNameT ,c.deptCodeT ,c.deptIdT ,c.deptNameF ,c.deptCodeF ,c.deptIdF,c.jobId ,c.jobName ,c.regionId ,c.regionName ,c.employeesState ,c.employeesStateName ,c.staffState ,c.staffStateName ,c.personnelType ,c.personnelTypeName ,c.position ,c.positionName ,c.personnelClass ,c.functionName ,c.englishSkillName ,c.oftenCardNum ,c.oftenAccount ,c.oftenBank ,c.personnelClassName ,c.wageLevelCode ,c.wageLevelName ,c.jobLevel ,c.healthState ,c.isMedicalHistory ,c.medicalHistory ,c.InfectDiseases ,c.height ,c.weight ,c.blood ,c.maritalStatus ,c.maritalStatusName ,c.birthStatus ,c.hobby ,c.speciality ,c.professional ,c.openingBank ,c.bankCardNum ,c.accountNumb ,c.archivesCode ,c.archivesLocation ,c.householdType ,c.collectResidence ,c.residencePro ,c.residenceCity ,c.tutor ,c.tutorId ,c.telephone ,c.mobile ,c.personEmail ,e.EMAIL AS compEmail ,c.QQ ,c.MSN ,c.fetion ,c.information ,c.homePhone ,c.emergencyName ,c.emergencyRelation ,c.emergencyTel ,c.nowPlacePro ,c.nowPlaceCity ,c.nowAddress ,c.nowPost ,c.homeAddressPro ,c.homeAddressCity ,c.homeAddress ,c.homePost ,c.entryDate ,c.becomeDate ,c.realBecomeDate ,c.becomeFraction ,c.entryPlace ,c.quitDate ,c.quitTypeCode ,c.quitTypeName ,c.quitReson ,c.quitInterview ,c.remark ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime ,c.deleteName ,c.deleteNameId ,c.deieteTime ,c.deleteRemark ,c.personLevel ,c.personLevelId ,c.projectId ,c.projectCode ,c.projectName ,c.planEndDate ,c.taskName ,c.taskId ,c.taskPlanEnd ,c.officeId ,c.officeName ,c.ecountry ,c.ecountryId ,c.eprovinceId ,c.eprovince ,c.isNeedTutor ,c.ecityId ,concat(c.eprovince ,c.ecity) AS eprovinceCity ,c.ecity ,c.socialPlace ,c.socialBuyer ,c.fundPlace ,c.fundPlaceId ,c.fundBuyer ,c.fundCardinality ,c.fundProportion ,c.workBeginDate ,c.seniority ,c.companyYear ,c.deviceCode ,c.deviceName ,c.networkName ,c.networkCode ,c.technologyName ,c.technologyCode ,c.outsourcingSupp ,c.outsourcingName ,c.unitPhone ,c.extensionNum ,c.unitFax ,c.mobilePhone ,c.shortNum ,c.otherPhone ,c.otherPhoneNum
		 from oa_hr_personnel c
		LEFT JOIN USER e ON (c.userAccount = e.USER_ID)
		WHERE 1 = 1 ",
);
$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "ids",
		"sql" => " and c.id in(arr) "
	),
	array (
		"name" => "userNo",
		"sql" => " and c.userNo=# "
	),
	array (
		"name" => "userNoSearch",
		"sql" => " and c.userNo LIKE CONCAT('%',#,'%') "
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
		"sql" => " and c.userName LIKE CONCAT('%',#,'%')  "
	),
	array (
		"name" => "staffNameSearch",
		"sql" => " and c.staffName LIKE CONCAT('%',#,'%')  "
	),
	array (
		"name" => "englishName",
		"sql" => " and c.englishName=# "
	),
	array (
		"name" => "highEducation",
		"sql" => " and c.highEducation=# "
	),
	array (
		"name" => "highEducationName",
		"sql" => " and c.highEducationName LIKE CONCAT('%',#,'%')  "
	),
	array (
		"name" => "sex",
		"sql" => " and c.sex=# "
	),
	array (
		"name" => "birthdate",
		"sql" => " and c.birthdate=# "
	),
	array (
		"name" => "nativePlacePro",
		"sql" => " and c.nativePlacePro=# "
	),
	array (
		"name" => "nativePlaceProSearch",
		"sql" => " and c.nativePlacePro LIKE CONCAT('%',#,'%')  "
	),
	array (
		"name" => "highSchool",
		"sql" => " and c.highSchool LIKE CONCAT('%',#,'%')  "
	),
	array (
		"name" => "professionalName",
		"sql" => " and c.professionalName LIKE CONCAT('%',#,'%')  "
	),
	array (
		"name" => "functionName",
		"sql" => " and c.functionName LIKE CONCAT('%',#,'%')  "
	),
	array (
		"name" => "age",
		"sql" => " and c.age=# "
	),
	array (
		"name" => "nativePlaceCity",
		"sql" => " and c.nativePlaceCity=# "
	),
	array (
		"name" => "nativePlaceCitySearch",
		"sql" => " and c.nativePlaceCity LIKE CONCAT('%',#,'%')   "
	),
	array (
		"name" => "nation",
		"sql" => " and c.nation=# "
	),
	array (
		"name" => "identityCard",
		"sql" => " and c.identityCard=# "
	),
	array (
		"name" => "identityCardEq",
		"sql" => " and c.identityCard=# "
	),
	array (
		"name" => "politicsStatus",
		"sql" => " and c.politicsStatus=# "
	),
	array (
		"name" => "companyType",
		"sql" => " and c.companyType=# "
	),
	array (
		"name" => "companyName",
		"sql" => " and c.companyName=# "
	),
	array(
		"name" => "companyNameI",
		"sql" => " and c.companyName in(arr) "
	),
	array (
		"name" => "companyNameSearch",
		"sql" => " and c.companyName LIKE CONCAT('%',#,'%')  "
	),
	array (
		"name" => "deptNameSearch",
		"sql" => " and c.deptName LIKE CONCAT('%',#,'%') "
	),
	array (
		"name" => "deptNameSSearch",
		"sql" => " and c.deptNameS LIKE CONCAT('%',#,'%')  "
	),
	array (
		"name" => "deptNameS",
		"sql" => " and c.deptNameS=# "
	),
	array (
		"name" => "deptIdS",
		"sql" => " and c.deptIdS=# "
	),
	array (
		"name" => "deptNameT",
		"sql" => " and c.deptNameT=# "
	),
	array (
		"name" => "deptNameTSearch",
		"sql" => " and c.deptNameT LIKE CONCAT('%',#,'%')   "
	),
	array (
		"name" => "deptNameF",
		"sql" => " and c.deptNameF=# "
	),
	array (
		"name" => "deptIdF",
		"sql" => " and c.deptIdF=# "
	),
	array (
		"name" => "deptNameFSearch",
		"sql" => " and c.deptNameF LIKE CONCAT('%',#,'%')   "
	),
	array (
		"name" => "belongDeptId",
		"sql" => " and c.belongDeptId in(arr) "
	),
	array (
		"name" => "belongDeptName",
		"sql" => " and c.belongDeptName LIKE CONCAT('%',#,'%') "
	),
	array (
		"name" => "deptIdT",
		"sql" => " and c.deptIdT=# "
	),
	array (
		"name" => "deptId",
		"sql" => " and c.deptId=# "
	),
	array (
		"name" => "deptIdArr",
		"sql" => " and (c.deptIdT in(arr) or c.deptIdS in(arr)) "
	),
	array (
		"name" => "jobId",
		"sql" => " and c.jobId=# "
	),
	array (
		"name" => "jobName",
		"sql" => " and c.jobName=# "
	),
	array (
		"name" => "jobNameSearch",
		"sql" => " and c.jobName LIKE CONCAT('%',#,'%') "
	),
	array (
		"name" => "regionId",
		"sql" => " and c.regionId=# "
	),
	array (
		"name" => "regionName",
		"sql" => " and c.regionName=# "
	),
	array (
		"name" => "regionNameSearch",
		"sql" => " and c.regionName LIKE CONCAT('%',#,'%') "
	),
	array (
		"name" => "employeesState",
		"sql" => " and c.employeesState=# "
	),
	array (
		"name" => "staffState",
		"sql" => " and c.staffState=# "
	),
	array (
		"name" => "employeesStateNameSearch",
		"sql" => " and c.employeesStateName LIKE CONCAT('%',#,'%')  "
	),
	array (
		"name" => "employeesStateName",
		"sql" => " and c.employeesStateName=# "
	),
	array (
		"name" => "staffStateName",
		"sql" => " and c.staffStateName=# "
	),
	array (
		"name" => "personnelType",
		"sql" => " and c.personnelType=# "
	),
	array (
		"name" => "personnelTypeName",
		"sql" => " and c.personnelTypeName=# "
	),
	array (
		"name" => "personnelTypeNameSearch",
		"sql" => " and c.personnelTypeName LIKE CONCAT('%',#,'%') "
	),
	array (
		"name" => "position",
		"sql" => " and c.position=# "
	),
	array (
		"name" => "positionName",
		"sql" => " and c.positionName=# "
	),
	array (
		"name" => "positionNameSearch",
		"sql" => " and c.positionName LIKE CONCAT('%',#,'%') "
	),
	array (
		"name" => "personnelClass",
		"sql" => " and c.personnelClass=# "
	),
	array (
		"name" => "personnelClassName",
		"sql" => " and c.personnelClassName=# "
	),
	array (
		"name" => "personnelClassNameSearch",
		"sql" => " and c.personnelClassName LIKE CONCAT('%',#,'%')  "
	),
	array (
		"name" => "wageLevelCode",
		"sql" => " and c.wageLevelCode=# "
	),
	array (
		"name" => "wageLevelName",
		"sql" => " and c.wageLevelName=# "
	),
	array (
		"name" => "jobLevel",
		"sql" => " and c.jobLevel=# "
	),
	array (
		"name" => "jobLevelSearch",
		"sql" => " and c.jobLevel LIKE CONCAT('%',#,'%') "
	),
	array (
		"name" => "healthState",
		"sql" => " and c.healthState=# "
	),
	array (
		"name" => "isMedicalHistory",
		"sql" => " and c.isMedicalHistory=# "
	),
	array (
		"name" => "medicalHistory",
		"sql" => " and c.medicalHistory=# "
	),
	array (
		"name" => "InfectDiseases",
		"sql" => " and c.InfectDiseases=# "
	),
	array (
		"name" => "height",
		"sql" => " and c.height=# "
	),
	array (
		"name" => "weight",
		"sql" => " and c.weight=# "
	),
	array (
		"name" => "blood",
		"sql" => " and c.blood=# "
	),
	array (
		"name" => "maritalStatus",
		"sql" => " and c.maritalStatus=# "
	),
	array (
		"name" => "maritalStatusName",
		"sql" => " and c.maritalStatusName=# "
	),
	array (
		"name" => "birthStatus",
		"sql" => " and c.birthStatus=# "
	),
	array (
		"name" => "hobby",
		"sql" => " and c.hobby=# "
	),
	array (
		"name" => "speciality",
		"sql" => " and c.speciality=# "
	),
	array (
		"name" => "professional",
		"sql" => " and c.professional=# "
	),
	array (
		"name" => "openingBank",
		"sql" => " and c.openingBank=# "
	),
	array (
		"name" => "bankCardNum",
		"sql" => " and c.bankCardNum=# "
	),
	array (
		"name" => "accountNumb",
		"sql" => " and c.accountNumb=# "
	),
	array (
		"name" => "archivesCode",
		"sql" => " and c.archivesCode=# "
	),
	array (
		"name" => "archivesLocation",
		"sql" => " and c.archivesLocation=# "
	),
	array (
		"name" => "householdType",
		"sql" => " and c.householdType=# "
	),
	array (
		"name" => "collectResidence",
		"sql" => " and c.collectResidence=# "
	),
	array (
		"name" => "residencePro",
		"sql" => " and c.residencePro=# "
	),
	array (
		"name" => "residenceCity",
		"sql" => " and c.residenceCity=# "
	),
	array (
		"name" => "tutor",
		"sql" => " and c.tutor=# "
	),
	array (
		"name" => "tutorId",
		"sql" => " and c.tutorId=# "
	),
	array (
		"name" => "telephone",
		"sql" => " and c.telephone=# "
	),
	array (
		"name" => "mobile",
		"sql" => " and c.mobile=# "
	),
	array (
		"name" => "mobileSearch",
		"sql" => " and c.mobile LIKE CONCAT('%',#,'%') "
	),
	array (
		"name" => "personEmail",
		"sql" => " and c.personEmail LIKE CONCAT('%',#,'%')  "
	),
	array (
		"name" => "compEmail",
		"sql" => " and c.compEmail LIKE CONCAT('%',#,'%')  "
	),
	array (
		"name" => "compEmailA",
		"sql" => " and e.EMAIL LIKE CONCAT('%',#,'%')  "
	),
	array (
		"name" => "QQ",
		"sql" => " and c.QQ=# "
	),
	array (
		"name" => "MSN",
		"sql" => " and c.MSN=# "
	),
	array (
		"name" => "fetion",
		"sql" => " and c.fetion=# "
	),
	array (
		"name" => "information",
		"sql" => " and c.information=# "
	),
	array (
		"name" => "homePhone",
		"sql" => " and c.homePhone=# "
	),
	array (
		"name" => "emergencyName",
		"sql" => " and c.emergencyName=# "
	),
	array (
		"name" => "emergencyRelation",
		"sql" => " and c.emergencyRelation=# "
	),
	array (
		"name" => "emergencyTel",
		"sql" => " and c.emergencyTel=# "
	),
	array (
		"name" => "nowPlacePro",
		"sql" => " and c.nowPlacePro=# "
	),
	array (
		"name" => "nowPlaceCity",
		"sql" => " and c.nowPlaceCity=# "
	),
	array (
		"name" => "nowAddress",
		"sql" => " and c.nowAddress=# "
	),
	array (
		"name" => "nowPost",
		"sql" => " and c.nowPost=# "
	),
	array (
		"name" => "homeAddressPro",
		"sql" => " and c.homeAddressPro=# "
	),
	array (
		"name" => "homeAddressCity",
		"sql" => " and c.homeAddressCity=# "
	),
	array (
		"name" => "homeAddress",
		"sql" => " and c.homeAddress=# "
	),
	array (
		"name" => "homePost",
		"sql" => " and c.homePost=# "
	),
	array (
		"name" => "entryDate",
		"sql" => " and c.entryDate=# "
	),
	array (
		"name" => "entryDateSearch",
		"sql" => " and c.entryDate LIKE BINARY CONCAT('%',#,'%')"
	),
	array (
		"name" => "entryDateBegin",
		"sql" => " and c.entryDate >=# "
	),
	array (
		"name" => "entryDateEnd",
		"sql" => " and c.entryDate <=# "
	),
	array (
		"name" => "becomeDate",
		"sql" => " and c.becomeDate=# "
	),
	array (
		"name" => "becomeDateSearch",
		"sql" => " and c.becomeDate LIKE BINARY CONCAT('%',#,'%') "
	),
	array (
		"name" => "becomeDateBegin",
		"sql" => " and c.becomeDate >=# "
	),
	array (
		"name" => "becomeDateEnd",
		"sql" => " and c.becomeDate <=# "
	),
	array (
		"name" => "becomeMonth",
		"sql" => " and date_format(c.becomeDate ,'%m') LIKE BINARY CONCAT('%',#,'%') "
	),
	array (
		"name" => "becomeFraction",
		"sql" => " and c.becomeFraction=# "
	),
	array (
		"name" => "entryPlace",
		"sql" => " and c.entryPlace=# "
	),
	array (
		"name" => "quitDate",
		"sql" => " and c.quitDate=# "
	),
	array (
		"name" => "quitTypeCode",
		"sql" => " and c.quitTypeCode=# "
	),
	array (
		"name" => "quitTypeName",
		"sql" => " and c.quitTypeName=# "
	),
	array (
		"name" => "quitReson",
		"sql" => " and c.quitReson=# "
	),
	array (
		"name" => "quitInterview",
		"sql" => " and c.quitInterview=# "
	),
	array (
		"name" => "remark",
		"sql" => " and c.remark=# "
	),
	array (
		"name" => "createName",
		"sql" => " and c.createName=# "
	),
	array (
		"name" => "createNameId",
		"sql" => " and c.createNameId=# "
	),
	array (
		"name" => "createTime",
		"sql" => " and c.createTime=# "
	),
	array (
		"name" => "editName",
		"sql" => " and c.editName=# "
	),
	array (
		"name" => "editNameId",
		"sql" => " and c.editNameId=# "
	),
	array (
		"name" => "editTime",
		"sql" => " and c.editTime=# "
	),
	array (
		"name" => "deleteName",
		"sql" => " and c.deleteName=# "
	),
	array (
		"name" => "deleteNameId",
		"sql" => " and c.deleteNameId=# "
	),
	array (
		"name" => "deieteTime",
		"sql" => " and c.deieteTime=# "
	),
	array (
		"name" => "deleteRemark",
		"sql" => " and c.deleteRemark=# "
	),
	array (
		"name" => "officeId",
		"sql" => " and c.officeId=# "
	),
	array (
		"name" => "officeName",
		"sql" => " and c.officeName LIKE CONCAT('%',#,'%') "
	),
	array (
		"name" => "personLevel",
		"sql" => " and c.personLevel=# "
	),
	array (
		"name" => "personLevelSearch",
		"sql" => " and c.personLevel LIKE CONCAT('%',#,'%') "
	),
	array (
		"name" => "personLevelId",
		"sql" => " and c.personLevelId=# "
	),
	array (
		"name" => "projectId",
		"sql" => " and c.projectId=# "
	),
	array (
		"name" => "projectCodeSearch",
		"sql" => " and c.projectCode LIKE CONCAT('%',#,'%') "
	),
	array (
		"name" => "projectCode",
		"sql" => " and c.projectCode=# "
	),
	array (
		"name" => "projectName",
		"sql" => " and c.projectName=# "
	),
	array (
		"name" => "projectNameSearch",
		"sql" => " and c.projectName LIKE CONCAT('%',#,'%')"
	),
	array (
		"name" => "planEndDate",
		"sql" => " and c.planEndDate=# "
	),
	array (
		"name" => "taskName",
		"sql" => " and c.taskName=# "
	),
	array (
		"name" => "taskNameSearch",
		"sql" => " and c.taskName LIKE CONCAT('%',#,'%')"
	),
	array (
		"name" => "taskId",
		"sql" => " and c.taskId=# "
	),
	array (
		"name" => "taskPlanEnd",
		"sql" => " and c.taskPlanEnd=# "
	),
	array (
		"name" => "provinceCity",
		"sql" => " and (c.eprovince LIKE CONCAT('%',#,'%') or c.ecity LIKE CONCAT('%',#,'%') ) "
	),
	array(
		"name" => "deptSuggest",
		"sql" => " and c.deptSuggest LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "deptIDs",
		"sql" => " and c.deptIdS=# or c.deptIdT=# "
	),
	array(
		"name" => "tExaStatus",
		"sql" => " and t.ExaStatus=# "
	),
	array(
		"name" => "eprovinceCity",
		"sql" => " and CONCAT(c.eprovince,c.ecity) =# "
	),
	array(
		"name" => "school",
		"sql" => "and c.userNo in(select userNo from oa_hr_personnel_education where organization LIKE CONCAT('%',#,'%'))"
	),
	array(
		"name" => "content",
		"sql" => "and c.userNo in(select userNo from oa_hr_personnel_education where content LIKE CONCAT('%',#,'%'))"
	),
	array(
		"name" => "company",
		"sql" => "and c.userNo in(select userNo from oa_hr_personnel_work where company LIKE CONCAT('%',#,'%'))"
	),
	array(
		"name" => "workjob",
		"sql" => "and c.userNo in(select userNo from oa_hr_personnel_work where position LIKE CONCAT('%',#,'%'))"
	),
	array(
		"name" => "responsibilities",
		"sql" => "and c.userNo in(select userNo from oa_hr_personnel_work where responsibilities LIKE CONCAT('%',#,'%'))"
	),
	array(
		"name" => "certificates",
		"sql" => "and c.userNo in(select userNo from oa_hr_personnel_certificate where certificates LIKE CONCAT('%',#,'%'))"
	),
	array(
		"name" => "certifying",
		"sql" => "and c.userNo in(select userNo from oa_hr_personnel_certificate where certifying LIKE CONCAT('%',#,'%'))"
	),
	array(
		"name" => "certifyingDate",
		"sql" => "and c.userNo in(select userNo from oa_hr_personnel_certificate where certifyingDate LIKE CONCAT('%',#,'%'))"
	),
	array(
		"name" => "searchProjectName",
		"sql" => "and c.userNo in(select userNo from oa_hr_personnel_project where projectName LIKE CONCAT('%',#,'%'))"
	),
	array(
		"name" => "projectContent",
		"sql" => "and c.userNo in(select userNo from oa_hr_personnel_project where projectContent LIKE CONCAT('%',#,'%'))"
	),
	array(
		"name" => "beginDate",
		"sql" => "and c.userNo in(select userNo from oa_hr_personnel_project where beginDate LIKE CONCAT('%',#,'%'))"
	),
	array(
		"name" => "closeDate",
		"sql" => "and c.userNo in(select userNo from oa_hr_personnel_project where closeDate LIKE CONCAT('%',#,'%'))"
	),
	array(
		"name" => "tutorState",
		"sql" => "and (if(#=1,c.isNeedTutor=1,if(#=2,((c.tutorId is null or c.tutorId='')  and (c.isNeedTutor is null or c.isNeedTutor='')),(c.tutorId is not null and (c.isNeedTutor is null or c.isNeedTutor=''))) ) ) "
	),
	array(
		"name" => "personnelTutorState",
		"sql" => "and (if(#=1,c.isNeedTutor=1,if(#=2,((d.id is null or d.id='')  and (c.isNeedTutor is null or c.isNeedTutor='')),(d.id is not null and (c.isNeedTutor is null or c.isNeedTutor=''))) ) ) "
	),
	array (
		"name" => "socialPlace",
		"sql" => " and c.socialPlace LIKE CONCAT('%',#,'%')"
	),
	array(
		"name" => "deviceCode",
		"sql" => " and c.deviceCode=# "
	),
	array(
		"name" => "deviceName",
		"sql" => " and c.deviceName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "networkName",
		"sql" => " and c.networkName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "networkCode",
		"sql" => " and c.networkCode=# "
	),
	array(
		"name" => "technologyName",
		"sql" => " and c.technologyName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "outsourcingSupp",
		"sql" => " and c.outsourcingSupp LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "outsourcingName",
		"sql" => " and c.outsourcingName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "technologyCode",
		"sql" => " and c.technologyCode=# "
	),
	array (
		"name" => "entryDates",
		"sql" => " and c.entryDate <= BINARY # "
	),
	array(
		"name" => "trialPlan",
		"sql" => " and c.trialPlan LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "createDateBegin",
		"sql" => " and date_format(c.createTime,'%Y-%m-%d')>= BINARY # "
	),
	array(
		"name" => "createDateEnd",
		"sql" => " and date_format(c.createTime,'%Y-%m-%d')<= BINARY # "
	)
)
?>