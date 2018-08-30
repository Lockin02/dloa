<?php
/**
 * @author show
 * @Date 2014年7月26日 14:59:37
 * @version 1.0
 * @description:试用项目映射表 sql配置文件
 */
$sql_arr = array(
    "select_default" => "select c.id ,c.projectId ,c.pkProjectId  from oa_esm_project_mapping c where 1=1 "
);

$condition_arr = array(
    array(
        "name" => "id",
        "sql" => " and c.Id=# "
    ),
    array(
        "name" => "projectId",
        "sql" => " and c.projectId=# "
    ),
    array(
        "name" => "pkProjectId",
        "sql" => " and c.pkProjectId=# "
    )
);