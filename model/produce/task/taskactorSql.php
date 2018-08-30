<?php
/**
 * @author huangzf
 * @Date 2012年5月21日 星期一 9:47:24
 * @version 1.0
 * @description:任务参与人 sql配置文件 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.taskId ,c.actUserCode ,c.actUserName  from oa_produce_task_actor c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "taskId",
   		"sql" => " and c.taskId=# "
   	  ),
   array(
   		"name" => "actUserCode",
   		"sql" => " and c.actUserCode=# "
   	  ),
   array(
   		"name" => "actUserName",
   		"sql" => " and c.actUserName=# "
   	  )
)
?>