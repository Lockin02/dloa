<?php
/**
 * @author show
 * @Date 2014��6��20��
 * @version 1.0
 * @description:��Ŀ�豸���������¼ sql�����ļ�
 */
$sql_arr = array(
    "select_default" => "select c.id ,c.applyId ,c.userId ,c.userName ,c.operationType ,c.operationTime ,c.description  from oa_esm_resource_log c where 1=1 "
);

$condition_arr = array(
    array(
        "name" => "id",
        "sql" => " and c.Id=# "
    ),
    array(
        "name" => "applyId",
        "sql" => " and c.applyId=# "
    ),
    array(
        "name" => "userId",
        "sql" => " and c.userId=# "
    ),
    array(
        "name" => "userName",
        "sql" => " and c.userName=# "
    ),
    array(
        "name" => "operationType",
        "sql" => " and c.operationType=# "
    ),
    array(
        "name" => "operationTime",
        "sql" => " and c.operationTime=# "
    ),
    array(
        "name" => "description",
        "sql" => " and c.description=# "
    )
)
?>