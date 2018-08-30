<?php
/**
 * @author zengqin
 * @Date 2015-2-10
 * @version 1.0
 * @description:费用预算 SQL脚本配置文件
 */
$sql_arr = array (
	"select_default"=>"select c.id,c.year,c.expenseTypeId,c.expenseTypeCode,c.expenseType,c.expenseClass,c.totalBudget,c.final,c.createId,c.createName,c.createTime,c.updateId,c.updateName,c.updateTime from oa_finance_budget c where 1=1 "
);

$condition_arr = array (
	array(
        "name" => "id",
        "sql" => " and c.Id=# "
    )
)
 ?>