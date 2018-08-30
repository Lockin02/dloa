<?php
/**
 * @author show
 * @Date 2013年10月10日 17:07:13
 * @version 1.0
 * @description:外包合同整包分包表 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.mainId ,c.costTypeName ,c.costType ,c.parent ,c.parentName ,c.feeType ,
            c.price ,c.number ,c.period ,c.amount ,c.supplierName ,c.remark ,c.isSelf ,
            c.isServerCost ,c.isChoosed ,c.groupKey,c.sysNo,c.isDetail,c.isCustom
        from oa_sale_outsourcing_projectrental c where 1=1 "
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
		"name" => "costTypeName",
		"sql" => " and c.costTypeName=# "
	),
	array (
		"name" => "costType",
		"sql" => " and c.costType=# "
	),
	array (
		"name" => "parent",
		"sql" => " and c.parent=# "
	),
	array (
		"name" => "parentName",
		"sql" => " and c.parentName=# "
	),
	array (
		"name" => "feeType",
		"sql" => " and c.feeType=# "
	),
	array (
		"name" => "price",
		"sql" => " and c.price=# "
	),
	array (
		"name" => "number",
		"sql" => " and c.number=# "
	),
	array (
		"name" => "period",
		"sql" => " and c.period=# "
	),
	array (
		"name" => "amount",
		"sql" => " and c.amount=# "
	),
	array (
		"name" => "supplierName",
		"sql" => " and c.supplierName=# "
	),
	array (
		"name" => "remark",
		"sql" => " and c.remark=# "
	),
	array (
		"name" => "isSelf",
		"sql" => " and c.isSelf=# "
	),
	array (
		"name" => "isServerCost",
		"sql" => " and c.isServerCost=# "
	),
	array (
		"name" => "isChoosed",
		"sql" => " and c.isChoosed=# "
	),
	array (
		"name" => "isCustom",
		"sql" => " and c.isCustom=# "
	),
	array (
		"name" => "isDetail",
		"sql" => " and c.isDetail=# "
	)
)
?>