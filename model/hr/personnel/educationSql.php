<?php
/**
 * @author Administrator
 * @Date 2012年5月25日 星期五 15:11:48
 * @version 1.0
 * @description:人事管理-基础信息-教育经历 sql配置文件
 */
$sql_arr = array (
	"select_default"=>"select c.id ,c.userNo ,c.userAccount ,c.userName ,c.organization ,c.content ,c.education ,c.educationName ,c.certificate ,c.beginDate ,c.closeDate ,c.remark ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime ,p.staffName ,p.highSchool ,p.professionalName ,p.companyType ,p.companyName ,p.deptName ,p.deptNameS ,p.deptNameT ,p.deptNameF ,p.personnelTypeName ,p.wageLevelName ,p.jobLevel
	from oa_hr_personnel_education c
	left join oa_hr_personnel p on c.userNo=p.userNo
	where 1=1 "
);

$condition_arr = array (
	array(
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array(
		"name" => "userNo",
		"sql" => " and c.userNo=# "
	),
	array(
		"name" => "userNoArr",
		"sql" => " and c.userNo in(arr) "
	),
	array(
		"name" => "userNoSearch",
		"sql" => " and c.userNo LIKE CONCAT('%',#,'%') "
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
		"name" => "userNameSearch",
		"sql" => " and c.userName LIKE CONCAT('%',#,'%')  "
	),
	array(
		"name" => "organization",
		"sql" => " and c.organization=# "
	),
	array(
		"name" => "organizationSearch",
		"sql" => " and c.organization LIKE CONCAT('%',#,'%')  "
	),
	array(
		"name" => "content",
		"sql" => " and c.content=# "
	),
	array(
		"name" => "contentSearch",
		"sql" => " and c.content LIKE CONCAT('%',#,'%')   "
	),
	array(
		"name" => "education",
		"sql" => " and c.education=# "
	),
	array(
		"name" => "educationSearch",
		"sql" => " and c.educationName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "educationName",
		"sql" => " and c.educationName=# "
	),
	array(
		"name" => "certificate",
		"sql" => " and c.certificate=# "
	),
	array(
		"name" => "beginDate",
		"sql" => " and c.beginDate=# "
	),
	array(
		"name" => "beginDateSearch",
		"sql" => " and c.beginDate >= BINARY # "
	),
	array(
		"name" => "closeDate",
		"sql" => " and c.closeDate=# "
	),
	array(
		"name" => "closeDateSearch",
		"sql" => " and c.closeDate <= BINARY # "
	),
	array(
		"name" => "remark",
		"sql" => " and c.remark=# "
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