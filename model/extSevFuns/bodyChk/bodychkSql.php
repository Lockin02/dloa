<?php
/**
 * @author hhj
 * @Date 2017-11-28 13:22:20
 * @version 1.0
 * @description: 体检申请表 sql配置文件
 */
$sql_arr = array (
    "select_default"=>"select *  from oa_extfuns_bodychk_record c where 1=1 "
);

$condition_arr = array (
    array(
        "name" => "id",
        "sql" => " and c.Id=# "
    )
);