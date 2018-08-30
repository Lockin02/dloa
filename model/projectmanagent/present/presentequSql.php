<?php
/**
 * @author Administrator
 * @Date 2011年9月14日 11:54:36
 * @version 1.0
 * @description:新增赠送产品清单 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.changeTips,c.linkId,c.arrivalPeriod,c.conProductId,c.conProductName,c.id ,c.presentId ,c.presentCode ,c.productLine ,c.productName ,c.productId ,c.productNo ,c.productModel ,c.productType ,c.number ,c.remark ,c.unitName ,c.price ,c.money ,c.warrantyPeriod ,c.license ,c.executedNum ,c.onWayNum ,c.purchasedNum ,c.issuedPurNum ,c.issuedProNum ,c.uniqueCode,c.issuedShipNum,c.isDel,c.backNum,c.isTemp,c.originalId  from oa_present_equ c where 1=1 ",
         "select_closematb"=>"select c.changeTips,c.linkId,c.arrivalPeriod,c.conProductId,c.conProductName,c.id ," .
         		"c.presentId ,c.presentCode ,c.productLine ,c.productName ,c.productId ,c.productNo ,c.productModel ," .
         		"c.productType ,c.number ,c.remark ,c.unitName ,c.price ,c.money ,c.warrantyPeriod ,c.license ,c.executedNum ," .
         		"c.onWayNum ,c.purchasedNum ,c.issuedPurNum ,c.issuedProNum ,c.uniqueCode,c.issuedShipNum,c.isDel,c.backNum,c.isTemp,  " .
         		"(c.number-c.executedNum+c.backNum) as contNum ,c.isDel as closeopenVal ,c.isDel as isClose " .
         		"from oa_present_equ c where 1=1 and (number-executedNum+backNum)=number and (number-executedNum+backNum)>0"
);

$condition_arr = array (
	array(
   		"name" => "isTemp",
   		"sql" => " and c.isTemp=# "
        ),
    array(
   		"name" => "temp",
   		"sql" => " and c.isTemp!=0 "
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
   		"name" => "parentEquId",
   		"sql" => " and c.parentEquId=# "
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
   		"sql" => " and c.conProductName like CONCAT('%',#,'%')  "
        ),
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
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
   		"name" => "presentId",
   		"sql" => " and c.presentId=# "
   	  ),
   array(
   		"name" => "presentCode",
   		"sql" => " and c.presentCode=# "
   	  ),
   array(
   		"name" => "productLine",
   		"sql" => " and c.productLine=# "
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
   		"name" => "number",
   		"sql" => " and c.number=# "
   	  ),
   array(
   		"name" => "remark",
   		"sql" => " and c.remark=# "
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
   		"name" => "warrantyPeriod",
   		"sql" => " and c.warrantyPeriod=# "
   	  ),
   array(
   		"name" => "License",
   		"sql" => " and c.License=# "
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
        "name" => "issuedShipNum",
        "sql" => "and c.issuedShipNum=#"
      ),
   array(
        "name" => "equIds",
        "sql" => "and c.id in(arr)"
      )

)
?>