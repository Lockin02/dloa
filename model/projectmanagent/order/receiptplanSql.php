<?php
/**
 * @author LiuBo
 * @Date 2011年3月4日 15:13:34
 * @version 1.0
 * @description:订单收款计划 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.orderId,c.orderCode,c.orderName ,c.money ,c.payDT ,c.pType ,c.collectionTerms ,c.isOver ,c.overDT,c.isTemp,c.originalId  from oa_sale_order_receiptplan c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "orderCode",
   		"sql" => " and c.orderCode=# "
   	  ),
   array(
   		"name" => "orderID",
   		"sql" => " and c.orderID=# "
   	  ),
   array(
   		"name" => "orderName",
   		"sql" => " and c.orderName=# "
   	  ),
   array(
   		"name" => "money",
   		"sql" => " and c.money=# "
   	  ),
   array(
   		"name" => "payDT",
   		"sql" => " and c.payDT=# "
   	  ),
   array(
   		"name" => "pType",
   		"sql" => " and c.pType=# "
   	  ),
   array(
   		"name" => "collectionTerms",
   		"sql" => " and c.collectionTerms=# "
   	  ),
   array(
   		"name" => "isOver",
   		"sql" => " and c.isOver=# "
   	  ),
   array(
   		"name" => "overDT",
   		"sql" => " and c.overDT=# "
   	  )
)
?>