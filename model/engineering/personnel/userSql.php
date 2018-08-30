<?php
$sql_arr = array (
	"select_user"=>"select c.USER_ID,c.USER_NAME,c.PASSWORD,c.LogName,c.SEX from user c where 1=1",
	"select_simple" => "select c.USER_ID,c.USER_NAME,c.SEX from user c where 1=1"
);
$condition_arr = array (
	array (
		"name" => "USER_ID",
//		"sql" => "and c.USER_ID like CONCAT('%',#,'%')"
		"sql" => "and c.USER_ID=#"
	),
	array (
		"name" => "USER_NAME",
		"sql" => "and c.USER_NAME = #"
	),
	array (
		"name" => "LogName",
		"sql" => "and c.LogName = #"
	),
	array (
		"name" => "SEX",
		"sql" => "and c.SEX = #"
	),
	array (
		"name" => "EMAIL",
		"sql" => "and c.EMAIL = #"
	),
	array (
		"name" => "Company",
		"sql" => "and c.Company = #"
	)
);
?>
