<?php
/**
 * @author Administrator
 * @Date 2014��3��17�� 14:21:21
 * @version 1.0
 * @description:ͨ��Ԥ��������ϸ���� sql�����ļ�
 */
$sql_arr = array(
    "select_default" => "select c.id, c.mainId, c.logic, c.warningObject, c.warningObjectId, c.leftK, c.rightK,
            c.compareObjectId, c.compareObject, c.compare
        from oa_system_warning_setting c where 1=1 "
);

$condition_arr = array(
    array(
        "name" => "id",
        "sql" => " and c.id=# "
    ),
    array(
        "name" => "mainId",
        "sql" => " and c.mainId=# "
    ),
    array(
        "name" => "logic",
        "sql" => " and c.logic=# "
    ),
    array(
        "name" => "compare",
        "sql" => " and c.compare=# "
    ),
    array(
        "name" => "warningObjectId",
        "sql" => " and c.warningObjectId=# "
    ),
    array(
        "name" => "warningObject",
        "sql" => " and c.warningObject=# "
    ),
    array(
        "name" => "compareObject",
        "sql" => " and c.compareObject=# "
    ),
    array(
        "name" => "compareObjectId",
        "sql" => " and c.compareObjectId=# "
    ),
    array(
        "name" => "compare",
        "sql" => " and c.compare=# "
    ),
    array(
        "name" => "leftK",
        "sql" => " and c.leftK=# "
    ),
    array(
        "name" => "rightK",
        "sql" => " and c.rightK=# "
    )
);