<?php
/**
 * @author huangzf
 * @Date 2011年11月27日 14:36:58
 * @version 1.0
 * @description:零配件订单清单 sql配置文件 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.mainId ,c.productId ,c.productCode ,c.productName ,c.pattern ,c.unitName ,c.warranty ,c.proNum ,c.price ,c.subCost ,c.remark  from oa_service_accessorder_item c where 1=1 "
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
   		"name" => "warranty",
   		"sql" => " and c.warranty=# "
   	  ),
   array(
   		"name" => "proNum",
   		"sql" => " and c.proNum=# "
   	  ),
   array(
   		"name" => "price",
   		"sql" => " and c.price=# "
   	  ),
   array(
   		"name" => "subCost",
   		"sql" => " and c.subCost=# "
   	  ),
   array(
   		"name" => "remark",
   		"sql" => " and c.remark=# "
   	  )
)
?>