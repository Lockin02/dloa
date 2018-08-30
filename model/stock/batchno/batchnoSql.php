<?php
/**
 * @author Administrator
 * @Date 2011年5月18日 10:50:36
 * @version 1.0
 * @description:物料批次号台账 sql配置文件 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.inDocId ,c.inDocCode ,c.inDocItemId ,c.outDocCode ,c.outDocId ,c.outDocItemId ,c.productId ,c.productName ,c.productCode ,c.batchNo ,c.stockId ,c.stockName ,c.stockCode ,c.stockNum ,c.remark  from oa_stock_product_batchno c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "inDocId",
   		"sql" => " and c.inDocId=# "
   	  ),
   array(
   		"name" => "inDocCode",
   		"sql" => " and c.inDocCode=# "
   	  ),
   array(
   		"name" => "inDocItemId",
   		"sql" => " and c.inDocItemId=# "
   	  ),
   array(
   		"name" => "outDocCode",
   		"sql" => " and c.outDocCode=# "
   	  ),
   array(
   		"name" => "outDocId",
   		"sql" => " and c.outDocId=# "
   	  ),
   array(
   		"name" => "outDocItemId",
   		"sql" => " and c.outDocItemId=# "
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
   		"name" => "productCode",
   		"sql" => " and c.productCode=# "
   	  ),
   array(
   		"name" => "batchNo",
   		"sql" => " and c.batchNo=# "
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
   	  ),
   array(
   		"name" => "stockNum",
   		"sql" => " and c.stockNum=# "
   	  ),
   array(
   		"name" => "remark",
   		"sql" => " and c.remark=# "
   	  )
)
?>