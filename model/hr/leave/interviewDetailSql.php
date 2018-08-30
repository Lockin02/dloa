<?php
/**
 * @author Administrator
 * @Date 2012年10月29日 星期一 15:17:23
 * @version 1.0
 * @description:离职--面谈记录表详细 sql配置文件 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.parentId ,c.leaveId ,c.interviewer ,c.interviewerId ,c.interviewContent ,c.interviewDate ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime,c.leaveReson,c.jobAdvice,c.companyAdvice,c.interviewAdvice from oa_hr_leave_interviewDetail c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "parentId",
   		"sql" => " and c.parentId=# "
   	  ),
   array(
   		"name" => "leaveId",
   		"sql" => " and c.leaveId=# "
   	  ),
   array(
   		"name" => "interviewer",
   		"sql" => " and c.interviewer=# "
   	  ),
   array(
   		"name" => "interviewerId",
   		"sql" => " and c.interviewerId=# "
   	  ),
   array(
   		"name" => "interviewContent",
   		"sql" => " and c.interviewContent=# "
   	  ),
   array(
   		"name" => "interviewDate",
   		"sql" => " and c.interviewDate=# "
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