<?php
/**
 * @author Administrator
 * @Date 2012��3��16�� 14:01:37
 * @version 1.0
 * @description:������������ sql�����ļ� 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.mainId ,c.itemNames ,c.itemIds ,c.goodsId  from oa_goods_asslist c where 1=1 "
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
   		"name" => "itemNames",
   		"sql" => " and c.itemNames=# "
   	  ),
   array(
   		"name" => "itemIds",
   		"sql" => " and c.itemIds=# "
   	  ),
   array(
   		"name" => "goodsId",
   		"sql" => " and c.goodsId=# "
   	  )
)
?>