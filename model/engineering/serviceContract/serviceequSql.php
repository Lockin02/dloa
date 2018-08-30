<?php
/**
 * @author Administrator
 * @Date 2011年5月5日 10:02:07
 * @version 1.0
 * @description:服务合同配置清单 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.productName ,c.productId ,c.productLine,c.productNo ,c.productModel ,c.productType ,c.unitName,c.orderCode ,c.orderId ,c.orderName ,c.number ,c.remark ,c.price ,c.money ,c.warrantyPeriod,c.license,c.isTemp,c.executedNum,c.originalId,c.issuedShipNum,c.issuedPurNum,c.issuedProNum,c.uniqueCode,c.isSell,c.purchasedNum,c.isDel,c.isCon,c.isConfig,c.changeTips,c.isBorrowToorder,c.backNum from oa_service_equ c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "isDel",
   		"sql" => " and c.isDel=# "
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
   		"name" => "productNo",
   		"sql" => " and c.productNo=# "
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
   		"name" => "number",
   		"sql" => " and c.number=# "
   	  ),
   array(
   		"name" => "remark",
   		"sql" => " and c.remark=# "
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
   		"name" => "warrantyPeriod",
   		"sql" => " and c.warrantyPeriod=# "
   	  ),
   array(
   		"name" => "executedNum",
   		"sql" => " and c.executedNum=# "
   	  ),
   array(
   		"name" => "加密配置",
   		"sql" => " and c.加密配置=# "
   	  ),
   array(
        "name" => "isBorrowToorder",
        "sql" => "and c.isBorrowToorder=#"
   )
)
?>