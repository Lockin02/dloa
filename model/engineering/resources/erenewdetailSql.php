<?php
/**
 * @author show
 * @Date 2013年12月9日 19:17:51
 * @version 1.0
 * @description:续借申请明细 sql配置文件
 */
$sql_arr = array(
    "select_default" => "select c.id ,c.mainId ,c.resourceId ,c.resourceName ,c.resourceTypeId ,c.resourceTypeName ,
			c.coding ,c.dpcoding ,c.number ,c.unit ,c.beginDate ,c.endDate ,c.realDate ,c.resourceListId ,c.borrowItemId ,c.remark ,c.status
		from oa_esm_resource_erenewdetail c where 1=1 "
);

$condition_arr = array(
    array(
        "name" => "id",
        "sql" => " and c.Id=# "
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
        "name" => "unit",
        "sql" => " and c.unit=# "
    ),
    array(
        "name" => "endDate",
        "sql" => " and c.endDate=# "
    ),
    array(
        "name" => "beginDate",
        "sql" => " and c.beginDate=# "
    ),
    array(
        "name" => "realDate",
        "sql" => " and c.realDate=# "
    ),
    array(
        "name" => "remark",
        "sql" => " and c.remark=# "
    ),
	array(
		'name' => 'status',
		'sql' => ' and c.status=# '
	)
);