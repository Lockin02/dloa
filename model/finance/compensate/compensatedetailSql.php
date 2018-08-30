<?php
/**
 * @author show
 * @Date 2013年10月24日 19:30:28
 * @version 1.0
 * @description:赔偿单明细 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.mainId ,c.productName ,c.productId ,c.productNo ,
			c.productModel ,c.number ,c.unitName ,c.price ,c.money ,
			c.compensateMoney ,c.compensateRate,c.borrowequId,c.returnequId,
			c.remark,c.serialNos,c.serialIds,c.unitPrice
		from oa_finance_compensate_detail c where 1=1 "
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "mainId",
		"sql" => " and c.mainId=# "
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
		"name" => "number",
		"sql" => " and c.number=# "
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
		"name" => "compensateMoney",
		"sql" => " and c.compensateMoney=# "
	),
	array (
		"name" => "compensateRate",
		"sql" => " and c.compensateRate=# "
	)
)
?>