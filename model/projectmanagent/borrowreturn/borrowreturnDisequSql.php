<?php
/**
 * @author Administrator
 * @Date 2013��1��15�� 10:17:00
 * @version 1.0
 * @description:�ֹܹ黹�������ݱ����ϴӱ� sql�����ļ�
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.returnId ,c.disposeId ,c.borrowId ,c.equId ,c.returnequId ,c.borrowCode ,
			c.conProductName ,c.conProductId ,c.productLine ,c.productName ,c.productId ,c.productNo ,c.productModel ,
			c.productType ,c.number ,c.remark ,c.unitName ,c.price ,c.money ,c.warrantyPeriod ,c.License ,c.executedNum ,
			c.onWayNum ,c.purchasedNum ,c.issuedPurNum ,c.issuedProNum ,c.uniqueCode ,c.issuedShipNum ,c.backNum ,
			c.serialId ,c.serialName ,c.isDel ,c.originalId ,c.changeTips ,c.isTemp ,c.disposeNum,c.outNum
		from oa_borrow_return_dispose_equ c where 1=1 "
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
		"name" => "returnequId",
		"sql" => " and c.returnequId=# "
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
		"name" => "disposeNum",
		"sql" => " and c.disposeNum=# "
	),
	array (
		"name" => "disNumSql",
		"sql" => " $ "
	)
)
?>