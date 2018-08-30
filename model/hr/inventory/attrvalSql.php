<?php
/**
 * @author zengq
 * @Date 2012年8月20日 11:14:36
 * @version 1.0
 * @description:盘点表属性值 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.attrId ,c.valName  from oa_hr_inventory_attrval c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "attrId",
   		"sql" => " and c.attrId=# "
   	  ),
   array(
   		"name" => "valName",
   		"sql" => " and c.valName=# "
   	  )
)
?>