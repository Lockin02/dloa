<?php
/**
 * sql config
 */
$sql_arr = array(
    "select_default" => "select * from oa_contract_node c where 1=1 ",
);

$condition_arr = array(
    array(
        "name" => "id",
        "sql" => " and c.id=# "
    )
);