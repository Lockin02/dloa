<?php
/**
 * @author Administrator
 * @Date 2011��5��25�� 9:50:39
 * @version 1.0
 * @description:�����ͬ�����嵥 sql�����ļ�
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.orderId ,c.orderCode ,c.orderName ,c.serviceItem ,c.serviceNo ,c.serviceRemark ,c.license,c.isTemp,c.originalId  from oa_service_list c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "orderId",
   		"sql" => " and c.orderId=# "
   	  ),
   array(
   		"name" => "orderCode",
   		"sql" => " and c.orderCode=# "
   	  ),
   array(
   		"name" => "orderName",
   		"sql" => " and c.orderName=# "
   	  ),
   array(
   		"name" => "serviceItem",
   		"sql" => " and c.serviceItem=# "
   	  ),
   array(
   		"name" => "serviceNo",
   		"sql" => " and c.serviceNo=# "
   	  ),
   array(
   		"name" => "serviceRemark",
   		"sql" => " and c.serviceRemark=# "
   	  ),
   array(
   		"name" => "license",
   		"sql" => " and c.license=# "
   	  )
)
?>