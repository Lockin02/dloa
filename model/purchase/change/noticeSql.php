<?php
$sql_arr = array (
	"list" => " select c.id,c.modelCode,c.changeNumb,c.basicNumb,c.basicId,c.subject,c.reason,c.remark,
	c.noticerId,c.ExaStatus,c.ExaDT,c.state,c.createId,c.createName,c.createTime,c.updateId,c.updateName
	,c.updateTime from oa_purch_change_notice c where 1=1 ",
	"contract_list" => "select "

);

$condition_arr = array (
	array(
		"name" => "state",
		"sql" => " and c.state=# "
	)
//	,array(
//		"name" => "subject",
//		"sql" => " and c.subject = # "
//	)
	,array(
		"name" => "basicNumb",
		"sql" => " and c.basicNumb like CONCAT('%',#,'%')"
	)
	,array(
		"name" => "changeNumb",
		"sql" => " and c.changeNumb like CONCAT('%',#,'%')"
	),
	array(
		"name" => "modelCode",
		"sql" => " and c.modelCode = #"
	)
);
?>