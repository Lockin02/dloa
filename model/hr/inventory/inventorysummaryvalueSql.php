<?php
/**
 * @author Administrator
 * @Date 2012年8月31日 10:01:18
 * @version 1.0
 * @description:盘点总结值 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.inventoryId ,c.question ,c.answer , c.state from oa_hr_inventory_inventorysummaryvalue c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "inventoryId",
   		"sql" => " and c.inventoryId=# "
   	  ),
   array(
   		"name" => "question",
   		"sql" => " and c.question=# "
   	  ),
   array(
   		"name" => "answer",
   		"sql" => " and c.answer=# "
   	  )
)
?>