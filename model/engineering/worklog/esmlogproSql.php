<?php
/**
 * @author Administrator
 * @Date 2010年12月5日 9:38:40
 * @version 1.0
 * @description:日志参与项目 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.workLogId ,c.proName ,c.proId ,c.proCode  from oa_esm_worklog_proinfo c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "workLogId",
   		"sql" => " and c.workLogId=# "
   	  ),
   array(
   		"name" => "proName",
   		"sql" => " and c.proName=# "
   	  ),
   array(
   		"name" => "proId",
   		"sql" => " and c.proId=# "
   	  ),
   array(
   		"name" => "proCode",
   		"sql" => " and c.proCode=# "
   	  )
)
?>