<?php

/**
 * @author Show
 * @Date 2012年7月31日 20:24:45
 * @version 1.0
 * @description:费用发票明细 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.costDetailId ,c.invoiceTypeId ,c.invoiceMoney ,c.status  from oa_esm_costdetail_invoicedetail c where 1=1 ",
	"count_costinvoice" => "select
			c.invoiceTypeId ,sum(c.invoiceMoney)  as invoiceMoney,sum(invoiceNumber) as invoiceNumber
		from oa_esm_costdetail_invoicedetail c where 1=1"
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "costDetailId",
		"sql" => " and c.costDetailId=# "
	),
	array (
		"name" => "costDetailIds",
		"sql" => " and c.costDetailId in(arr) "
	),
	array (
		"name" => "invoiceTypeId",
		"sql" => " and c.invoiceTypeId=# "
	),
	array (
		"name" => "invoiceMoney",
		"sql" => " and c.invoiceMoney=# "
	),
	array (
		"name" => "status",
		"sql" => " and c.status=# "
	)
)
?>