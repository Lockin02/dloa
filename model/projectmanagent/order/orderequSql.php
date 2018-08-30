<?php
/**
 * @author Show
 * @Date 2011年3月14日 19:31:12
 * @version 1.0
 * @description:订单产品清单 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.productLine,c.productLineName,c.productName ,c.productId ,c.productNo ,c.orderId,c.productModel ,c.productType ,c.projArraDate ,c.orderCode ,c.orderName ,c.version ,c.number ,c.remark ,c.price ,c.money ,c.warrantyPeriod ,c.isSell ,c.executedNum ,c.onWayNum ,c.purchasedNum ,c.issuedPurNum ,c.issuedProNum ,c.uniqueCode,c.license,c.unitName,c.isTemp,c.originalId,c.issuedShipNum,c.purchasedNum,c.isDel,c.isCon,c.isConfig,c.changeTips,c.isBorrowToorder,c.backNum  from oa_sale_order_equ c where 1=1 ",
         "select_inventory" => "select c.id ,c.productLine,c.productName ,c.productId ,c.productNo ,c.productModel ,c.productType ,c.projArraDate ,c.orderCode ,c.orderName ,c.version ,c.number ,c.remark ,c.price ,c.money ,c.warrantyPeriod ,c.isSell ,c.executedNum ,c.onWayNum ,c.purchasedNum ,c.issuedPurNum ,c.issuedProNum ,c.uniqueCode,c.license ,l.exeNum from oa_sale_order_equ c left join oa_stock_inventory_info l on l.stockName='销售仓' where c.productId = l.productId"
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
   		"name" => "projArraDate",
   		"sql" => " and c.projArraDate=# "
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
   		"name" => "version",
   		"sql" => " and c.version=# "
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
   		"name" => "isSell",
   		"sql" => " and c.isSell=# "
   	  ),
   array(
   		"name" => "executedNum",
   		"sql" => " and c.executedNum=# "
   	  ),
   array(
   		"name" => "onWayNum",
   		"sql" => " and c.onWayNum=# "
   	  ),
   array(
   		"name" => "purchasedNum",
   		"sql" => " and c.purchasedNum=# "
   	  ),
   array(
   		"name" => "issuedPurNum",
   		"sql" => " and c.issuedPurNum=# "
   	  ),
   array(
   		"name" => "issuedProNum",
   		"sql" => " and c.issuedProNum=# "
   	  ),
   array(
   		"name" => "uniqueCode",
   		"sql" => " and c.uniqueCode=# "
   	  ),
   array(
        "name" => "isBorrowToorder",
        "sql" => "and c.isBorrowToorder=#"
   )
)
?>