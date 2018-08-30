<?php

/**
 * 部门费用脚本配置
 */
$sql_arr = array(
    "select_default" => "select * from oa_bi_asset_depreciation c where 1=1"
);

$condition_arr = array(
    array(
        "name" => "id",
        "sql" => " and c.id = # "
    ),
    array(
        "name" => "businessSearch",
        "sql" => " and c.business LIKE CONCAT('%', #, '%') "
    )
);