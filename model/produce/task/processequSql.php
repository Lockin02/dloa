<?php
/**
 * @author ACan
 * @Date 2015年4月13日 14:25:37
 * @version 1.0
 * @description:配置信息-工序 sql配置文件 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.parentId ,c.process ,c.processName ,c.processTime ,c.recipient ,c.recipientId ,c.remark  from oa_produce_taskconfig_processequ c where 1=1 "
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
   		"name" => "process",
   		"sql" => " and c.process=# "
   	  ),
   array(
   		"name" => "processName",
   		"sql" => " and c.processName=# "
   	  ),
   array(
   		"name" => "processTime",
   		"sql" => " and c.processTime=# "
   	  ),
   array(
   		"name" => "recipient",
   		"sql" => " and c.recipient=# "
   	  ),
   array(
   		"name" => "recipientId",
   		"sql" => " and c.recipientId=# "
   	  ),
   array(
   		"name" => "remark",
   		"sql" => " and c.remark=# "
   	  )
)
?>