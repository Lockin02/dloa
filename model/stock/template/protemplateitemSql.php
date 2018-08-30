<?php
/**
 * @author Show
 * @Date 2013年8月2日 星期五 14:41:42
 * @version 1.0
 * @description:物料模板配置明细表 sql配置文件 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.mainId ,c.productId ,c.productCode ,c.productName ,c.pattern ,c.unitName ,c.actNum  from oa_stock_product_templateitem c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "mainId",
   		"sql" => " and c.mainId=# "
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
   		"name" => "unitName",
   		"sql" => " and c.unitName=# "
   	  ),
   array(
   		"name" => "actNum",
   		"sql" => " and c.actNum=# "
   	  )
)
?>