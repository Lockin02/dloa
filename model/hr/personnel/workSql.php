<?php
/**
 * @author Administrator
 * @Date 2012年5月25日 星期五 15:11:59
 * @version 1.0
 * @description:人事管理-基础信息-工作经历 sql配置文件
 */
$sql_arr = array (
	"select_default"=>"select c.id ,c.userNo ,c.userAccount ,c.userName ,c.company ,c.dept ,c.position ,c.treatment ,c.beginDate ,c.closeDate ,c.seniority ,c.isSeniority ,c.responsibilities ,c.leaveReason ,c.prove ,c.remark ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime ,p.staffName ,p.highSchool ,p.professionalName ,p.companyType ,p.companyName ,p.deptName ,p.deptNameS ,p.deptNameT ,p.deptNameF ,p.personnelTypeName ,p.wageLevelName ,p.jobLevel
	 from oa_hr_personnel_work c
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
		"name" => "company",
		"sql" => " and c.company=# "
	),
	array(
		"name" => "companySearch",
		"sql" => " and c.company LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "dept",
		"sql" => " and c.dept=# "
	),
	array(
		"name" => "deptSearch",
		"sql" => " and c.dept LIKE CONCAT('%',#,'%')  "
	),
	array(
		"name" => "position",
		"sql" => " and c.position=# "
	),
	array(
		"name" => "positionSearch",
		"sql" => " and c.position LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "treatment",
		"sql" => " and c.treatment=# "
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
		"name" => "seniority",
		"sql" => " and c.seniority=# "
	),
	array(
		"name" => "isSeniority",
		"sql" => " and c.isSeniority=# "
	),
	array(
		"name" => "responsibilities",
		"sql" => " and c.responsibilities LIKE CONCAT('%',#,'%')  "
	),
	array(
		"name" => "leaveReason",
		"sql" => " and c.leaveReason=# "
	),
	array(
		"name" => "prove",
		"sql" => " and c.prove=# "
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