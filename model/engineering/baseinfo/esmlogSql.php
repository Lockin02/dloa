<?php
/**
 * @author show
 * @Date 2014��5��13�� 15:39:29
 * @version 1.0
 * @description:��Ŀ������¼ sql�����ļ�
 */
$sql_arr = array(
    "select_default" => "select c.id ,c.projectId ,c.userId ,c.userName ,c.operationType ,c.operationTime ,c.description  from oa_esm_log c where 1=1 "
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
        "name" => "operationTypeSearch",
        "sql" => " and c.operationType LIKE CONCAT('%',#,'%') "
    ),
    array(
        "name" => "operationTimeSearch",
        "sql" => " and c.operationTime LIKE CONCAT('%',#,'%') "
    ),
    array(
        "name" => "operationTime",
        "sql" => " and c.operationTime=# "
    ),
    array(
        "name" => "description",
        "sql" => " and c.description=# "
    ),
    array(
        "name" => "period",
        "sql" => " AND DATEDIFF(NOW(), operationTime) <= #"
    )
);