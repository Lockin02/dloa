<?php
$sql_arr = array (
	"select_datadict" => "select c.id,c.userId,c.customCode,c.colName,c.colIndex,c.colWidth
		,c.isShow from oa_system_gridcustom c where 1=1"
);
$condition_arr = array (
    array (
        "name" => "userId",
        "sql" => "and c.userId = #"
    ),
    array (
        "name" => "customCode",
        "sql" => "and c.customCode = #"
    ),
    array (
        "name" => "colName",
        "sql" => "and c.colName = #"
    )
);
?>
