<?php
/**
 * @author show
 * @Date 2014��12��25�� 16:07:45
 * @version 1.0
 * @description:��Ŀ�ֳ����� sql�����ļ�
 */
$sql_arr = array (
    "select_default"=>"select c.id, c.projectId, c.thisYear, c.thisMonth, c.thisDate,
            c.feeField, c.feeFieldImport, c.createId, c.createName, c.createTime
        from oa_esm_records_field c where 1=1 "
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
        "name" => "thisYear",
        "sql" => " and c.thisYear=# "
    ),
    array(
        "name" => "thisMonth",
        "sql" => " and c.thisMonth=# "
    ),
    array(
        "name" => "thisDate",
        "sql" => " and c.thisDate=# "
    )
);