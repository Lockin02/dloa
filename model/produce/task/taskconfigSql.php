<?php
/**
 * @author yxin1
 * @Date 2014��8��25�� 11:04:57
 * @version 1.0
 * @description:������������ sql�����ļ� 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.taskId ,c.configId ,c.configCode ,c.colName ,c.colCode  from oa_produce_taskconfig c where 1=1 "
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
   		"name" => "configId",
   		"sql" => " and c.configId=# "
   	  ),
   array(
   		"name" => "configCode",
   		"sql" => " and c.configCode=# "
   	  ),
   array(
   		"name" => "colName",
   		"sql" => " and c.colName=# "
   	  ),
   array(
   		"name" => "colCode",
   		"sql" => " and c.colCode=# "
   	  )
)
?>