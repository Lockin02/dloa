<?php
/**
 * @author show
 * @Date 2013年10月17日 17:34:30
 * @version 1.0
 * @description:项目成员周日志 sql配置文件
 */
$sql_arr = array(
    "select_default" => "select c.id,c.mainId,c.memberName,c.memberId,c.inWorkRate,c.workCoefficient,
            c.projectProcess,c.processCoefficient,c.feeAll,c.monthScore,c.countDay,c.attendance,c.assess,c.hols,
            c.isNew
        from oa_esm_project_weeklog c where 1=1 "
);

$condition_arr = array(
    array(
        "name" => "id",
        "sql" => " and c.Id=# "
    ),
    array(
        "name" => "mainId",
        "sql" => " and c.mainId=# "
    ),
    array(
        "name" => "memberName",
        "sql" => " and c.memberName=# "
    ),
    array(
        "name" => "memberId",
        "sql" => " and c.memberId=# "
    ),
    array(
        "name" => "inWorkRate",
        "sql" => " and c.inWorkRate=# "
    ),
    array(
        "name" => "workCoefficient",
        "sql" => " and c.workCoefficient=# "
    ),
    array(
        "name" => "projectProcess",
        "sql" => " and c.projectProcess=# "
    ),
    array(
        "name" => "processCoefficient",
        "sql" => " and c.processCoefficient=# "
    ),
    array(
        "name" => "feeAll",
        "sql" => " and c.feeAll=# "
    )
);