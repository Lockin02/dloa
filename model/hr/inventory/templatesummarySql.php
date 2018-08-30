<?php
/**
 * @author Administrator
 * @Date 2012年8月30日 19:17:07
 * @version 1.0
 * @description:盘点总结属性 sql配置文件 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.templateId ,c.question ,c.orderIndex ,c.remark  from oa_hr_inventory_templatesummary c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "templateId",
   		"sql" => " and c.templateId=# "
   	  ),
   array(
   		"name" => "question",
   		"sql" => " and c.question=# "
   	  ),
   array(
   		"name" => "orderIndex",
   		"sql" => " and c.orderIndex=# "
   	  ),
   array(
   		"name" => "remark",
   		"sql" => " and c.remark=# "
   	  )
)
?>