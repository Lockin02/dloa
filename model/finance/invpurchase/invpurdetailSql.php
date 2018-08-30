<?php

/**
 * @author Show
 * @Date 2010年12月21日 星期二 15:54:46
 * @version 1.0
 * @description:采购发票条目 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.invPurId ,c.productName ,c.productNo ,c.productId ,c.number ,c.price ,c.amount ,c.status,c.productModel,c.belongId,c.hookAmount,c.hookNumber,c.unHookAmount,c.unHookNumber,c.allCount,c.assessment,c.unit,c.taxPrice,c.objId,c.objCode,c.objType,c.contractId,c.contractCode,c.objCode as dObjCode from oa_finance_invpurchase_detail c where 1=1 ",
	"select_left" => "select c.id ,c.invPurId ,c.productName ,c.productNo ,c.productId ,c.number ,c.price ,c.amount ,
			c.status,c.productModel,c.belongId,c.hookAmount,c.hookNumber,c.unHookAmount,c.unHookNumber,
			c.allCount,c.assessment,c.unit,c.taxPrice,c.objId,c.objCode,c.objType,c.contractId,c.contractCode,
			c.objCode as dObjCode,i.formType,i.objNo as formNo,i.invType,i.formDate,i.objCode as formCode
		from oa_finance_invpurchase_detail c left join oa_finance_invpurchase i on c.invpurId = i.id where 1=1 ",
	"easy_list" => "select c.id ,c.invPurId ,c.productName ,c.productNo ,c.productId ,c.number ,c.price ,c.amount ,c.unit,c.productModel,c.belongId,c.allCount,c.assessment,c.objId,c.objCode,c.objType,c.objCode as dObjCode  from oa_finance_invpurchase_detail c where 1=1 ",
	"isAllHook" => "select sum(c.unHookNumber) as allunHookNumber from oa_finance_invpurchase_detail c where 1=1 ",
	"select_sum" => "select c.id ,c.productId,c.expand1,sum(c.number) as allnumber,c.objId,c.objType from oa_finance_invpurchase_detail c "
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.id=# "
	),
	array (
		"name" => "invIds",
		"sql" => " and c.invPurId in(arr) "
	),
	array (
		"name" => "invPurId",
		"sql" => " and c.invPurId=# "
	),
	array (
		"name" => "invPurCode",
		"sql" => " and c.invPurCode=# "
	),
	array (
		"name" => "number",
		"sql" => " and c.number=# "
	),
	array (
		"name" => "unit",
		"sql" => " and c.unit=# "
	),
	array (
		"name" => "price",
		"sql" => " and c.price=# "
	),
	array (
		"name" => "rate",
		"sql" => " and c.rate=# "
	),
	array (
		"name" => "assessment",
		"sql" => " and c.assessment=# "
	),
	array (
		"name" => "taxPrice",
		"sql" => " and c.taxPrice=# "
	),
	array (
		"name" => "discount",
		"sql" => " and c.discount=# "
	),
	array (
		"name" => "discAmount",
		"sql" => " and c.discAmount=# "
	),
	array (
		"name" => "actDiscPrice",
		"sql" => " and c.actDiscPrice=# "
	),
	array (
		"name" => "amount",
		"sql" => " and c.amount=# "
	),
	array (
		"name" => "accruedCosts",
		"sql" => " and c.accruedCosts=# "
	),
	array (
		"name" => "regardlessCosts",
		"sql" => " and c.regardlessCosts=# "
	),
	array (
		"name" => "freightTaxes",
		"sql" => " and c.freightTaxes=# "
	),
	array (
		"name" => "status",
		"sql" => " and c.status=# "
	),
	array (
		"name" => "objIds",
		"sql" => " and c.objId in(arr) "
	),
	array (
		"name" => "objType",
		"sql" => " and c.objType=# "
	),
	array (
		"name" => "contractIds",
		"sql" => " and c.contractId in(arr) "
	)
)
?>