<?php
$sql_arr = array (
	"select" => "select c.ID,c.ID as id,c.NameCN,c.NamePT,c.ParentID,c.ParentName,c.ComCard,c.EnFile,c.type
			,c.rand_key from branch_info c where type=1 "
 );
$condition_arr = array (
	array (
		"name" => "id",
		"sql" => "and c.ID =#"
	),
	array (
		"name" => "NameCN",
		"sql" => "and c.NameCN like CONCAT('%',#,'%')"
	),
	array (
		"name" => "NamePT",
		"sql" => "and c.NamePT = #"
	),
	array (
		"name" => "ParentID",
		"sql" => "and c.ParentID = #"
	),
	array (
		"name" => "ParentName",
		"sql" => "and c.ParentName = #"
	),
	array (
		"name" => "ComCard",
		"sql" => "and c.ComCard = #"
	),
	array (
		"name" => "EnFile",
		"sql" => "and c.EnFile = #"
	),
	array (
		"name" => "type",
		"sql" => "and c.type = #"
	),
	array (
		"name" => "rand_key",
		"sql" => "and c.rand_key = #"
	),
);
?>
