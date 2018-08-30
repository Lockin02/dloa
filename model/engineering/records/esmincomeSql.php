<?php
/**
 * @author show
 * @Date 2014年12月25日 16:07:45
 * @version 1.0
 * @description:项目现场决算 sql配置文件
 */
$sql_arr = array (
    "select_default"=>"select * from oa_esm_record_income c where 1=1 "
);

$condition_arr = array (
    array(
        "name" => "id",
        "sql" => " and c.id=# "
    ),
    array(
        "name" => "projectId",
        "sql" => " and c.projectId=# "
    ),
    array(
        "name" => "versionNo",
        "sql" => " and c.versionNo=# "
    )
);