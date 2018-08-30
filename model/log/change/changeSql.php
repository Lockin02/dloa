<?php
$sql_arr = array ("select_operation" =>
	"select c.id,c.objTable,c.objId,c.changeManId,c.changeManName
	,c.changeTime,c.changeReason,c.changeLog from oa_rd_change_record c where 1=1 " ,
	"select_count"=> "select count(c.id) as num from oa_rd_change_record c where 1=1 "
);
$condition_arr = array (
	array (
		"name" => "objTable",
		"sql" => "and c.objTable =#"
	),
	array (
		"name" => "objId",
		"sql" => "and c.objId =#"
	)
);
?>
