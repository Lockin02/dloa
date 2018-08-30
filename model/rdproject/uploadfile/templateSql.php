<?php
 $sql_arr = array (
	"select_default" => "select c.id,c.parentId,c.name,c.name as text,c.projectType from oa_rd_project_uploadfile_template c where 1=1 "
);


$condition_arr = array (
	array (
		"name" => "parentId",
		"sql" => " and c.parentId = # "
	),
	array (
		"name" => "projectType",
		"sql" => " and c.projectType = # "
	)
);
?>
