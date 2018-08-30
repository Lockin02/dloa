<?php
/**
 * @author Administrator
 * @Date 2012-09-25 09:54:23
 * @version 1.0
 * @description:销售备货物料清单 sql配置文件 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.stockupId ,c.productName ,c.productId ,c.productCode ,c.productModel ,c.productType ,c.projArraDate ,c.conProductId ,c.conProductName ,c.number ,c.remark ,c.price ,c.unitName ,c.money ,c.warrantyPeriod ,c.license ,c.isSell ,c.issuedShipNum ,c.executedShipNum ,c.executedNum ,c.backNum ,c.onWayNum ,c.purchasedNum ,c.issuedPurNum ,c.issuedProNum ,c.applyExchangeNum ,c.exchangeBackNum ,c.changeTips ,c.isTemp ,c.originalId ,c.isDel ,c.isCon ,c.isConfig ,c.isNeedDelivery ,c.outStockDate ,c.isDefault ,c.parentEquId ,c.linkId ,c.isBorrowToorder ,c.toBorrowId ,c.toBorrowequId ,c.arrivalPeriod ,c.serialName ,c.serialId  from oa_sale_stockup_equ c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "stockupId",
   		"sql" => " and c.stockupId=# "
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
   		"name" => "productCode",
   		"sql" => " and c.productCode=# "
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
   		"name" => "conProductId",
   		"sql" => " and c.conProductId=# "
   	  ),
   array(
   		"name" => "conProductName",
   		"sql" => " and c.conProductName=# "
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
   		"name" => "unitName",
   		"sql" => " and c.unitName=# "
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
   		"name" => "license",
   		"sql" => " and c.license=# "
   	  ),
   array(
   		"name" => "isSell",
   		"sql" => " and c.isSell=# "
   	  ),
   array(
   		"name" => "issuedShipNum",
   		"sql" => " and c.issuedShipNum=# "
   	  ),
   array(
   		"name" => "executedShipNum",
   		"sql" => " and c.executedShipNum=# "
   	  ),
   array(
   		"name" => "executedNum",
   		"sql" => " and c.executedNum=# "
   	  ),
   array(
   		"name" => "backNum",
   		"sql" => " and c.backNum=# "
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
   		"name" => "applyExchangeNum",
   		"sql" => " and c.applyExchangeNum=# "
   	  ),
   array(
   		"name" => "exchangeBackNum",
   		"sql" => " and c.exchangeBackNum=# "
   	  ),
   array(
   		"name" => "changeTips",
   		"sql" => " and c.changeTips=# "
   	  ),
   array(
   		"name" => "isTemp",
   		"sql" => " and c.isTemp=# "
   	  ),
   array(
   		"name" => "originalId",
   		"sql" => " and c.originalId=# "
   	  ),
   array(
   		"name" => "isDel",
   		"sql" => " and c.isDel=# "
   	  ),
   array(
   		"name" => "isCon",
   		"sql" => " and c.isCon=# "
   	  ),
   array(
   		"name" => "isConfig",
   		"sql" => " and c.isConfig=# "
   	  ),
   array(
   		"name" => "isNeedDelivery",
   		"sql" => " and c.isNeedDelivery=# "
   	  ),
   array(
   		"name" => "outStockDate",
   		"sql" => " and c.outStockDate=# "
   	  ),
   array(
   		"name" => "isDefault",
   		"sql" => " and c.isDefault=# "
   	  ),
   array(
   		"name" => "parentEquId",
   		"sql" => " and c.parentEquId=# "
   	  ),
   array(
   		"name" => "linkId",
   		"sql" => " and c.linkId=# "
   	  ),
   array(
   		"name" => "isBorrowToorder",
   		"sql" => " and c.isBorrowToorder=# "
   	  ),
   array(
   		"name" => "toBorrowId",
   		"sql" => " and c.toBorrowId=# "
   	  ),
   array(
   		"name" => "toBorrowequId",
   		"sql" => " and c.toBorrowequId=# "
   	  ),
   array(
   		"name" => "arrivalPeriod",
   		"sql" => " and c.arrivalPeriod=# "
   	  ),
   array(
   		"name" => "serialName",
   		"sql" => " and c.serialName=# "
   	  ),
   array(
   		"name" => "serialId",
   		"sql" => " and c.serialId=# "
   	  )
)
?>