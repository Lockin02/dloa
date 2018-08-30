<?php
/**
 * @author LiuBo
 * @Date 2011年3月4日 15:06:47
 * @version 1.0
 * @description:订单开票计划 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.orderId,c.orderCode  ,c.orderName ,c.money ,c.softM ,c.iType ,c.invDT ,c.remark ,c.isOver ,c.overDT,c.isTemp,c.originalId  from oa_sale_ordert_invoice c where 1=1 "
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
   		"name" => "orderId",
   		"sql" => " and c.orderId=# "
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
   		"name" => "softM",
   		"sql" => " and c.softM=# "
   	  ),
   array(
   		"name" => "iType",
   		"sql" => " and c.iType=# "
   	  ),
   array(
   		"name" => "invDT",
   		"sql" => " and c.invDT=# "
   	  ),
   array(
   		"name" => "remark",
   		"sql" => " and c.remark=# "
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