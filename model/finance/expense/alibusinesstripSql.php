<?php
$sql_arr = array (
    "select_default" => "select * from oa_alibusinesstrip_localrecord c where 1=1"
);
$condition_arr = array (
    array (
        "name" => "id",
        "sql" => " and c.id = #"
    ),
    array (
        "name" => "idArr",
        "sql" => " and c.id in(arr)"
    )
);