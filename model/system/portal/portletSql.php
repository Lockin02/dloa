<?php
$sql_arr = array (
	"select_default" => "select c.id ,c.typeId ,c.typeName ,c.portletName," .
	"c.portletName as name,c.url,c.isPerm,c.width,c.height,c.remark," .
	"c.updateId ,c.updateName,c.createId,c.createName,c.createTime,0 as isParent " .
	"from oa_portal_portlet c where 1=1 "
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.id=# "
	),
	array (
		"name" => "ids",
		"sql" => " and c.id in($) "
	),
	array (
		"name" => "typeId",
		"sql" => " and c.typeId=# "
	),
	array (
		"name" => "portletName",
		"sql" => "and c.portletName like CONCAT('%',#,'%')"
	),
	array (
		"name" => "url",
		"sql" => "and c.url like CONCAT('%',#,'%')"
	),
	array (
		"name" => "isPerm",
		"sql" => " and c.isPerm=# "
	)
);
?>