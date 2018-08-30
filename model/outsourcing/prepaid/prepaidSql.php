<?php
/**
 * @author Acan
 * @Date 2014年10月30日 11:03:15
 * @version 1.0
 * @description:外包预提 sql配置文件
 */
$sql_arr = array(
    "select_default" => "select *
	    from oa_outsourcing_nprepaid c
	    where 1=1 "
);

$condition_arr = array(
    array(
        "name" => "id",
        "sql" => " and c.id=# "
    ),
    array(
        "name" => "contractId",
        "sql" => " and c.contractId=# "
    ),
    array(
        "name" => "contractCode",
        "sql" => " and c.contractCode=# "
    ),
    array(
        "name" => "signCode",
        "sql" => " and c.signCode=# "
    )
);