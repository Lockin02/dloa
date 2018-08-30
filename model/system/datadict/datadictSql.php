<?php
$sql_arr = array (
	// OA 外包 zengq 2012-09-12 添加获取c.dataEnglishName字段
	"select_datadict" => "select c.isUse,c.id,c.dataName,c.dataName as text,c.dataCode,c.dataEnglishName,c.parentId,c.parentCode,c.remark," .
			"c.orderNum,c.parentName,c.leaf,c.expand1,c.expand2,c.expand3,
			c.expand4,c.expand5,c.module,c.moduleName
			from oa_system_datadict c where 1=1 and c.isUse = '0'",
	"select_grid" => "select c.isUse,c.id,c.dataName,c.dataName as text,c.dataCode,c.parentId,c.parentCode," .
			"c.orderNum,c.parentName,c.leaf,c.remark,c.expand1,c.expand2,c.expand3,
			c.expand4,c.expand5,c.module,c.moduleName from oa_system_datadict c where id <> -1 ",
    "select_treelist" => "select c.isUse,c.id,c.dataName as name,c.dataCode as code,if((c.leaf)=1,0,1) as isParent,c.parentId from oa_system_datadict c where c.id<>-1 and 1=1 ",
    "select_parentName" => "select c.isUse,c.dataName from oa_system_datadict c where 1=1",
	"select_foreasyui" => "select c.dataCode as id ,c.dataName as text,c.expand1,c.dataCode AS value " .
			" from oa_system_datadict c where 1",
	"select_KHLX" => "select
			c.dataName , c.dataCode , c.*
			from
			oa_system_datadict c
			LEFT JOIN oa_system_saleperson s on ( FIND_IN_SET(c.dataCode,s.customerType) )
			where 1=1
			and c.isUse = '0'"
);
$condition_arr = array (
    array(
        "name" => "isUse",
        "sql" => "and c.isUse = #"
    ),
    array (
        "name" => "id",
        "sql" => "and c.id = #"
    ),
	array (
		"name" => "dataName",
		"sql" => "and c.dataName like CONCAT('%',#,'%')"
	),
	array (
		"name" => "dataCode",
		"sql" => "and c.dataCode =#"
	),
	array (
		"name" => "dataCodeArr",
		"sql" => "and c.dataCode in(arr)"
	),
	array (
		"name" => "parentId",
		"sql" => "and c.parentId = #"
	),
	array (
		"name" => "parentName",
		"sql" => "and c.parentName  like CONCAT('%',#,'%')"
	),
	array (
		"name" => "parentCode",
		"sql" => "and c.parentCode = #"
	),
	array (
		"name" => "parentCodes",
		"sql" => "and c.parentCode in(arr)"
	),
	array (
		"name" => "ecode",
		"sql" => "and c.dataCode = #"
	),
	array (
		"name" => "expand1",
		"sql" => "and c.expand1 = #"
	),
	array (
		"name" => "expand2",
		"sql" => "and c.expand2 = #"
	),
	array (
		"name" => "expand3",
		"sql" => "and c.expand3 = #"
	),
	array (
		"name" => "expand4",
		"sql" => "and c.expand4 = #"
	),
	array (
		"name" => "expand5",
		"sql" => "and c.expand5 = #"
	),
	array (
		"name" => "expand1No",
		"sql" => "and c.expand1 <> #"
	),
	array (
		"name" => "expand2No",
		"sql" => "and c.expand2 <> #"
	),
	array (
		"name" => "expand3No",
		"sql" => "and c.expand3 <> #"
	),
	array (
		"name" => "expand4No",
		"sql" => "and c.expand4 <> #"
	),
	array (
		"name" => "expand5No",
		"sql" => "and c.expand5 <> #"
	),
	array (
		"name" => "myCondition",
		"sql" => "$"
	),
	array (
		"name" => "module",
		"sql" => " and c.module = # "
	),
	array (
		"name" => "moduleName",
		"sql" => " and c.moduleName = # "
	)
);
?>
