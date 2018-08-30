<?php
/**
 * @author Show
 * @Date 2011年5月31日 星期二 10:30:26
 * @version 1.0
 * @description:成本调整单详细 sql配置文件 
 */
$sql_arr = array (
    "select_default"=>"select c.id ,c.costajustId ,c.productId ,c.productNo ,c.productName ,c.productModel ,c.batchNo ,c.money ,c.detailDate ,c.durability ,c.period ,c.stockId ,c.stockName ,c.stockCode ,c.remark  from oa_finance_costajust_detail c where 1=1 "
);

$condition_arr = array (
	array(
       "name" => "id",
   		"sql" => " and c.Id=# "
    ),
   array(
   		"name" => "costajustId",
   		"sql" => " and c.costajustId=# "
    ),
   array(
   		"name" => "productId",
   		"sql" => " and c.productId=# "
    ),
   array(
   		"name" => "productNo",
   		"sql" => " and c.productNo=# "
    ),
   array(
   		"name" => "productName",
   		"sql" => " and c.productName=# "
    ),
   array(
   		"name" => "productModel",
   		"sql" => " and c.productModel=# "
    ),
   array(
   		"name" => "batchNo",
   		"sql" => " and c.batchNo=# "
    ),
   array(
   		"name" => "money",
   		"sql" => " and c.money=# "
    ),
   array(
   		"name" => "detailDate",
   		"sql" => " and c.detailDate=# "
    ),
   array(
   		"name" => "durability",
   		"sql" => " and c.durability=# "
    ),
   array(
   		"name" => "period",
   		"sql" => " and c.period=# "
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
   		"name" => "remark",
   		"sql" => " and c.remark=# "
    )
)
?>