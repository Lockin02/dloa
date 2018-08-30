<?php


/*
 * Created on 2010-12-25
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
$sql_arr = array (
	"select_invcost" => "select c.id,c.costId,c.invCostId,c.costCode,c.costName,c.costType,c.unit,c.number,c.price,c.amount,c.rate,c.assessment,c.notAssAmount,c.remark from oa_finance_invcost_detail c where 1=1 "
);
$condition_arr = array (
	array (
		"name" => "id",
		"sql" => "and c.id =#"
	),
	array (
		"name" => "costId",
		"sql" => "and c.costId =#"
	),
	array (
		"name" => "invCostId",
		"sql" => "and c.invCostId =#"
	),
	array (
		"name" => "costCode",
		"sql" => "and c.costCode like CONCAT('%',#,'%')"
	),


);
?>
