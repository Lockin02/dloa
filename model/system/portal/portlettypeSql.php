<?php
$sql_arr = array (
	"select_default" => "select c.id ,c.typeName ,c.typeName as name,c.parentId ,c.parentName,c.remark," .
	"c.updateId ,c.updateName,c.createId,c.createName,c.createTime,'true' as isParent " .
	"from oa_portal_portlet_type c where id!=-1 "
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.parentId=# "
	),
	array (
		"name" => "parentId",
		"sql" => " and c.parentId=# "
	),
	array (
		"name" => "typeName",
		"sql" => "and c.typeName like CONCAT('%',#,'%')"
	),
	array (
		"name" => "parentName",
		"sql" => "and c.parentName like CONCAT('%',#,'%')"
	)
);
?>