<?php
/**
 * @author Administrator
 * @Date 2012-10-25 14:53:11
 * @version 1.0
 * @description:设备分类信息 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.budgetType ,c.parentName ,c.parentId ,c.orderNum ,c.lft ,c.rgt  from oa_equ_budget_type c where 1=1 and id <> '-1'",
         "select_treeinfo"=>"select c.id ,c.budgetType as name ,c.parentName ,c.parentId ,c.orderNum ,c.lft ,c.rgt,case (c.rgt-c.lft) when 1 then 0 else 1 end as isParent  from oa_equ_budget_type c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "budgetType",
   		"sql" => " and c.budgetType like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "parentName",
   		"sql" => " and c.parentName like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "parentId",
   		"sql" => " and c.parentId=# "
   	  ),
   array(
   		"name" => "orderNum",
   		"sql" => " and c.orderNum=# "
   	  ),
   array(
   		"name" => "lft",
   		"sql" => " and c.lft=# "
   	  ),
   array(
   		"name" => "dlft",
   		"sql" => " and c.lft ># "
   	  ),
   array(
   		"name" => "xrgt",
   		"sql" => " and c.rgt <# "
   	  ),
   array(
   		"name" => "rgt",
   		"sql" => " and c.rgt=# "
   	  )
)
?>