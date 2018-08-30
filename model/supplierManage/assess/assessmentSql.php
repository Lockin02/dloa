<?php
$sql_arr = array(
	"select_default" => "select " .
				"c.id,c.assesName,c.beginDate,c.endDate,c.assesTotal,c.assesType,c.assesRemarks,c.status,c.manageId,c.manageName,c.createId,c.createName,c.createTime,c.updateId,c.updateName,c.updateTime " .
				" from oa_supp_assessment c " .
			" where 1=1 ",
	"select_MyManage" => " select " .
				"c.id,c.assesName,c.beginDate,c.endDate,c.assesTotal,c.assesType,c.assesRemarks,c.status,c.manageId,c.manageName,c.createId,c.createName,c.createTime,c.updateId,c.updateName,c.updateTime " .
				" from oa_supp_assessment c " .
			" where 1=1 "
);


$condition_arr = array(
	array(
		"name" => "id", //方案Id
		"sql" => "and c.id=#"
	),
	array(
		"name" => "manageId", //负责人Id
		"sql" => "and c.manageId=#"
	),
	array(
		"name" => "statusArr", //状态
		"sql" => " and c.status in(arr) "
	),
	array(
		"name" => "assesNameSeach", //搜索字段，评估方案名称
		"sql" => " and c.assesName like CONCAT('%',#,'%') "
	),
	array(
		"name" => "createNameSeach", //搜索字段，创建人名称
		"sql" => " and c.createName like CONCAT('%',#,'%') "
	),

);
?>
