<?php
/**
 * @author liub
 * @Date 2012年3月8日 14:13:30
 * @version 1.0
 * @description: 产品清单 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.conProductName,c.changeTips ," .
         		"c.conProductId ,c.conProductCode ,c.conProductDes ," .
         		"c.presentId ,c.presentCode ,c.presentName ," .
         		"c.number ,c.remark ,c.price ,c.unitName ,c.money ," .
         		"c.warrantyPeriod ,c.license ,c.deploy ,c.backNum  ," .
         		"c.issuedShipNum ,c.executedNum ,c.onWayNum ,c.purchasedNum ," .
         		"c.issuedPurNum ,c.issuedProNum ," .
         		"c.isTemp ,c.originalId ,c.isDel ,c.isCon ," .
         		"c.isConfig ,c.isNeedDelivery  from oa_present_product c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "conProductName",
   		"sql" => " and c.conProductName=# "
   	  ),
   array(
   		"name" => "conProductId",
   		"sql" => " and c.conProductId=# "
   	  ),
   array(
   		"name" => "conProductCode",
   		"sql" => " and c.conProductCode=# "
   	  ),
   array(
   		"name" => "conProductDes",
   		"sql" => " and c.conProductDes=# "
   	  ),
   array(
   		"name" => "presentId",
   		"sql" => " and c.presentId=# "
   	  ),
   array(
   		"name" => "presentCode",
   		"sql" => " and c.presentCode=# "
   	  ),
   array(
   		"name" => "presentName",
   		"sql" => " and c.presentName=# "
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
   		"name" => "deploy",
   		"sql" => " and c.deploy=# "
   	  ),
   array(
   		"name" => "backNum",
   		"sql" => " and c.backNum=# "
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