<?php
$sql_arr = array (
	"select_default" => "select c.id,c.objCode,c.systemCode,c.busiCode,c.parentCode,c.parentId,c.productId,c.productName,".
	"c.createTime,c.createName,c.createId,c.updateTime,c.updateName,c.updateId".
	" from oa_supp_prod_temp c where 1=1 "
);

$condition_arr = array (
	array(
		"name" => "suppProductId", //供应商临时库产品id
		"sql" => "and c.id=#"
	),
	array(
		"name" => "parentId", 
		"sql" => "and c.parentId=#"
	)
);
?>

