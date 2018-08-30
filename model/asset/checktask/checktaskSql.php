<?php
$sql_arr = array ("select_checktask" => "select c.id,c.billNo,c.checkDate,c.endDate,c.deptId,c.deptName,c.initiatorId,c.initiator,c.participantId,c.participant,c.remark from oa_asset_checktask c where 1=1 " );
$condition_arr = array (
	array (
		"name" => "id",
		"sql" => "and c.id=#"
	),
	array (
		"name" => "billNo",
		"sql" => "and c.billNo like CONCAT('%',#,'%')"
	),
	array (
		"name" => "checkDate",
		"sql" => "and c.checkDate like CONCAT('%',#,'%')"
	),
	array(
		"name" => "endDate",
		"sql" => " and c.endDate = #"
	),
	array (
		"name" => "deptId",
		"sql" => "and c.deptId =#"
	),
	array (
		"name" => "deptNameEq",
		"sql" => "and c.deptNameEq =#"
	),
	array (
		"name" => "deptName",
		"sql" => "and c.deptName like CONCAT('%',#,'%')"
	),
	array(
		"name" => "initiatorId",
		"sql" => " and c.initiatorId = # "
	),
	array(
		"name" => "initiator",
		"sql" => " and  c.initiator = #"
	),
	array (
		"name" => "participantId",
		"sql" => "and c.participantId =#"
	),
	array(
		"name" => "participant",
		"sql" => " and c.participant = #"
	),  array(
   		"name" => "remark",
   		"sql" => " and c.remark=# "
   	  )
);
?>
