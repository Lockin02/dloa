<?php
/**
 * @author huangzf
 * @Date 2012年2月2日 15:46:00
 * @version 1.0
 * @description:日志清单(oa_esm_worklog_detail) sql配置文件 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.worklogId ,c.projectId ,c.projectCode ,c.projectName ,c.workType ,c.workContent ,c.workloadDay  from oa_esm_worklog_detail c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "worklogId",
   		"sql" => " and c.worklogId=# "
   	  ),
   array(
   		"name" => "projectId",
   		"sql" => " and c.projectId=# "
   	  ),
   array(
   		"name" => "projectCode",
   		"sql" => " and c.projectCode=# "
   	  ),
   array(
   		"name" => "projectName",
   		"sql" => " and c.projectName=# "
   	  ),
   array(
   		"name" => "workType",
   		"sql" => " and c.workType=# "
   	  ),
   array(
   		"name" => "workContent",
   		"sql" => " and c.workContent=# "
   	  ),
   array(
   		"name" => "workloadDay",
   		"sql" => " and c.workloadDay=# "
   	  )
)
?>