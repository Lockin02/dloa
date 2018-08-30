<?php
/**
 * @author Administrator
 * @Date 2012年8月22日 16:39:03
 * @version 1.0
 * @description:导师考核表 sql配置文件 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.tutorId ,c.userNo ,c.userAccount ,c.userName ,c.jobId ,c.jobName ,c.deptId ,c.deptName ,c.studentNo ,c.studentAccount ,c.studentName ,c.studentDeptId ,c.studentDeptName ,c.tryBeginDate ,c.tryEndDate ,c.superiorName ,c.superiorId ,c.hrName ,c.hrId ,c.assistantId ,c.assistantName ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime ,c.sysCompanyName ,c.sysCompanyId  from oa_hr_tutor_tutorassess c where 1=1 "
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
   		"name" => "jobId",
   		"sql" => " and c.jobId=# "
   	  ),
   array(
   		"name" => "jobName",
   		"sql" => " and c.jobName=# "
   	  ),
   array(
   		"name" => "deptId",
   		"sql" => " and c.deptId=# "
   	  ),
   array(
   		"name" => "deptName",
   		"sql" => " and c.deptName=# "
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
   		"sql" => " and c.studentName=# "
   	  ),
   array(
   		"name" => "studentDeptId",
   		"sql" => " and c.studentDeptId=# "
   	  ),
   array(
   		"name" => "studentDeptName",
   		"sql" => " and c.studentDeptName=# "
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
   	  )
)
?>