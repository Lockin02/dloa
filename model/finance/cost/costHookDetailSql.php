<?php
/**
 * @author show
 * @Date 2014��10��15�� 14:15:01
 * @version 1.0
 * @description:���÷�̯������ϸ sql�����ļ�
 */
$sql_arr = array(
    "select_default" => "select c.id ,c.mainId ,c.hookId ,c.hookCode ,c.hookType ,c.hookMoney
        from oa_finance_cost_hook_detail c where 1=1 "
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
        "name" => "hookId",
        "sql" => " and c.hookId=# "
    ),
    array(
        "name" => "hookCode",
        "sql" => " and c.hookCode=# "
    ),
    array(
        "name" => "hookType",
        "sql" => " and c.hookType=# "
    ),
    array(
        "name" => "hookMoney",
        "sql" => " and c.hookMoney=# "
    )
);