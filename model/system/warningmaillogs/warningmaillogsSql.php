<?php
/**
 * @author Administrator
 * @Date 2014年3月17日 14:22:22
 * @version 1.0
 * @description:预警邮件通知情况 sql配置文件
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