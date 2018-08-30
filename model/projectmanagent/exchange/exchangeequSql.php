<?php
/**
 * @author liub
 * @Date 2012-04-07 14:03:11
 * @version 1.0
 * @description:换货物料清单 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.deploy ,c.id ,c.exchangeObjCode ,c.exchangeId ,c.productName ,c.productId ,c.productCode ,c.productModel ,c.productType ,c.projArraDate ,c.conProductId ,c.conProductName ,c.version ,c.number ,c.remark ,c.price ,c.unitName ,c.money ,c.warrantyPeriod ,c.license ,c.isSell ,c.issuedShipNum ,c.executedShipNum ,c.executedNum ,c.backNum ,c.onWayNum ,c.purchasedNum ,c.issuedPurNum ,c.issuedProNum ,c.applyExchangeNum ,c.exchangeBackNum ,c.uniqueCode ,c.productLine ,c.productLineName ,c.changeTips ,c.isTemp ,c.originalId ,c.isDel ,c.isCon ,c.isConfig ,c.isNeedDelivery ,c.outStockDate ,c.isDefault ,c.parentEquId ,c.linkId ,c.arrivalPeriod from oa_contract_exchange_equ c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "equIdArr",
   		"sql" => " and c.Id in(arr) "
   	  ),
   array(
   		"name" => "exchangeObjCode",
   		"sql" => " and c.exchangeObjCode=# "
   	  ),
   array(
   		"name" => "exchangeId",
   		"sql" => " and c.exchangeId=# "
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
   		"name" => "noContProductId",
   		"sql" => " and (c.conProductId is null or c.conProductId=0) "
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
   		"name" => "deploy",
   		"sql" => " and c.deploy=# "
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
   		"name" => "uniqueCode",
   		"sql" => " and c.uniqueCode=# "
   	  ),
   array(
   		"name" => "productLine",
   		"sql" => " and c.productLine=# "
   	  ),
   array(
   		"name" => "productLineName",
   		"sql" => " and c.productLineName=# "
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
   		"name" => "temp",
   		"sql" => " and c.isTemp!=0 "
   	  ),
   array(
   		"name" => "originalId",
   		"sql" => " and c.originalId=# "
   	  ),
   array(
   		"name" => "notDel",
   		"sql" => " and (c.isDel=0 or c.isDel='') "
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
   		"name" => "arrivalPeriod",
   		"sql" => " and c.arrivalPeriod=# "
   	  ),
   array(
   		"name" => "equIds",
   		"sql" => " and c.id in(arr) "
   	  )
)
?>