<?php
/**
 * @author show
 * @Date 2014��7��26�� 14:59:37
 * @version 1.0
 * @description:������Ŀӳ��� sql�����ļ�
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