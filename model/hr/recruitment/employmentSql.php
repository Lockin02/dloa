<?php
/**
 * @author Administrator
 * @Date 2012-07-18 19:15:30
 * @version 1.0
 * @description:职位申请表 sql配置文件
 */
$sql_arr = array (
	"select_default"=>"select c.id ,c.employmentCode,c.resumeId ,c.userNo ,c.userAccount ,c.userName ,c.name ,c.sex ,c.nativePlacePro ,c.nativePlaceCity ,c.nation ,c.height ,c.weight ,c.blood ,c.maritalStatus ,c.maritalStatusName ,c.identityCard ,c.age ,c.birthdate ,c.nowPlacePro ,c.nowPlaceCity ,c.nowAddress ,c.nowPost ,c.homeAddressPro ,c.homeAddressCity ,c.homeAddress ,c.homePost ,c.politicsStatusCode ,c.politicsStatus ,c.companyType ,c.companyName ,c.deptCodeS ,c.deptNameS ,c.deptIdS ,c.deptNameT ,c.deptCodeT ,c.deptIdT ,c.jobId ,c.jobName ,c.regionId ,c.regionName ,c.employeesState ,c.employeesStateName ,c.personnelType ,c.personnelTypeName ,c.position ,c.positionName ,c.personnelClass ,c.personnelClassName ,c.wageLevelCode ,c.wageLevelName ,c.jobLevel ,c.healthStateCode ,c.healthState ,c.isMedicalHistory ,c.medicalHistory ,c.InfectDiseases ,c.birthStatus ,c.hobby ,c.speciality ,c.professional ,c.englishSkillName ,c.englishSkill ,c.openingBank ,c.bankCardNum ,c.accountNumb ,c.archivesCode ,c.archivesLocation ,c.householdType ,c.collectResidence ,c.residencePro ,c.residenceCity ,c.tutor ,c.tutorId ,c.highEducationName ,c.highEducation ,c.highSchool ,c.professionalName ,c.telephone ,c.mobile ,c.personEmail ,c.compEmail ,c.QQ ,c.MSN ,c.fetion ,c.information ,c.homePhone ,c.emergencyName ,c.emergencyRelation ,c.emergencyTel ,c.entryDate ,c.becomeDate ,c.becomeFraction ,c.entryPlace ,c.quitDate ,c.quitTypeCode ,c.quitTypeName ,c.quitReson ,c.quitInterview ,c.remark ,c.deleteName ,c.deleteNameId ,c.deieteTime ,c.deleteRemark ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime ,c.appointPro ,c.appointCity ,c.appointAddress ,c.appointPost ,c.graduateDate ,c.identityCardDate ,c.identityCardAddress from oa_hr_recruitment_employment c where 1=1 "
);

