<?php
/**
 * @author Administrator
 * @Date 2017��11��13�� 16:19:33
 * @version 1.0
 * @description:�����óɱ����� sql�����ļ�
 */
$sql_arr = array (
    "select_default"=>"select * from oa_borrow_cost c where 1=1 "
);

$condition_arr = array (
    array (
        "name" => "id",
        "sql" => " and c.id = # "
    ),
    array (
        "name" => "ids",
        "sql" => " and c.id in(arr) "
    )
);