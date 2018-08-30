<?php
/**
 * @author Administrator
 * @Date 2012年5月23日 9:50:17
 * @version 1.0
 * @description:发货需求自定义类型 sql配置文件 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.type ,c.remark  from oa_shipment_type c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "type",
   		"sql" => " and c.type=# "
   	  ),
   array(
   		"name" => "remark",
   		"sql" => " and c.remark=# "
   	  )
)
?>