<?php
/**
 * @author Administrator
 * @Date 2011年12月3日 10:33:06
 * @version 1.0
 * @description:维修费用减免清单 sql配置文件 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.mainId ,c.applyItemId ,c.productTypeId ,c.productType ,c.productId ,c.productCode ,c.productName ,c.pattern ,c.unitName ,c.serilnoName ,c.fittings ,c.cost ,c.reduceCost ,c.remark  from oa_service_reduce_item c where 1=1 "
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
   		"name" => "applyItemId",
   		"sql" => " and c.applyItemId=# "
   	  ),   	  
   array(
   		"name" => "productTypeId",
   		"sql" => " and c.productTypeId=# "
   	  ),
   array(
   		"name" => "productType",
   		"sql" => " and c.productType=# "
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
   		"name" => "serilnoName",
   		"sql" => " and c.serilnoName=# "
   	  ),
   array(
   		"name" => "fittings",
   		"sql" => " and c.fittings=# "
   	  ),
   array(
   		"name" => "cost",
   		"sql" => " and c.cost=# "
   	  ),
   array(
   		"name" => "reduceCost",
   		"sql" => " and c.reduceCost=# "
   	  ),
   array(
   		"name" => "remark",
   		"sql" => " and c.remark=# "
   	  )
)
?>