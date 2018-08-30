<?php
$sql_arr = array (
	"list" => "select c.id ,c.parentId ,c.taskEquId ,c.purchType ,c.productName ,c.productId ,c.productNumb ,c.pattem ,c.units ,c.auxiliary ,c.amount ,c.remark  from oa_purch_inquiry_equ c where 1=1 ",
	"equ_list" => "select c.id ,c.parentId ,c.taskEquId ,c.purchType ,c.productName ,c.productId ,c.productNumb ,c.pattem ,c.units ,c.amount,i.suppId ," .
			"i.suppName ,i.state ,i.purcherId,i.inquiryCode,se.price,se.id as seId,(te.amountAll-te.contractAmount) as notOrderAmount from oa_purch_inquiry_equ c " .
			"left join oa_purch_inquiry i on i.id=c.parentId " .
			"left join oa_purch_inquiry_supp isu on  (i.suppId=isu.suppId and i.id=isu.parentId)" .
			" left join oa_purch_inquiry_suppequ se on (c.id=se.inquiryEquId and se.parentId=isu.id) " .
			" left join oa_purch_task_equ te on (c.taskEquId=te.id) where 1=1 ",
	"equ_list_progress"=>"select c.id ,c.parentId ,c.taskEquId,c.amount,i.ExaStatus,i.state,date_format(i.createTime,'%Y-%m-%d') as inquiryTime from oa_purch_inquiry_equ c " .
						"left join oa_purch_inquiry i on i.id=c.parentId where 1=1"
);

$condition_arr = array (
	array(
		"name" => "id",	//id 作为查询条件
		"sql" => " and c.id=# "
	),
	array(
		"name" => "parentId",	//id 作为查询条件
		"sql" => " and c.parentId=# "
	),
	array(
		"name" => "parentIdArr",
		"sql" => " and c.parentId in(arr) "
	),
	array(
		"name" => "state",
		"sql" => " and i.state=#"
	),
	array(
		"name" => "purcherId",
		"sql" => " and i.purcherId=#"
	),
	array(
		"name" => "suppId",
		"sql" => " and i.suppId=#"
	),
	array(
		"name" => "suppName",
		"sql" => " and i.suppName=#"
	),
	array(
		"name" => "purchType",
		"sql" => " and c.purchType in(arr)"
	),
	array(
		"name" => "idArr",
		"sql" => " and se.id in(arr)"
	),
	array(
		"name" => "taskEquId",
		"sql" => " and  c.taskEquId in(arr)"
	)
	,array(
		"name" => "productNameSear",
		"sql" => " and c.productName like CONCAT('%',#,'%')"
	)
);
?>