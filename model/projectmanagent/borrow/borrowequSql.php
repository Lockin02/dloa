<?php
/**
 * @author Administrator
 * @Date 2011年5月9日 16:02:12
 * @version 1.0
 * @description:借试用申请产品清单 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.changeTips,c.isTemp,c.linkId,c.arrivalPeriod,c.parentEquId,c.conProductId,c.conProductName,c.id,
			c.borrowId ,c.borrowCode ,c.productLine ,c.productName ,c.productId ,c.productNo ,c.productModel ,
			c.toContractNum,c.productType ,c.number ,c.remark ,c.price ,c.money,c.productNameKS,c.productNoKS,
			c.warrantyPeriod ,c.warrantyPeriod as warranty,c.license,c.unitName ,c.executedNum ,
			c.issuedPurNum ,c.issuedProNum ,c.uniqueCode,c.license,c.unitName,c.issuedShipNum,c.isDel,
			c.isCon,c.isConfig,c.backNum,c.serialName,c.serialId,c.applyBackNum,c.originalId
		from oa_borrow_equ c where 1=1",
	"select_all" => "select c.changeTips,c.isTemp,c.linkId,c.arrivalPeriod,c.parentEquId,c.conProductId,c.conProductName,c.id,
			c.borrowId ,c.borrowCode ,c.productLine ,c.productName ,c.productId ,c.productNo ,c.productModel ,
			c.toContractNum,c.productType ,c.number ,c.remark ,c.price ,c.money,c.productNameKS,c.productNoKS,
			c.warrantyPeriod ,c.warrantyPeriod as warranty,c.license,c.unitName ,c.executedNum ,
			c.issuedPurNum ,c.issuedProNum ,c.uniqueCode,c.license,c.unitName,c.issuedShipNum,c.isDel,
			c.isCon,c.isConfig,c.backNum,c.serialName,c.serialId,c.applyBackNum,c.originalId
		from oa_borrow_equ c where 1",
	"select_closematb" => "select c.changeTips,c.isTemp,c.linkId,c.arrivalPeriod,c.parentEquId,c.conProductId,c.conProductName,c.id,
		c.borrowId ,c.borrowCode ,c.productLine ,c.productName ,c.productId ,c.productNo ,c.productModel ,
		c.toContractNum,c.productType ,c.number ,c.remark ,c.price ,c.money,c.productNameKS,c.productNoKS,
		c.warrantyPeriod ,c.warrantyPeriod as warranty,c.license,c.unitName ,c.executedNum ,
		c.issuedPurNum ,c.issuedProNum ,c.uniqueCode,c.license,c.unitName,c.issuedShipNum,c.isDel,
		c.isCon,c.isConfig,c.backNum,c.serialName,c.serialId,c.applyBackNum," .
		"(c.number-c.executedNum+c.backNum) as contNum ,c.isDel as closeopenVal ,c.isDel as isClose " .
		"from oa_borrow_equ c where c.isTemp = 0 and c.isDel = 0 and (number-executedNum+backNum)=number",
	//借试用设备清单
	"select_equlist" => "select c.id,c.productId,c.productNo,c.productName,c.productModel,
			SUM(c.number) AS number,SUM(c.executedNum) AS executedNum,SUM(c.applyBackNum) AS applyBackNum,
			SUM(c.backNum) AS backNum,GROUP_CONCAT(CAST(c.borrowId AS CHAR)) AS borrowIdArr
		from oa_borrow_equ c LEFT JOIN oa_borrow_borrow b ON c.borrowId = b.id where c.isDel = 0 AND c.isTemp = 0 "
);

$condition_arr = array (
	array (
		"name" => "linkId",
		"sql" => " and c.linkId=# "
	),
	array (
		"name" => "arrivalPeriod",
		"sql" => " and c.arrivalPeriod=# "
	),
	array (
		"name" => "parentEquId",
		"sql" => " and c.parentEquId=# "
	),
	array (
		"name" => "noContProductId",
		"sql" => " and (c.conProductId is null or c.conProductId=0) "
	),
	array (
		"name" => "conProductId",
		"sql" => " and c.conProductId=# "
	),
	array (
		"name" => "conProductName",
		"sql" => " and c.conProductName like CONCAT('%',#,'%')  "
	),
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "notDel",
		"sql" => " and (c.isDel=0 or c.isDel='') "
	),
	array (
		"name" => "isTemp",
		"sql" => " and c.isTemp=# "
	),
	array (
		"name" => "temp",
		"sql" => " and c.isTemp!=0 "
	),
	array (
		"name" => "isDel",
		"sql" => " and c.isDel=#"
	),
	array (
		"name" => "borrowId",
		"sql" => " and c.borrowId=# "
	),
	array (
		"name" => "borrowCode",
		"sql" => " and c.borrowCode=# "
	),
	array (
		"name" => "productLine",
		"sql" => " and c.productLine=# "
	),
	array (
		"name" => "productName",
		"sql" => " and c.productName=# "
	),
	array (
		"name" => "productId",
		"sql" => " and c.productId=# "
	),
	array (
		"name" => "productNo",
		"sql" => " and c.productNo=# "
	),
	array (
		"name" => "productModel",
		"sql" => " and c.productModel=# "
	),
	array (
		"name" => "productType",
		"sql" => " and c.productType=# "
	),
	array (
		"name" => "number",
		"sql" => " and c.number=# "
	),
	array (
		"name" => "remark",
		"sql" => " and c.remark=# "
	),
	array (
		"name" => "price",
		"sql" => " and c.price=# "
	),
	array (
		"name" => "money",
		"sql" => " and c.money=# "
	),
	array (
		"name" => "warrantyPeriod",
		"sql" => " and c.warrantyPeriod=# "
	),
	array (
		"name" => "license",
		"sql" => " and c.license=# "
	),
	array (
		"name" => "executedNumSql",
		"sql" => "$"
	),
	array (
		"name" => "equIds",
		"sql" => " and c.id in(arr) "
	),
   array(
   		"name" => "issuedShipNum",
   		"sql" => " and c.issuedShipNum=# "
   	),
	array(
	    "name" => "serialName",
	    "sql" => " and c.serialName like CONCAT('%',#,'%') "
	),
	array(
		"name" => "serialName2",
		"sql" => "and c.borrowId in (select relDocId from oa_stock_product_serialno where relDocType='oa_borrow_borrow' and sequence like CONCAT('%',#,'%') )"
	),
	//借试用设备清单，连接borrow表条件
	array(
		"name" => "pageUser",
		"sql" => " and (b.createId =# or b.salesNameId = #)"
	),
	array(
		"name" => "borrowLimits",
		"sql" => " and b.limits=# "
	),
	array(
		"name" => "borrowExaStatus",
		"sql" => " and b.ExaStatus=# "
	),
	array(
		"name" => "borrowStatus",
		"sql" => " and b.status=# "
	)
	,
	array (//自定义条件
		"name" => "mySearchCondition",
		"sql" => "$"
	),
	array (
		"name" => "checkIds",
		"sql" => " and c.id not in(arr) "
	)
)
?>