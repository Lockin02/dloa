<?php
/**
 * @author huangzf
 * @Date 2012��5��21�� ����һ 9:47:24
 * @version 1.0
 * @description:��������� sql�����ļ� 
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