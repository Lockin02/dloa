<?php

/**
 * @author Administrator
 * @Date 2011年10月26日 15:47:12
 * @version 1.0
 * @description:货币换算 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.Currency ,c.rate ,c.standard,c.currencyCode  from oa_system_currency c where 1=1 "
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "Currency",
		"sql" => " and c.Currency=# "
	),
	array (
		"name" => "currencyCode",
		"sql" => " and c.currencyCode=# "
	),
	array (
		"name" => "ajaxCurrency",
		"sql" => " and c.Currency=# "
	),
	array (
		"name" => "rate",
		"sql" => " and c.rate=# "
	),
	array (
		"name" => "standard",
		"sql" => " and c.standard=# "
	)
)
?>