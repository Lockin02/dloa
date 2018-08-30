<?php

$sql_arr = array (
	"select_default"=>"select t.*,c.classifyName as parentName from oa_manufacture_classify c left join oa_manufacture_template t on c.id = t.classifyId where 1=1 "
);

$condition_arr = array (
	array(
		"name" => "classifyName",
		"sql" => " and c.classifyName LIKE CONCAT('%' ,# ,'%') "
	),
	array(
		"name" => "remark",
		"sql" => " and c.remark LIKE CONCAT('%' ,# ,'%') "
	)
)
?>