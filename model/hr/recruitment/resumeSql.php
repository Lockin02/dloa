<?php
/**
 * @author Administrator
 * @Date 2012-07-12 09:50:10
 * @version 1.0
 * @description:简历管理 sql配置文件
 */
$sql_arr = array (
	"select_default"=>"select c.id ,c.resumeCode ,c.college,c.major,c.graduateDate,c.resumeType,c.applicantName ,c.planCode ,c.planId ,c.isInform ,c.sex ,c.birthdate ,c.workSeniority ,c.education ,c.post ,c.postName ,c.sourceA ,c.sourceAName ,c.sourceB ,c.politics ,c.marital ,c.phone ,c.email ,c.wishWork ,c.wishSalary ,c.wishAdress ,c.wishTrade ,c.languageGrade ,c.languageGradeName ,c.computerGrade ,c.computerGradeName ,c.selfAssessment ,c.presentAddress ,c.specialty ,c.workExp ,c.educationExp ,c.remark ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime ,c.sysCompanyName ,c.sysCompanyId ,c.talentPoolA ,c.talentPoolB ,c.talentPoolC ,c.talentPoolD ,c.talentPoolE ,c.reserveA ,c.reserveB ,c.reserveC ,c.reserveD ,c.reserveE ,c.prevCompany ,c.hillockDate ,c.language
	from oa_hr_recruitment_resume c where 1=1 "
);

$condition_arr = array (
	array(
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array(
		"name" => "resumeTypeArr",
		"sql" => " and c.resumeType in(arr)"
	),
	array(
		"name" => "resumeType",
		"sql" => " and c.resumeType=# "
	),
	array(
		"name" => "resumeCode",
		"sql" => " and c.resumeCode LIKE CONCAT('%',#,'%')"
	),
	array(
		"name" => "applicantName",
		"sql" => " and c.applicantName LIKE CONCAT('%',#,'%')"
	),
	array(
		"name" => "college",
		"sql" => " and c.college LIKE CONCAT('%',#,'%')"
	),
	array(
		"name" => "hillockDate",
		"sql" => " and c.hillockDate LIKE BINARY CONCAT('%',#,'%')"
	),
	array(
		"name" => "graduateDate",
		"sql" => " and c.graduateDate LIKE BINARY CONCAT('%',#,'%')"
	),
	array(
		"name" => "planCode",
		"sql" => " and c.planCode=# "
	),
	array(
		"name" => "planId",
		"sql" => " and c.planId=# "
	),
	array(
		"name" => "isInform",
		"sql" => " and c.isInform=# "
	),
	array(
		"name" => "sex",
		"sql" => " and c.sex=# "
	),
	array(
		"name" => "prevCompany",
		"sql" => " and c.prevCompany LIKE CONCAT('%',#,'%')"
	),
	array(
		"name" => "birthdate",
		"sql" => " and c.birthdate LIKE BINARY CONCAT('%',#,'%')"
	),
	array(
		"name" => "workSeniority",
		"sql" => " and c.workSeniority LIKE CONCAT('%',#,'%')"
	),
	array(
		"name" => "education",
		"sql" => " and c.education LIKE CONCAT('%',#,'%')"
	),
	array(
		"name" => "major",
		"sql" => " and c.major LIKE CONCAT('%',#,'%')"
	),
	array(
		"name" => "post",
		"sql" => " and c.postName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "post_d",
		"sql" => " and c.post LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "language",
		"sql" => " and c.languageName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "postName",
		"sql" => " and c.postName=# "
	),
	array(
		"name" => "sourceA",
		"sql" => " and c.sourceAName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "sourceA_d",
		"sql" => " and c.sourceA LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "sourceAName",
		"sql" => " and c.sourceAName=# "
	),
	array(
		"name" => "sourceB",
		"sql" => " and c.sourceB LIKE CONCAT('%',#,'%')"
	),
	array(
		"name" => "politics",
		"sql" => " and c.politics LIKE CONCAT('%',#,'%')"
	),
	array(
		"name" => "marital",
		"sql" => " and c.marital LIKE CONCAT('%',#,'%')"
	),
	array(
		"name" => "phone",
		"sql" => " and c.phone LIKE CONCAT('%',#,'%')"
	),
	array(
		"name" => "email",
		"sql" => " and c.email LIKE CONCAT('%',#,'%')"
	),
	array(
		"name" => "wishWork",
		"sql" => " and c.wishWork LIKE CONCAT('%',#,'%')"
	),
	array(
		"name" => "wishSalary",
		"sql" => " and c.wishSalary LIKE CONCAT('%',#,'%')"
	),
	array(
		"name" => "wishAdress",
		"sql" => " and c.wishAdress LIKE CONCAT('%',#,'%')"
	),
	array(
		"name" => "wishTrade",
		"sql" => " and c.wishTrade LIKE CONCAT('%',#,'%')"
	),
	array(
		"name" => "languageGrade",
		"sql" => " and c.languageGradeName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "languageGrade_d",
		"sql" => " and c.languageGrade LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "languageGradeName",
		"sql" => " and c.languageGradeName=# "
	),
	array(
		"name" => "computerGrade",
		"sql" => " and c.computerGradeName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "computerGrade_d",
		"sql" => " and c.computerGrade LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "computerGradeName",
		"sql" => " and c.computerGradeName=# "
	),
	array(
		"name" => "selfAssessment",
		"sql" => " and c.selfAssessment LIKE CONCAT('%',#,'%')"
	),
	array(
		"name" => "presentAddress",
		"sql" => " and c.presentAddress LIKE CONCAT('%',#,'%')"
	),
	array(
		"name" => "specialty",
		"sql" => " and c.specialty LIKE CONCAT('%',#,'%')"
	),
	array(
		"name" => "workExp",
		"sql" => " and c.workExp LIKE CONCAT('%',#,'%')"
	),
	array(
		"name" => "educationExp",
		"sql" => " and c.educationExp LIKE CONCAT('%',#,'%')"
	),
	array(
		"name" => "remark",
		"sql" => " and c.remark LIKE CONCAT('%',#,'%')"
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
		"name" => "talentPoolA",
		"sql" => " and c.talentPoolA=# "
	),
	array(
		"name" => "talentPoolB",
		"sql" => " and c.talentPoolB=# "
	),
	array(
		"name" => "talentPoolC",
		"sql" => " and c.talentPoolC=# "
	),
	array(
		"name" => "talentPoolD",
		"sql" => " and c.talentPoolD=# "
	),
	array(
		"name" => "talentPoolE",
		"sql" => " and c.talentPoolE=# "
	),
	array(
		"name" => "reserveA",
		"sql" => " and c.reserveA LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "reserveB",
		"sql" => " and c.reserveB=# "
	),
	array(
		"name" => "reserveC",
		"sql" => " and c.reserveC=# "
	),
	array(
		"name" => "reserveD",
		"sql" => " and c.reserveD=# "
	),
	array(
		"name" => "reserveE",
		"sql" => " and c.reserveE=# "
	),
	array(
		"name" => "myjoinId",
		"sql" => " and c.id in (select resumeId from oa_hr_apply_resume where parentId=#) "
	),
	array(
		"name" => "myinnerId",
		"sql" => " and c.id in (select resumeId from oa_hr_recommend_resume where parentId=#) "
	),
	array(
		"name" => "resumeType_d",
		"sql" => " and c.resumeType not in(#)"
	)
)
?>