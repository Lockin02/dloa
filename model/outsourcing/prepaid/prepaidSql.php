<?php
/**
 * @author Acan
 * @Date 2014��10��30�� 11:03:15
 * @version 1.0
 * @description:���Ԥ�� sql�����ļ�
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