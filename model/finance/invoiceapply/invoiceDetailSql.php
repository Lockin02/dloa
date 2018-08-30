<?php
$sql_arr = array (
	"select_default" => "select c.id ,c.invoiceApplyId ,c.productName ,c.productId ,
			c.amount ,c.softMoney ,c.hardMoney ,c.serviceMoney ,c.psTyle ,
			c.remark ,c.repairMoney ,c.equRentalMoney ,c.spaceRentalMoney ,c.otherMoney
		from oa_finance_invoiceapply_detail c where 1=1 "
);