$condition_arr = array (
	array(
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array(
		"name" => "resumeId",
		"sql" => " and c.resumeId=# "
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
		"sql" => " and c.userName=# "
	),
	array(
		"name" => "employmentCode",
		"sql" => " and c.employmentCode LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "name",
		"sql" => " and c.name LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "sex",
		"sql" => " and c.sex=# "
	),
	array(
		"name" => "nativePlacePro",
		"sql" => " and c.nativePlacePro=# "
	),
	array(
		"name" => "nativePlaceCity",
		"sql" => " and c.nativePlaceCity=# "
	),
	array(
		"name" => "nation",
		"sql" => " and c.nation LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "height",
		"sql" => " and c.height=# "
	),
	array(
		"name" => "weight",
		"sql" => " and c.weight=# "
	),
	array(
		"name" => "blood",
		"sql" => " and c.blood=# "
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
		"name" => "identityCard",
		"sql" => " and c.identityCard=# "
	),
	array(
		"name" => "age",
		"sql" => " and c.age LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "birthdate",
		"sql" => " and c.birthdate=# "
	),
	array(
		"name" => "nowPlacePro",
		"sql" => " and c.nowPlacePro=# "
	),
	array(
		"name" => "nowPlaceCity",
		"sql" => " and c.nowPlaceCity=# "
	),
	array(
		"name" => "nowAddress",
		"sql" => " and c.nowAddress=# "
	),
	array(
		"name" => "nowPost",
		"sql" => " and c.nowPost=# "
	),
	array(
		"name" => "homeAddressPro",
		"sql" => " and c.homeAddressPro=# "
	),
	array(
		"name" => "homeAddressCity",
		"sql" => " and c.homeAddressCity=# "
	),
	array(
		"name" => "homeAddress",
		"sql" => " and c.homeAddress=# "
	),
	array(
		"name" => "homePost",
		"sql" => " and c.homePost=# "
	),
	array(
		"name" => "politicsStatusCode",
		"sql" => " and c.politicsStatusCode=# "
	),
	array(
		"name" => "politicsStatus",
		"sql" => " and c.politicsStatus=# "
	),
	array(
		"name" => "companyType",
		"sql" => " and c.companyType=# "
	),
	array(
		"name" => "companyName",
		"sql" => " and c.companyName=# "
	),
	array(
		"name" => "deptCodeS",
		"sql" => " and c.deptCodeS=# "
	),
	array(
		"name" => "deptNameS",
		"sql" => " and c.deptNameS=# "
	),
	array(
		"name" => "deptIdS",
		"sql" => " and c.deptIdS=# "
	),
	array(
		"name" => "deptNameT",
		"sql" => " and c.deptNameT=# "
	),
	array(
		"name" => "deptCodeT",
		"sql" => " and c.deptCodeT=# "
	),
	array(
		"name" => "deptIdT",
		"sql" => " and c.deptIdT=# "
	),
	array(
		"name" => "jobId",
		"sql" => " and c.jobId=# "
	),
	array(
		"name" => "jobName",
		"sql" => " and c.jobName=# "
	),
	array(
		"name" => "regionId",
		"sql" => " and c.regionId=# "
	),
	array(
		"name" => "regionName",
		"sql" => " and c.regionName=# "
	),
	array(
		"name" => "employeesState",
		"sql" => " and c.employeesState=# "
	),
	array(
		"name" => "employeesStateName",
		"sql" => " and c.employeesStateName=# "
	),
	array(
		"name" => "personnelType",
		"sql" => " and c.personnelType=# "
	),
	array(
		"name" => "personnelTypeName",
		"sql" => " and c.personnelTypeName=# "
	),
	array(
		"name" => "position",
		"sql" => " and c.position=# "
	),
	array(
		"name" => "positionName",
		"sql" => " and c.positionName=# "
	),
	array(
		"name" => "personnelClass",
		"sql" => " and c.personnelClass=# "
	),
	array(
		"name" => "personnelClassName",
		"sql" => " and c.personnelClassName=# "
	),
	array(
		"name" => "wageLevelCode",
		"sql" => " and c.wageLevelCode=# "
	),
	array(
		"name" => "wageLevelName",
		"sql" => " and c.wageLevelName=# "
	),
	array(
		"name" => "jobLevel",
		"sql" => " and c.jobLevel=# "
	),
	array(
		"name" => "healthStateCode",
		"sql" => " and c.healthStateCode=# "
	),
	array(
		"name" => "healthState",
		"sql" => " and c.healthState=# "
	),
	array(
		"name" => "isMedicalHistory",
		"sql" => " and c.isMedicalHistory=# "
	),
	array(
		"name" => "medicalHistory",
		"sql" => " and c.medicalHistory=# "
	),
	array(
		"name" => "InfectDiseases",
		"sql" => " and c.InfectDiseases=# "
	),
	array(
		"name" => "birthStatus",
		"sql" => " and c.birthStatus=# "
	),
	array(
		"name" => "hobby",
		"sql" => " and c.hobby=# "
	),
	array(
		"name" => "speciality",
		"sql" => " and c.speciality=# "
	),
	array(
		"name" => "professional",
		"sql" => " and c.professional=# "
	),
	array(
		"name" => "englishSkillName",
		"sql" => " and c.englishSkillName=# "
	),
	array(
		"name" => "englishSkill",
		"sql" => " and c.englishSkill=# "
	),
	array(
		"name" => "openingBank",
		"sql" => " and c.openingBank=# "
	),
	array(
		"name" => "bankCardNum",
		"sql" => " and c.bankCardNum=# "
	),
	array(
		"name" => "accountNumb",
		"sql" => " and c.accountNumb=# "
	),
	array(
		"name" => "archivesCode",
		"sql" => " and c.archivesCode=# "
	),
	array(
		"name" => "archivesLocation",
		"sql" => " and c.archivesLocation=# "
	),
	array(
		"name" => "householdType",
		"sql" => " and c.householdType=# "
	),
	array(
		"name" => "collectResidence",
		"sql" => " and c.collectResidence=# "
	),
	array(
		"name" => "residencePro",
		"sql" => " and c.residencePro=# "
	),
	array(
		"name" => "residenceCity",
		"sql" => " and c.residenceCity=# "
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
		"name" => "highEducationName",
		"sql" => " and c.highEducationName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "highEducation",
		"sql" => " and c.highEducation=# "
	),
	array(
		"name" => "highSchool",
		"sql" => " and c.highSchool=# "
	),
	array(
		"name" => "professionalName",
		"sql" => " and c.professionalName=# "
	),
	array(
		"name" => "telephone",
		"sql" => " and c.telephone LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "mobile",
		"sql" => " and c.mobile LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "personEmail",
		"sql" => " and c.personEmail=# "
	),
	array(
		"name" => "compEmail",
		"sql" => " and c.compEmail=# "
	),
	array(
		"name" => "QQ",
		"sql" => " and c.QQ LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "MSN",
		"sql" => " and c.MSN=# "
	),
	array(
		"name" => "fetion",
		"sql" => " and c.fetion=# "
	),
	array(
		"name" => "information",
		"sql" => " and c.information=# "
	),
	array(
		"name" => "homePhone",
		"sql" => " and c.homePhone=# "
	),
	array(
		"name" => "emergencyName",
		"sql" => " and c.emergencyName=# "
	),
	array(
		"name" => "emergencyRelation",
		"sql" => " and c.emergencyRelation=# "
	),
	array(
		"name" => "emergencyTel",
		"sql" => " and c.emergencyTel=# "
	),
	array(
		"name" => "entryDate",
		"sql" => " and c.entryDate=# "
	),
	array(
		"name" => "becomeDate",
		"sql" => " and c.becomeDate=# "
	),
	array(
		"name" => "becomeFraction",
		"sql" => " and c.becomeFraction=# "
	),
	array(
		"name" => "entryPlace",
		"sql" => " and c.entryPlace=# "
	),
	array(
		"name" => "quitDate",
		"sql" => " and c.quitDate=# "
	),
	array(
		"name" => "quitTypeCode",
		"sql" => " and c.quitTypeCode=# "
	),
	array(
		"name" => "quitTypeName",
		"sql" => " and c.quitTypeName=# "
	),
	array(
		"name" => "quitReson",
		"sql" => " and c.quitReson=# "
	),
	array(
		"name" => "quitInterview",
		"sql" => " and c.quitInterview=# "
	),
	array(
		"name" => "remark",
		"sql" => " and c.remark=# "
	),
	array(
		"name" => "deleteName",
		"sql" => " and c.deleteName=# "
	),
	array(
		"name" => "deleteNameId",
		"sql" => " and c.deleteNameId=# "
	),
	array(
		"name" => "deieteTime",
		"sql" => " and c.deieteTime=# "
	),
	array(
		"name" => "deleteRemark",
		"sql" => " and c.deleteRemark=# "
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
	)
)
?>