<?php
/**
 * @author Administrator
 * @Date 2011年11月30日 11:05:26
 * @version 1.0
 * @description:借试用转销售物料处理表 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.businessId ,c.businessEquId ,c.contractType ,c.contractId ,c.contractName ,c.contractCode ,c.objCode ,c.contractEquId ,c.productName ,c.productId ,c.productNo ,c.productModel ,c.productType ,c.projArraDate ,c.number ,c.remark ,c.price ,c.unitName ,c.money ,c.warrantyPeriod ,c.license ,c.isSell ,c.issuedShipNum ,c.executedNum ,c.onWayNum ,c.purchasedNum ,c.issuedPurNum ,c.issuedProNum ,c.uniqueCode ,c.productLine ,c.productLineName ,c.isTemp ,c.originalId ,c.isDel ,c.isCon ,c.isConfig ,c.isNeedDelivery  from oa_borrow_order_equ c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "businessId",
   		"sql" => " and c.businessId=# "
   	  ),
   array(
   		"name" => "businessEquId",
   		"sql" => " and c.businessEquId=# "
   	  ),
   array(
   		"name" => "contractType",
   		"sql" => " and c.contractType=# "
   	  ),
   array(
   		"name" => "contractId",
   		"sql" => " and c.contractId=# "
   	  ),
   array(
   		"name" => "contractName",
   		"sql" => " and c.contractName=# "
   	  ),
   array(
   		"name" => "contractCode",
   		"sql" => " and c.contractCode=# "
   	  ),
   array(
   		"name" => "objCode",
   		"sql" => " and c.objCode=# "
   	  ),
   array(
   		"name" => "contractEquId",
   		"sql" => " and c.contractEquId=# "
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
   		"name" => "productLine",
   		"sql" => " and c.productLine=# "
   	  ),
   array(
   		"name" => "productLineName",
   		"sql" => " and c.productLineName=# "
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
   	  )
)
?>