<?php
/**
 * @author show
 * @Date 2014年10月15日 14:15:01
 * @version 1.0
 * @description:费用分摊核销记录 sql配置文件
 */
$sql_arr = array(
    "select_default" => "select c.id ,c.hookPeriod ,c.hookYear ,c.hookMonth ,c.createId ,c.createName ,c.createTime,
            d.hookId, d.hookCode, d.hookType, d.hookMoney
        from oa_finance_cost_hook c LEFT JOIN oa_finance_cost_hook_detail d ON c.id = d.mainId where 1=1 "
);

$condition_arr = array(
    array(
        "name" => "id",
        "sql" => " and c.Id=# "
    ),
    array(
        "name" => "idArr",
        "sql" => " and c.id in(arr)"
    ),
    array(
        "name" => "hookPeriod",
        "sql" => " and c.hookPeriod=# "
    ),
    array(
        "name" => "hookYear",
        "sql" => " and c.hookYear=# "
    ),
    array(
        "name" => "hookMonth",
        "sql" => " and c.hookMonth=# "
    ),
    array(
        "name" => "createId",
        "sql" => " and c.createId=# "
    ),
    array(
        "name" => "createName",
        "sql" => " and c.createName=# "
    ),
    array(
        "name" => "createTime",
        "sql" => " and c.createTime=# "
    ),
    array(
        "name" => "dHookCode",
        "sql" => " and d.hookCode=# "
    )
);