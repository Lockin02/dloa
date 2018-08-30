<?php
/**
 * @author Administrator
 * @Date 2013年1月15日 10:46:55
 * @version 1.0
 * @description:借试用归还物料从表 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.returnId ,c.disposeId ,c.borrowId ,c.equId ,c.borrowCode ,c.conProductName ,
			c.conProductId ,c.productLine ,c.productName ,c.productId ,c.productNo ,c.productModel ,c.productType ,
			c.number ,c.remark ,c.unitName ,c.price ,c.money ,c.warrantyPeriod ,c.License ,c.executedNum ,c.onWayNum ,
			c.purchasedNum ,c.issuedPurNum ,c.issuedProNum ,c.uniqueCode ,c.issuedShipNum ,c.backNum ,c.serialId ,
			c.serialName ,c.isDel ,c.originalId ,c.changeTips ,c.isTemp ,c.disposeNumber,c.qualityNum,c.qPassNum,
			c.qBackNum,c.compensateNum,c.outNum,c.outNum as outedNum
		from oa_borrow_return_equ c where 1=1 ",
	"select_equinfo" => "select c.id ,c.returnId ,c.disposeId ,c.borrowId ,c.equId ,c.borrowCode ,c.conProductName ,
			c.conProductId ,c.productLine ,c.productName ,c.productId ,c.productNo ,c.productModel ,c.productType ,
			c.number ,c.remark ,c.unitName ,c.price ,c.money ,c.warrantyPeriod ,c.License ,c.executedNum ,c.onWayNum ,
			c.purchasedNum ,c.issuedPurNum ,c.issuedProNum ,c.uniqueCode ,c.issuedShipNum ,c.backNum ,c.serialId ,
			c.serialName ,c.isDel ,c.originalId ,c.changeTips ,c.isTemp ,c.disposeNumber,c.qualityNum,c.qPassNum,
			c.qBackNum,c.compensateNum,c.outNum,c.outNum as outedNum
		from oa_borrow_return_equ c
		where 1=1 and c.number-c.disposeNumber>0",
	"select_compensate" => "select c.id as returnequId,c.returnId ,c.disposeId ,c.borrowId ,c.equId ,c.borrowCode ,c.conProductName ,
			c.conProductId ,c.productLine ,c.productName ,c.productId ,c.productNo ,c.productModel ,c.productType ,
			c.number ,c.remark ,c.unitName ,c.price ,c.money ,c.warrantyPeriod ,c.License ,c.executedNum ,c.onWayNum ,
			c.purchasedNum ,c.issuedPurNum ,c.issuedProNum ,c.uniqueCode ,c.issuedShipNum ,c.backNum ,c.serialId ,
			c.serialName ,c.isDel ,c.originalId ,c.changeTips ,c.isTemp ,c.disposeNumber,c.qualityNum,c.qPassNum,
			c.qBackNum,c.compensateNum,c.outNum,c.outNum as outedNum
		from oa_borrow_return_equ c
		where 1"
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "equIdArr",
		"sql" => " and c.id in(arr) "
	),
	array (
		"name" => "returnId",
		"sql" => " and c.returnId=# "
	),
	array (
		"name" => "disposeId",
		"sql" => " and c.disposeId=# "
	),
	array (
		"name" => "borrowId",
		"sql" => " and c.borrowId=# "
	),
	array (
		"name" => "equId",
		"sql" => " and c.equId=# "
	),
	array (
		"name" => "borrowCode",
		"sql" => " and c.borrowCode=# "
	),
	array (
		"name" => "conProductName",
		"sql" => " and c.conProductName=# "
	),
	array (
		"name" => "conProductId",
		"sql" => " and c.conProductId=# "
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
		"name" => "unitName",
		"sql" => " and c.unitName=# "
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
		"name" => "License",
		"sql" => " and c.License=# "
	),
	array (
		"name" => "executedNum",
		"sql" => " and c.executedNum=# "
	),
	array (
		"name" => "onWayNum",
		"sql" => " and c.onWayNum=# "
	),
	array (
		"name" => "purchasedNum",
		"sql" => " and c.purchasedNum=# "
	),
	array (
		"name" => "issuedPurNum",
		"sql" => " and c.issuedPurNum=# "
	),
	array (
		"name" => "issuedProNum",
		"sql" => " and c.issuedProNum=# "
	),
	array (
		"name" => "uniqueCode",
		"sql" => " and c.uniqueCode=# "
	),
	array (
		"name" => "issuedShipNum",
		"sql" => " and c.issuedShipNum=# "
	),
	array (
		"name" => "backNum",
		"sql" => " and c.backNum=# "
	),
	array (
		"name" => "serialId",
		"sql" => " and c.serialId=# "
	),
	array (
		"name" => "serialName",
		"sql" => " and c.serialName=# "
	),
	array (
		"name" => "isDel",
		"sql" => " and c.isDel=# "
	),
	array (
		"name" => "originalId",
		"sql" => " and c.originalId=# "
	),
	array (
		"name" => "changeTips",
		"sql" => " and c.changeTips=# "
	),
	array (
		"name" => "isTemp",
		"sql" => " and c.isTemp=# "
	),
	array (
		"name" => "disposeNumber",
		"sql" => " and c.disposeNumber=# "
	),
	array (
		"name" => "numSql",
		"sql" => "$"
	)
);