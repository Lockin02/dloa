<?php
/**
 * @author Administrator
 * @Date 2011年5月16日 16:19:07
 * @version 1.0
 * @description:退料通知单物料清单信息 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.basicId ,c.businessType ,c.businessId ,c.productId ,c.productName ,c.productNumb ,c.pattem ,c.batchNum ,c.units ,c.auxiUnit ,c.price ,c.conversionrate ,c.moneyAll ,c.deliveredNum ,c.factNum ,c.deliveredDate ,c.stockId ,c.stockName ,c.stockCode  from oa_purchase_delivered_equ c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "basicId",
   		"sql" => " and c.basicId=# "
   	  ),
   array(
   		"name" => "businessType",
   		"sql" => " and c.businessType=# "
   	  ),
   array(
   		"name" => "businessId",
   		"sql" => " and c.businessId=# "
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
   		"name" => "productNumb",
   		"sql" => " and c.productNumb=# "
   	  ),
   array(
   		"name" => "pattem",
   		"sql" => " and c.pattem=# "
   	  ),
   array(
   		"name" => "batchNum",
   		"sql" => " and c.batchNum=# "
   	  ),
   array(
   		"name" => "units",
   		"sql" => " and c.units=# "
   	  ),
   array(
   		"name" => "auxiUnit",
   		"sql" => " and c.auxiUnit=# "
   	  ),
   array(
   		"name" => "price",
   		"sql" => " and c.price=# "
   	  ),
   array(
   		"name" => "conversionrate",
   		"sql" => " and c.conversionrate=# "
   	  ),
   array(
   		"name" => "moneyAll",
   		"sql" => " and c.moneyAll=# "
   	  ),
   array(
   		"name" => "deliveredNum",
   		"sql" => " and c.deliveredNum=# "
   	  ),
   array(
   		"name" => "factNum",
   		"sql" => " and c.factNum=# "
   	  ),
   array(
   		"name" => "deliveredDate",
   		"sql" => " and c.deliveredDate=# "
   	  ),
   array(
   		"name" => "stockId",
   		"sql" => " and c.stockId=# "
   	  ),
   array(
   		"name" => "stockName",
   		"sql" => " and c.stockName=# "
   	  ),
   array(
   		"name" => "stockCode",
   		"sql" => " and c.stockCode=# "
   	  )
)
?>