<?php
/**
 * @author Administrator
 * @Date 2012年12月14日 星期五 15:18:00
 * @version 1.0
 * @description:订单供应商_报价单物料清单 sql配置文件 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.parentId ,c.productName ,c.productId ,c.productNumb ,c.pattem ,c.units ,c.auxiliary ,c.amount ,c.deliveryDate ,c.transportation ,c.price ,c.tax ,c.moneyAll ,c.applyEquId ,c.takeEquId ,c.purchType ,c.taxRate ,c.batchNumb  from oa_purch_apply_suppequ c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "parentId",
   		"sql" => " and c.parentId=# "
   	  ),
   array(
   		"name" => "productName",
   		"sql" => " and c.productName=# "
   	  ),
   array(
   		"name" => "productId",
   		"sql" => " and c.productId=# "
   	  ),
   array(
   		"name" => "productNumb",
   		"sql" => " and c.productNumb=# "
   	  ),
   array(
   		"name" => "pattem",
   		"sql" => " and c.pattem=# "
   	  ),
   array(
   		"name" => "units",
   		"sql" => " and c.units=# "
   	  ),
   array(
   		"name" => "auxiliary",
   		"sql" => " and c.auxiliary=# "
   	  ),
   array(
   		"name" => "amount",
   		"sql" => " and c.amount=# "
   	  ),
   array(
   		"name" => "deliveryDate",
   		"sql" => " and c.deliveryDate=# "
   	  ),
   array(
   		"name" => "transportation",
   		"sql" => " and c.transportation=# "
   	  ),
   array(
   		"name" => "price",
   		"sql" => " and c.price=# "
   	  ),
   array(
   		"name" => "tax",
   		"sql" => " and c.tax=# "
   	  ),
   array(
   		"name" => "moneyAll",
   		"sql" => " and c.moneyAll=# "
   	  ),
   array(
   		"name" => "applyEquId",
   		"sql" => " and c.applyEquId=# "
   	  ),
   array(
   		"name" => "takeEquId",
   		"sql" => " and c.takeEquId=# "
   	  ),
   array(
   		"name" => "purchType",
   		"sql" => " and c.purchType=# "
   	  ),
   array(
   		"name" => "taxRate",
   		"sql" => " and c.taxRate=# "
   	  ),
   array(
   		"name" => "batchNumb",
   		"sql" => " and c.batchNumb=# "
   	  )
)
?>