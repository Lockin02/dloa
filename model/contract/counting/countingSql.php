<?php
$sql_arr = array (
	"select_default" => "select * from oa_contract_counting c where 1=1 "
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.id=# "
	),
    array (
        "name" => "countingCheck",
        "sql" => " and c.countingCheck=# "
    ),
    array (
        "name" => "buildCheck",
        "sql" => " and c.buildCheck=# "
    ),
    array (
        "name" => "countingAndBuildChk",
        "sql" => " and ((c.buildCheck=# && c.isTrue <> 1) or c.countingCheck=#) "
    ),
    array (
        "name" => "countingAndBuildChkCorrect",
        "sql" => " and ((c.buildCheck=# or c.isTrue = 1) and c.countingCheck=#) "
    ),
	array(
		"name" => "contractCodeSearch",
		"sql" => " and c.contractCode like CONCAT('%',#,'%')"
	),
	array(
		"name" => "contractNameSearch",
		"sql" => " and c.contractName like CONCAT('%',#,'%')"
	),
    array(
		"name" => "projectCodeSearch",
		"sql" => " and c.projectCode like CONCAT('%',#,'%') "
	),
    array(
		"name" => "projectNameSearch",
		"sql" => " and c.projectName like CONCAT('%',#,'%') "
	),
    array (
        "name" => "isDel",
        "sql" => " and c.isDel=# "
    )
);