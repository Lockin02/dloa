<?php
/**
 * @author Administrator
 * @Date 2010年12月5日 9:37:44
 * @version 1.0
 * @description:工作日志汇总(oa_esm_worklog_summary) sql配置文件 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.weekId ,c.logDate ,c.workStatus ,c.question  from oa_esm_worklog_summary c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "weekId",
   		"sql" => " and c.weekId=# "
   	  ),
   array(
   		"name" => "logDate",
   		"sql" => " and c.logDate=# "
   	  ),
   array(
   		"name" => "workStatus",
   		"sql" => " and c.workStatus=# "
   	  ),
   array(
   		"name" => "question",
   		"sql" => " and c.question=# "
   	  )
)
?>