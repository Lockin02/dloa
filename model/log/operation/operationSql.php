<?php
$sql_arr = array ("select_operation" => "select c.id,c.objTable,c.objId,c.operateManId,c.operateManName
,c.operateTime,c.operateType,c.operateLog from oa_rd_operation_record c where 1=1 " );
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
