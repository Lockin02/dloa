<?php
/**
 * @author Administrator
 * @Date 2014��3��17�� 14:22:22
 * @version 1.0
 * @description:Ԥ���ʼ�֪ͨ��� sql�����ļ�
 */
$sql_arr = array(
    "select_default" => "select c.id ,c.objId ,c.objName ,c.logId ,c.mailUserIds ,c.mailUserNames ,c.mailFeedback ,c.executeTime,c.ccmailUserIds,c.ccmailUserNames,c.result  from oa_system_warning_mail_logs c where 1=1 "
);

$condition_arr = array(
    array(
        "name" => "id",
        "sql" => " and c.Id=# "
    ),
    array(
        "name" => "objId",
        "sql" => " and c.objId=# "
    ),
    array(
        "name" => "objName",
        "sql" => " and c.objName=# "
    ),
    array(
        "name" => "logId",
        "sql" => " and c.logId=# "
    ),
    array(
        "name" => "mailUserIds",
        "sql" => " and c.mailUserIds=# "
    ),
    array(
        "name" => "mailUserNames",
        "sql" => " and c.mailUserNames=# "
    ),
    array(
        "name" => "mailFeedback",
        "sql" => " and c.mailFeedback=# "
    ),
    array(
        "name" => "executeTime",
        "sql" => " and c.executeTime=# "
    )
);