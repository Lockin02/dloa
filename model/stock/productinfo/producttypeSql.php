<?php

/**
 * @author huangzf
 * @Date 2011年5月5日 9:36:26
 * @version 1.0
 * @description:物料分类信息 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.proType ,c.parentName ,c.parentId ,c.orderNum ,c.lft ,c.rgt ,c.accountingCode,c.properties,c.submitDay,c.esmCanUse  from oa_stock_product_type c where c.id<>-1 and 1=1 ",
	"select_treeinfo" => "select c.id ,c.proType as name ,c.parentName ,c.parentId ,c.orderNum ,c.lft ,c.rgt ,c.accountingCode,c.properties,case (c.rgt-c.lft) when 1 then 0 else 1 end as isParent,c.submitDay  from oa_stock_product_type c where 1=1 "
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.id=# "
	),
	array (
		"name" => "ids",
		"sql" => " and c.id in ($)"
	),
	array (
		"name" => "nid",
		"sql" => "and c.id!=#"
	),
	array (
		"name" => "nproType",
		"sql" => "and c.proType =#"
	),
	array (
		"name" => "proType",
		"sql" => "and c.proType like CONCAT('%',#,'%')"
	),
	array (
		"name" => "proTypeArr",
		"sql" => " and c.proType in(arr)"
	),
	array (
		"name" => "parentName",
		"sql" => "and c.parentName like CONCAT('%',#,'%')"
	),
	array (
		"name" => "parentId",
		"sql" => " and c.parentId=# "
	),
	array (
        "name" => "parentIdArr",
        "sql" => " and c.parentId in(arr)"
    ),
	array (
		"name" => "orderNum",
		"sql" => " and c.orderNum=# "
	),
	array (
		"name" => "lft",
		"sql" => " and c.lft=# "
	),
	array (
		"name" => "dlft",
		"sql" => " and c.lft>=# "
	),
	array (
		"name" => "xlft",
		"sql" => " and c.lft<# "
	),
	array (
		"name" => "rgt",
		"sql" => " and c.rgt=# "
	),
	array (
		"name" => "xrgt",
		"sql" => " and c.rgt<=# "
	),
	array (
		"name" => "drgt",
		"sql" => " and c.rgt># "
	),
	array (
		"name" => "accountingCode",
		"sql" => " and c.accountingCode=# "
	),
	array (
		"name" => "esmCanUse",
		"sql" => " and c.esmCanUse=# "
	),
	array (//自定义条件
        "name" => "customCondition",
        "sql" => "$"
    )
);