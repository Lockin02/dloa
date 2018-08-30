<?php
$sql_arr = array(
    "select_default" => "select c.id,c.mainId,c.resourceId,c.resourceName,c.resourceTypeId,c.resourceTypeName,c.resourceListId,c.borrowItemId,
			c.coding,c.dpcoding,c.number,c.confirmNum,(c.number - c.confirmNum) as remainNum,c.unit,c.beginDate,c.endDate,c.status
	  from oa_esm_resource_ereturndetail c where 1=1"
);

$condition_arr = array(
    array(
        "name" => "id",
        "sql" => " and c.id=# "
    ),
    array(
        "name" => "mainId",
        "sql" => " and c.mainId=# "
    ),
    array(
        "name" => "resourceId",
        "sql" => " and c.resourceId=# "
    ),
    array(
        "name" => "resourceName",
        "sql" => " and c.resourceName=# "
    ),
    array(
        "name" => "resourceTypeId",
        "sql" => " and c.resourceTypeId=# "
    ),
    array(
        "name" => "resourceTypeName",
        "sql" => " and c.resourceTypeName=# "
    ),
    array(
        "name" => "coding",
        "sql" => " and c.coding=# "
    ),
    array(
        "name" => "dpcoding",
        "sql" => " and c.dpcoding=# "
    ),
    array(
        "name" => "number",
        "sql" => " and c.number=# "
    ),
	array(
		"name" => "confirmNum",
		"sql" => " and c.confirmNum=# "
	),
    array(
        "name" => "unit",
        "sql" => " and c.unit=# "
    ),
    array(
        "name" => "beginDate",
        "sql" => " and c.beginDate=# "
    ),
    array(
        "name" => "endDate",
        "sql" => " and c.endDate=# "
    ),
	array(
		'name' => 'status',
		'sql' => ' and c.status=# '
	)
);