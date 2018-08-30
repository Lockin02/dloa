<?php
/**
 * @author Administrator
 * @Date 2012-10-29 14:47:08
 * @version 1.0
 * @description:设备配置明细 sql配置文件 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.deployId ,c.name ,c.info ,c.price ,c.remark  from oa_equ_baseinfo_deployInfo c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "deployId",
   		"sql" => " and c.deployId=# "
   	  ),
   array(
   		"name" => "name",
   		"sql" => " and c.name=# "
   	  ),
   array(
   		"name" => "info",
   		"sql" => " and c.info=# "
   	  ),
   array(
   		"name" => "price",
   		"sql" => " and c.price=# "
   	  ),
   array(
   		"name" => "remark",
   		"sql" => " and c.remark=# "
   	  )
)
?>