<?php
/**
 * @author Administrator
 * @Date 2013年4月25日 星期四 15:29:40
 * @version 1.0
 * @description:工资清单模板 sql配置文件 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.formCode ,c.formDate ,c.schemeCode ,c.schemeTypeCode ,c.schemeTypeName ,c.schemeName ,c.jobName ,c.jobId ,c.companyName ,c.companyId ,c.leaveTypeCode ,c.leaveTypeName ,c.state ,c.remark ,c.ExaStatus ,c.ExaDT ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime  from oa_hr_leave_salarytplate c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "formCode",
   		"sql" => " and c.formCode=# "
   	  ),
   array(
   		"name" => "formDate",
   		"sql" => " and c.formDate=# "
   	  ),
   array(
   		"name" => "schemeCode",
   		"sql" => " and c.schemeCode=# "
   	  ),
   array(
   		"name" => "schemeTypeCode",
   		"sql" => " and c.schemeTypeCode=# "
   	  ),
   array(
   		"name" => "schemeTypeName",
   		"sql" => " and c.schemeTypeName=# "
   	  ),
   array(
   		"name" => "schemeName",
   		"sql" => " and c.schemeName=# "
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
   		"name" => "leaveTypeCode",
   		"sql" => " and c.leaveTypeCode=# "
   	  ),
   array(
   		"name" => "leaveTypeName",
   		"sql" => " and c.leaveTypeName=# "
   	  ),
   array(
   		"name" => "state",
   		"sql" => " and c.state=# "
   	  ),
   array(
   		"name" => "remark",
   		"sql" => " and c.remark=# "
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
   	  )
)
?>