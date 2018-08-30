<?php
$condition_arr = array (
	array (
		"name" => "parentId",
		"sql" => "and c.parentId=#"
	),
	array (
		"name" => "objId",
		"sql" => "and c.objId=#"
	),
	array (
		"name" => "tempId",
		"sql" => "and c.tempId=#"
	),
	array (
		"name" => "nameNotNull",
		"sql" => "and (c.changeFieldCn !='NULL' and  c.changeFieldCn is not null and c.changeFieldCn not like 'code%')"
	),
	array (
		"name" => "detailId",
		"sql" => "and c.detailId=#"
	),
	array (
		"name" => "detailType",
		"sql" => "and c.detailType=#"
	),
	array (
		"name" => "parentType",
		"sql" => "and c.parentType=#"
	),
	array (
		"name" => "objType",
		"sql" => "and c.objType=#"
	),
	array (
		"name" => "isGetUpdate",
		"sql" => "and c.newValue!='��ɾ����' and c.oldValue!='��������'"
	),
	array (
		"name" => "changeManNameSearch",
		"sql" => "and c.changeManName like CONCAT('%',#,'%') "
	),
	array (
		"name" => "changeFieldCn",
		"sql" => "$"
	)
);
?>
