<?php
/*
 * Created on 2010-12-25
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
$sql_arr = array (
     "select_default"=>"select c.id ,c.invoiceId ,c.productName ,c.productId ,
     		c.amount ,c.softMoney ,c.hardMoney ,c.softMoney,c.serviceMoney ,c.repairMoney ,c.psType,c.otherMoney,
     		c.dsEnergyCharge,c.dsWaterRateMoney,c.houseRentalFee,c.installationCost
     	from oa_finance_invoice_detail c where 1=1 ",
     "select_easy"=>"select c.invoiceId ,c.productName ,c.amount ,c.psType  from oa_finance_invoice_detail c where 1=1 ",
     "select_group" => "select c.id,c.invoiceId ,group_concat(c.productName) as productName,sum(c.amount) as amount ,
     		group_concat(d.dataName) as psType
     	from oa_finance_invoice_detail c left join oa_system_datadict d on c.psType = d.dataCode where 1=1 "
);
$condition_arr = array (
	array (
		"name" => "invoiceIds",
		"sql" => "and c.invoiceId in(arr)"
	),array (
		"name" => "invoiceId",
		"sql" => "and c.invoiceId = #"
	),
	array(
		"name" => "parentCode",
		"sql" => "and d.parentCode = #"
	)
);