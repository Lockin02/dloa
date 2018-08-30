<?php
/**
 * @author Administrator
 * @Date 2011年6月27日 9:47:27
 * @version 1.0
 * @description:自定义清单 sql配置文件 自定义清单

 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.orderCode ,c.orderId ,c.orderName ,c.productCode ,c.productName ,c.productModel ,c.productType ,c.number ,c.unitName ,c.price ,c.money ,c.productLine ,c.isSell ,c.projArraDT ,c.remark,c.license  from oa_sale_lease_customizelist c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "orderCode",
   		"sql" => " and c.orderCode=# "
   	  ),
   array(
   		"name" => "orderId",
   		"sql" => " and c.orderId=# "
   	  ),
   array(
   		"name" => "orderName",
   		"sql" => " and c.orderName=# "
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
   		"name" => "productType",
   		"sql" => " and c.productType=# "
   	  ),
   array(
   		"name" => "number",
   		"sql" => " and c.number=# "
   	  ),
   array(
   		"name" => "unitName",
   		"sql" => " and c.unitName=# "
   	  ),
   array(
   		"name" => "price",
   		"sql" => " and c.price=# "
   	  ),
   array(
   		"name" => "money",
   		"sql" => " and c.money=# "
   	  ),
   array(
   		"name" => "productLine",
   		"sql" => " and c.productLine=# "
   	  ),
   array(
   		"name" => "isSell",
   		"sql" => " and c.isSell=# "
   	  ),
   array(
   		"name" => "projArraDT",
   		"sql" => " and c.projArraDT=# "
   	  ),
   array(
   		"name" => "remark",
   		"sql" => " and c.remark=# "
   	  )
)
?>