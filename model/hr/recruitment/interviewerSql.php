<?php
/**
 * @author Administrator
 * @Date 2012年7月18日 星期三 15:11:15
 * @version 1.0
 * @description:面试官 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.parentId ,c.parentCode ,c.interviewerId ,c.interviewerName,c.interviewerType  from oa_hr_invitation_interviewer c where 1=1 "
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
   		"name" => "parentCode",
   		"sql" => " and c.parentCode=# "
   	  ),
   array(
   		"name" => "interviewerId",
   		"sql" => " and c.interviewerId=# "
   	  ),
   array(
   		"name" => "interviewerName",
   		"sql" => " and c.interviewerName=# "
   	  )
)
?>