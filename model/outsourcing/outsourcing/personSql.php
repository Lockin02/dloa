<?php
/**
 * @author Administrator
 * @Date 2013年9月14日 15:51:51
 * @version 1.0
 * @description:人员租借详细 sql配置文件
 */
$sql_arr = array(
    "select_default" => "select c.id ,c.applyId ,c.riskCode ,c.peopleCount ,c.startTime ,c.endTime ,c.skill ,
            c.inBudget ,c.outBudget,c.totalDay,c.inBudgetPrice,c.outBudgetPrice,c.levelRemark,c.content,c.priceContent
        from oa_outsourcing_person c where 1=1 "
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
        "name" => "riskCode",
        "sql" => " and c.riskCode=# "
    ),
    array(
        "name" => "peopleCount",
        "sql" => " and c.peopleCount=# "
    ),
    array(
        "name" => "startTime",
        "sql" => " and c.startTime=# "
    ),
    array(
        "name" => "endTime",
        "sql" => " and c.endTime=# "
    ),
    array(
        "name" => "skill",
        "sql" => " and c.skill=# "
    ),
    array(
        "name" => "inBudget",
        "sql" => " and c.inBudget=# "
    ),
    array(
        "name" => "outBudget",
        "sql" => " and c.outBudget=# "
    )
)
?>