<?php
/**
 * @author Administrator
 * @Date 2011年8月9日 19:38:28
 * @version 1.0
 * @description:盘点物料清单 sql配置文件 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.checkId ,c.productId ,c.productCode ,c.productName ,c.pattern ,c.billNum ,c.actNum ,c.adjustNum ,c.unitName ,c.batchNum ,c.price ,c.subPrice ,c.stockId ,c.stockCode ,c.stockName ,c.remark  from oa_stock_check_item c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "checkId",
   		"sql" => " and c.checkId=# "
   	  ),
   array(
   		"name" => "productId",
   		"sql" => " and c.productId=# "
   	  ),
   array(
   		"name" => "productCode",
   		"sql" => " and c.productCode=# "
   	  ),
   array(
   		"name" => "productName",
   		"sql" => " and c.productName=# "
   	  ),
   array(
   		"name" => "pattern",
   		"sql" => " and c.pattern=# "
   	  ),
   array(
   		"name" => "billNum",
   		"sql" => " and c.billNum=# "
   	  ),
   array(
   		"name" => "actNum",
   		"sql" => " and c.actNum=# "
   	  ),
   array(
   		"name" => "adjustNum",
   		"sql" => " and c.adjustNum=# "
   	  ),
   array(
   		"name" => "unitName",
   		"sql" => " and c.unitName=# "
   	  ),
   array(
   		"name" => "batchNum",
   		"sql" => " and c.batchNum=# "
   	  ),
   array(
   		"name" => "price",
   		"sql" => " and c.price=# "
   	  ),
   array(
   		"name" => "subPrice",
   		"sql" => " and c.subPrice=# "
   	  ),
   array(
   		"name" => "stockId",
   		"sql" => " and c.stockId=# "
   	  ),
   array(
   		"name" => "stockCode",
   		"sql" => " and c.stockCode=# "
   	  ),
   array(
   		"name" => "stockName",
   		"sql" => " and c.stockName=# "
   	  ),
   array(
   		"name" => "remark",
   		"sql" => " and c.remark=# "
   	  )
)
?>