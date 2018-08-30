<?php
/**
 * @author Administrator
 * @Date 2012年8月22日 19:40:09
 * @version 1.0
 * @description:导师考核表 sql配置文件
 */
$sql_arr = array (
	"select_default"=>"select c.id ,c.tutorId ,c.userNo ,c.userAccount ,c.userName ,c.jobId ,c.jobName ,c.deptId ,c.deptName ,c.studentNo ,c.studentAccount ,c.studentName ,c.studentDeptId ,c.studentDeptName ,c.tryBeginDate ,c.tryEndDate ,c.superiorName ,c.superiorId ,c.hrName ,c.hrId ,c.assistantId ,c.assistantName ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime ,c.sysCompanyName ,c.sysCompanyId ,c.selfgraded ,c.superiorgraded ,c.staffgraded ,c.assistantgraded ,c.hrgraded ,c.assessmentScore ,c.supProportion ,c.hrProportion ,c.deptProportion ,c.tutProportion
	from oa_hr_tutor_scheme c
	LEFT JOIN oa_hr_tutor_records d ON c.tutorId=d.id
	where 1=1 ",
	"select_examinelist"=>"select c.id ,c.tutorId ,c.userNo ,c.userAccount ,c.userName ,c.jobId ,c.jobName ,c.deptId ,c.deptName ,c.studentNo ,c.studentAccount ,c.studentName ,c.studentDeptId ,c.studentDeptName ,c.tryBeginDate ,c.tryEndDate ,c.superiorName ,c.superiorId ,c.hrName ,c.hrId ,c.assistantId ,c.assistantName ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime ,c.sysCompanyName ,c.sysCompanyId ,c.selfgraded ,c.superiorgraded ,c.staffgraded ,c.assistantgraded ,c.hrgraded ,c.assessmentScore ,c.supProportion ,c.hrProportion ,c.deptProportion ,c.tutProportion ,d.status
	from oa_hr_tutor_scheme c
	LEFT JOIN oa_hr_tutor_records d ON c.tutorId=d.id
	where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "tutorId",
   		"sql" => " and c.tutorId=# "
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
   		"name" => "userName",
   		"sql" => " and c.userName LIKE CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "jobId",
   		"sql" => " and c.jobId=# "
   	  ),
   array(
   		"name" => "jobName",
   		"sql" => " and c.jobName LIKE CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "deptId",
   		"sql" => " and c.deptId=# "
   	  ),
   array(
   		"name" => "deptName",
   		"sql" => " and c.deptName LIKE CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "studentNo",
   		"sql" => " and c.studentNo=# "
   	  ),
   array(
   		"name" => "studentAccount",
   		"sql" => " and c.studentAccount=# "
   	  ),
   array(
   		"name" => "studentName",
   		"sql" => " and c.studentName LIKE CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "studentDeptId",
   		"sql" => " and c.studentDeptId=# "
   	  ),
   array(
   		"name" => "studentDeptName",
   		"sql" => " and c.studentDeptName LIKE CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "tryBeginDate",
   		"sql" => " and c.tryBeginDate=# "
   	  ),
   array(
   		"name" => "tryEndDate",
   		"sql" => " and c.tryEndDate=# "
   	  ),
   array(
   		"name" => "superiorName",
   		"sql" => " and c.superiorName=# "
   	  ),
   array(
   		"name" => "superiorId",
   		"sql" => " and c.superiorId=# "
   	  ),
   array(
   		"name" => "hrName",
   		"sql" => " and c.hrName=# "
   	  ),
   array(
   		"name" => "hrId",
   		"sql" => " and c.hrId=# "
   	  ),
   array(
   		"name" => "assistantId",
   		"sql" => " and c.assistantId=# "
   	  ),
   array(
   		"name" => "assistantName",
   		"sql" => " and c.assistantName=# "
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
   		"name" => "threeman",
   		"sql" => " and c.assistantId=# or c.superiorId=# or c.hrId=# "
   	  )
)
?>