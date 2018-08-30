<?php
/*
 * Created on 2010-7-17
 *	产品类型信息SQL
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 $sql_arr = array (
	"select_producttype" => "select c.id,c.proType as text,c.typecode,c.parentId,c.parentName,c.orderNum,c.leaf from oa_stock_product_type c where c.id<>-1 and 1=1 "
	);
$condition_arr = array (
	array (
		"name" => "proType",
		"sql" => "and c.proType like CONCAT('%',#,'%')"
	),
	array(
		"name" => "parentId",
		"sql"=>"and c.parentId =#"
	),
	array(
		"name"=>"parentName",
		"sql"=>"and c.parentName like CONCAT('%',#,'%')"
	),
	array(
		"name"=>"typecode",
		"sql"=>"and c.typecode like CONCAT('%',#,'%')"
	)
	)
?>
