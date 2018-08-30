<?php
 $sql_arr = array (
	"select_default" => "select c.id,c.parentId,c.name,c.name as text,c.projectId from oa_rd_project_uploadfile_type c where 1=1 "
);


$condition_arr = array (
	array (
		"name" => "parentId",
		"sql" => " and c.parentId = # "
	),
	array (
		"name" => "projectId",
		"sql" => " and c.projectId = # "
	)
);
?>
