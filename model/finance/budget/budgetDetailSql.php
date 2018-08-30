<?php
/**
 * @author zengqin
 * @Date 2015-2-12
 * @version 1.0
 * @description: 费用预算明细SQL配置文件
 */
$sql_arr = array (
	"select_default"=>"SELECT c.id,c.mainId,c.parentId,c.expenseTypeId,c.expenseTypeCode,c.expenseType,c.`year`,c.expenseClass,c.areaId,c.area,c.company,c.province,c.totalBudget,c.firstBudget,c.secondBudget,c.thirdBudget,c.fourthBudget,c.firstFinal,c.secondFinal,c.thirdFinal,c.fourthFinal,c.final,c.isProvinceVisible FROM oa_finance_budget_detail c where 1=1 "
);

$condition_arr = array (
	array(
        "name" => "mainIds",
        "sql" => " and c.mainId in (#) "
    ),array(
        "name" => "mainId",
        "sql" => " and c.mainId = # "
    ),array(
        "name" => "totalBudget", // 有预算或者决算的区域才在查看页面显示
        "sql" => " and (c.totalBudget >0 or c.final>0)"
    ),array(
        "name" => "areaIds",
        "sql" => " and c.areaId in (#) "
    )
)
 ?>