<?php
/**
 * @author Administrator
 * @Date 2012年3月1日 20:16:15
 * @version 1.0
 * @description:属性不可见性关系 sql配置文件 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.mainId ,c.proTypeId ,c.proTypeName  from oa_goods_properties_assproperties c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "mainId",
   		"sql" => " and c.mainId=# "
   	  ),
   array(
   		"name" => "proTypeId",
   		"sql" => " and c.proTypeId=# "
   	  ),
   array(
   		"name" => "proTypeName",
   		"sql" => " and c.proTypeName=# "
   	  )
)
?>