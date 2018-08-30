<?php

/**
 * @author Show
 * @Date 2016年1月27日 星期三 11:07:46
 * @version 1.0
 * @description:报销申请费用明细(部门报销) sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.ID ,c.CostType ,c.CostTypeID ,c.CostMoney ,c.Remark ,c.BillNo ,
			c.MainType ,c.MainTypeId ,c.module ,c.moduleName
		from oa_finance_costshare c where 1=1 "
);

$condition_arr = array (
	array (
		"name" => "ID",
		"sql" => " and c.ID=# "
	),
	array (
		"name" => "CostType",
		"sql" => " and c.CostType=# "
	),
	array (
		"name" => "CostTypeID",
		"sql" => " and c.CostTypeID=# "
	),
	array (
		"name" => "CostMoney",
		"sql" => " and c.CostMoney=# "
	),
	array (
		"name" => "Remark",
		"sql" => " and c.Remark=# "
	),
	array (
		"name" => "BillNo",
		"sql" => " and c.BillNo=# "
	),
	array (
		"name" => "MainType",
		"sql" => " and c.MainType=# "
	),
	array (
		"name" => "MainTypeId",
		"sql" => " and c.MainTypeId=# "
	),
	array (
		"name" => "module",
		"sql" => " and c.module=# "
	),
	array (
		"name" => "moduleName",
		"sql" => " and c.moduleName=# "
	)
)
?>