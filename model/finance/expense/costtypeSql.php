<?php

/**
 * @author Show
 * @Date 2012年11月2日 星期五 11:43:46
 * @version 1.0
 * @description:费用类型表 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.CostTypeID as id,c.CostTypeID,c.CostTypeName,c.Remark,c.CostTypeLeve,c.ParentCostType,
			c.ParentCostTypeID,c.showDays,c.k3Code,c.k3Name,c.isNew,c.invoiceType,c.invoiceTypeName,c.isReplace,
			c.isEqu,c.ParentCostTypeID as _parentId,c.orderNum,c.isSubsidy,c.isClose,c.budgetType
		from cost_type c where 1 "
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.CostTypeID=# "
	),
	array (
		"name" => "CostTypeID",
		"sql" => " and c.CostTypeID=# "
	),
	array (
		"name" => "CostTypeIDNo",
		"sql" => " and c.CostTypeID <> # "
	),
	array (
		"name" => "CostTypeName",
		"sql" => " and c.CostTypeName=# "
	),
	array (
		"name" => "Remark",
		"sql" => " and c.Remark=# "
	),
	array (
		"name" => "CostTypeLeve",
		"sql" => " and c.CostTypeLeve=# "
	),
	array (
		"name" => "ParentCostType",
		"sql" => " and c.ParentCostType=# "
	),
	array (
		"name" => "ParentCostTypeID",
		"sql" => " and c.ParentCostTypeID=# "
	),
	array (
		"name" => "ParentCostTypeIDs",
		"sql" => " and c.ParentCostTypeID in(arr)"
	),
	array (
		"name" => "showDays",
		"sql" => " and c.showDays=# "
	),
	array (
		"name" => "k3Code",
		"sql" => " and c.k3Code=# "
	),
	array (
		"name" => "k3Name",
		"sql" => " and c.k3Name=# "
	),
	array (
		"name" => "isNew",
		"sql" => " and c.isNew=# "
	),
	array (
		"name" => "invoiceType",
		"sql" => " and c.invoiceType=# "
	),
	array (
		"name" => "invoiceTypeName",
		"sql" => " and c.invoiceTypeName=# "
	),
	array (
		"name" => "isReplace",
		"sql" => " and c.isReplace=# "
	),
	array (
		"name" => "isEqu",
		"sql" => " and c.isEqu=# "
	)
);