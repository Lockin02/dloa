<?php
/**
 * @author Administrator
 * @Date 2013年4月25日 星期四 16:33:08
 * @version 1.0
 * @description:工资交接单 sql配置文件 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.leaveId ,c.userNo ,c.userAccount ,c.userName ,c.deptName ,c.deptId ,c.jobName ,c.jobId ,c.companyName ,c.companyId ,c.entryDate ,c.quitDate ,c.quitReson ,c.quitTypeCode ,c.quitTypeName ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime ,c.ExaStatus ,c.ExaDT  from oa_hr_leave_salarydoc c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "leaveId",
   		"sql" => " and c.leaveId=# "
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
   		"name" => "deptName",
   		"sql" => " and c.deptName=# "
   	  ),
   array(
   		"name" => "deptId",
   		"sql" => " and c.deptId=# "
   	  ),
   array(
   		"name" => "jobName",
   		"sql" => " and c.jobName=# "
   	  ),
   array(
   		"name" => "jobId",
   		"sql" => " and c.jobId=# "
   	  ),
   array(
   		"name" => "companyName",
   		"sql" => " and c.companyName=# "
   	  ),
   array(
   		"name" => "companyId",
   		"sql" => " and c.companyId=# "
   	  ),
   array(
   		"name" => "entryDate",
   		"sql" => " and c.entryDate=# "
   	  ),
   array(
   		"name" => "quitDate",
   		"sql" => " and c.quitDate=# "
   	  ),
   array(
   		"name" => "quitReson",
   		"sql" => " and c.quitReson=# "
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
   		"name" => "ExaStatus",
   		"sql" => " and c.ExaStatus=# "
   	  ),
   array(
   		"name" => "ExaDT",
   		"sql" => " and c.ExaDT=# "
   	  )
)
?>