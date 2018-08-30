<?php


/*
 * Created on 2010-12-25
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
$sql_arr = array (
	"select_invcost" => "select c.id,c.pickingId,c.productId,c.productNo,c.productModel,c.productName,c.stockId,c.stockName,c.number from oa_stock_pickingapply_detail c where 1=1 "
);
$condition_arr = array (
	array (
		"name" => "id",
		"sql" => "and c.id =#"
	),
	array (
		"name" => "pickingId",
		"sql" => "and c.pickingId =#"
	),
	array (
		"name" => "stockId",
		"sql" => "and c.stockId =#"
	),
	array (
		"name" => "stockName",
		"sql" => "and c.stockName =#"
	),
	array (
		"name" => "stockName",
		"sql" => "and c.stockName like CONCAT('%',#,'%')"
	),


);
?>
