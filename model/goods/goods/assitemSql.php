<?php
/**
 * @author Administrator
 * @Date 2012��3��1�� 20:16:01
 * @version 1.0
 * @description:���������ϵ sql�����ļ� 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.mainId ,c.itemName ,c.itemId ,c.propertiesName ,c.propertiesId  from oa_goods_properties_assitem c where 1=1 "
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
   		"name" => "itemName",
   		"sql" => " and c.itemName=# "
   	  ),
   array(
   		"name" => "itemId",
   		"sql" => " and c.itemId=# "
   	  ),
   array(
   		"name" => "propertiesName",
   		"sql" => " and c.propertiesName=# "
   	  ),
   array(
   		"name" => "propertiesId",
   		"sql" => " and c.propertiesId=# "
   	  )
)
?>