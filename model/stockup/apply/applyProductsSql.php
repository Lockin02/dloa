<?php
/**
 * @author Administrator
 * @Date 2013��11��11�� ����һ 22:21:40
 * @version 1.0
 * @description:����������ϸ�� sql�����ļ� 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.appId ,c.productId ,c.productName,c.remark ,c.productNum ,c.productConfig ,c.exDeliveryDate  from oa_stockup_apply_products c where 1=1 ",
         "pageItem"=>"select c.id ,c.appId ,c.productId ,c.productName ,c.isClose ,c.remark ,c.productNum ,c.productConfig ,c.exDeliveryDate  from oa_stockup_apply_products c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "appId",
   		"sql" => " and c.appId=# "
   	  ),
   array(
   		"name" => "productId",
   		"sql" => " and c.productId=# "
   	  ),
   array(
   		"name" => "productName",
   		"sql" => " and c.productName=# "
   	  ),
   array(
   		"name" => "remark",
   		"sql" => " and c.remark=# "
   	  ),
   array(
   		"name" => "productNum",
   		"sql" => " and c.productNum=# "
   	  ),
   array(
   		"name" => "productConfig",
   		"sql" => " and c.productConfig=# "
   	  ),
   array(
   		"name" => "exDeliveryDate",
   		"sql" => " and c.exDeliveryDate=# "
   	  )
)
?>