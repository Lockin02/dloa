<?php

/**
 * 部门折旧分摊脚本配置
 */
$sql_arr = array(
    "select_default" => "select * from oa_bi_asset_share c where 1=1"
);

$condition_arr = array(
    array(
        "name" => "id",
        "sql" => " and c.id = # "
    ),
    array(
        "name" => "deprId",
        "sql" => " and c.deprId = # "
    )
);