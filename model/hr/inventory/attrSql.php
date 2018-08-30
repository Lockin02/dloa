<?php
/*
 * @author: zengq
 * Created on 2012-8-20
 *
 * @description:盘点管理->属性管理->sql配置文件
 */
 $sql_arr = array (
         "select_default"=>"select c.id ,c.attrName ,c.attrType ,c.remark  from oa_hr_inventory_attr c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "attrName",
   		"sql" => " and c.attrName like CONCAT('%',#,'%')"
   	  ),
   array(
   		"name" => "attrType",
   		"sql" => " and c.attrType=# "
   	  ),
   array(
   		"name" => "remark",
   		"sql" => " and c.remark=# "
   	  )
)
?>
