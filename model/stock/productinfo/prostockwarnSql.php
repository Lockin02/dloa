<?php
/**
 * @author huangzf
 * @Date 2012年4月20日 星期五 9:57:12
 * @version 1.0
 * @description:物料库存预警信息配置 sql配置文件 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.stockId ,c.stockName ,c.stockCode ,c.productId ,c.productCode ,c.productName ,c.pattern ,c.unitName ,c.safeNum ,c.maxNum ,c.miniNum ,c.remark ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime  from oa_stock_productstock_warn c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
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
   		"name" => "productCode",
   		"sql" => " and c.productCode=# "
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
   		"name" => "lproductCode",
   		"sql" => " and c.productCode like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "lproductName",
   		"sql" => " and c.productName like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "pattern",
   		"sql" => " and c.pattern=# "
   	  ),
   array(
   		"name" => "unitName",
   		"sql" => " and c.unitName=# "
   	  ),
   array(
   		"name" => "safeNum",
   		"sql" => " and c.safeNum=# "
   	  ),
   array(
   		"name" => "maxNum",
   		"sql" => " and c.maxNum=# "
   	  ),
   array(
   		"name" => "miniNum",
   		"sql" => " and c.miniNum=# "
   	  ),
   array(
   		"name" => "remark",
   		"sql" => " and c.remark=# "
   	  ),
   array(
   		"name" => "createName",
   		"sql" => " and c.createName=# "
   	  ),
   array(
   		"name" => "createId",
   		"sql" => " and c.createId=# "
   	  ),
   array(
   		"name" => "createTime",
   		"sql" => " and c.createTime=# "
   	  ),
   array(
   		"name" => "updateName",
   		"sql" => " and c.updateName=# "
   	  ),
   array(
   		"name" => "updateId",
   		"sql" => " and c.updateId=# "
   	  ),
   array(
   		"name" => "updateTime",
   		"sql" => " and c.updateTime=# "
   	  )
)
?>