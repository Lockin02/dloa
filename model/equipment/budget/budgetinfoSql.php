<?php
/**
 * @author Administrator
 * @Date 2013年3月27日 13:56:10
 * @version 1.0
 * @description:设备预算表从表 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.budgetId ,c.budgetTypeId ,c.budgetTypeName ,c.equId ,c.equCode ,c.equName ,c.useEndDate ,c.unitName ,c.version ,c.useStatus ,c.remark ,c.pattern ,c.color ,c.brand ,c.quotedPrice ,c.num ,c.money  from oa_equ_budget_info c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "budgetId",
   		"sql" => " and c.budgetId=# "
   	  ),
   array(
   		"name" => "budgetTypeId",
   		"sql" => " and c.budgetTypeId=# "
   	  ),
   array(
   		"name" => "budgetTypeName",
   		"sql" => " and c.budgetTypeName=# "
   	  ),
   array(
   		"name" => "equId",
   		"sql" => " and c.equId=# "
   	  ),
   array(
   		"name" => "equCode",
   		"sql" => " and c.equCode=# "
   	  ),
   array(
   		"name" => "equName",
   		"sql" => " and c.equName=# "
   	  ),
   array(
   		"name" => "useEndDate",
   		"sql" => " and c.useEndDate=# "
   	  ),
   array(
   		"name" => "unitName",
   		"sql" => " and c.unitName=# "
   	  ),
   array(
   		"name" => "version",
   		"sql" => " and c.version=# "
   	  ),
   array(
   		"name" => "useStatus",
   		"sql" => " and c.useStatus=# "
   	  ),
   array(
   		"name" => "remark",
   		"sql" => " and c.remark=# "
   	  ),
   array(
   		"name" => "pattern",
   		"sql" => " and c.pattern=# "
   	  ),
   array(
   		"name" => "color",
   		"sql" => " and c.color=# "
   	  ),
   array(
   		"name" => "brand",
   		"sql" => " and c.brand=# "
   	  ),
   array(
   		"name" => "quotedPrice",
   		"sql" => " and c.quotedPrice=# "
   	  ),
   array(
   		"name" => "num",
   		"sql" => " and c.num=# "
   	  ),
   array(
   		"name" => "money",
   		"sql" => " and c.money=# "
   	  )
)
?>