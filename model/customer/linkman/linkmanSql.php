<?php
$sql_arr = array ("select_linkman" => "select c.id,c2.Name as customerName,
c.customerId,c.linkmanName,c.sex,c.weight,c.age,c.duty,c.remark,
c.height,c.phone,c.mobile,c.address,c.MSN,c.QQ,c2.areaName,
c.email from oa_customer_linkman c left join customer c2 on c.customerId
=c2.id
where 1=1 " );
$condition_arr = array (
	array (
		"name" => "id",
		"sql" => "and c.id=#"
	),
	array (
		"name" => "linkmanName",
		"sql" => "and c.linkmanName like CONCAT('%',#,'%')"
	),
	array (
		"name" => "linkmanNameEqu",
		"sql" => "and c.linkmanName like CONCAT('%',#,'%')"
	),
	array (
		"name" => "customerId",
		"sql" => "and c.customerId =#"
	),
	array (
		"name" => "customerName",
		"sql" => "and c.customerName like CONCAT('%',#,'%')"
	),
	array(
		"name" => "createId",
		"sql" => " and c.createId = #"
	),
	array(
		"name" => "createIdIOr",
		"sql" => " or c.createId = # )"
	),
	array(
		"name" => "areaId",
		"sql" => " and ( c2.areaId in(arr) "
	)
);
?>
