<?php
$sql_arr = array(
	"select_roleinfo"=>"select c.id,c.roleName,c.createName,c.createId,c.createDate,c.itemType,c.itemTypeId,c.roleDescription " .
	 "from oa_rd_itemrole c where 1=1",
	 //�����б���˵����
	"select_filterrole"=>"select c.roleName,c.createName,c.createDate,c.itemType " .
	 "from oa_rd_itemrole c where c.itemType = " . "'" . $_GET['itemType'] . "'"
);

$condition_arr = array(
	array(
		"name" => "id",
		"sql" => "and c.id=#"
	),
	array(
		"name" => "roleName",
		"sql" => "and c.roleName"
	),
	array(
		"name" => "itemType",
		"sql" => "and c.itemType"
	)
);


?>