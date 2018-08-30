<?php
/**
 * @author Show
 * @Date 2012年6月14日 星期四 20:39:00
 * @version 1.0
 * @description:人力预算项目(oa_esm_baseinfo_person) sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.personLevel ,c.parentId  ,c.parentName ,c.orderNum ,c.isLeaf ,c.price ,c.number ,
	        c.money ,c.unit ,c.coefficient ,c.status ,c.remark,c.nonFamilyShort,c.nonFamilyLong,c.adminProject,c.familyProject,
	        c.customPrice
        from oa_esm_baseinfo_person c where c.id<>-1 ",
	"select_treelist" => "select c.id,c.personLevel as name,c.personLevel as code,if((c.isLeaf)=1,0,1) as isParent,
	        c.parentId
	    from oa_esm_baseinfo_person c where c.id<>-1 and 1=1 ",
	"select_treelistRtBoolean" => "select c.id,c.personLevel as name,c.personLevel as code,if((c.isLeaf)=1,'false','true') as isParent,c.parentId from oa_esm_baseinfo_person c where c.id<>-1 and 1=1 ",
	"select_all"=>"select c.id ,c.personLevel ,c.parentId  ,c.parentName ,c.orderNum ,c.isLeaf ,c.price ,c.number ,c.money ,c.unit ,c.coefficient ,c.status ,c.remark,c.nonFamilyShort,c.nonFamilyLong,c.adminProject,c.familyProject   from oa_esm_baseinfo_person c where c.id<>-1 "
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "personLevel",
		"sql" => " and c.personLevel=# "
	),
	array (
		"name" => "parentId",
		"sql" => " and c.parentId=# "
	),
	array (
		"name" => "parentName",
		"sql" => " and c.parentName=# "
	),
	array (
		"name" => "orderNum",
		"sql" => " and c.orderNum=# "
	),
	array (
		"name" => "isLeaf",
		"sql" => " and c.isLeaf=# "
	),
	array (
		"name" => "price",
		"sql" => " and c.price=# "
	),
	array (
		"name" => "number",
		"sql" => " and c.number=# "
	),
	array (
		"name" => "money",
		"sql" => " and c.money=# "
	),
	array (
		"name" => "unit",
		"sql" => " and c.unit=# "
	),
	array (
		"name" => "coefficient",
		"sql" => " and c.coefficient=# "
	),
	array (
		"name" => "status",
		"sql" => " and c.status=# "
	),
	array (
		"name" => "remark",
		"sql" => " and c.remark=# "
	),
	array (
		"name" => "nonFamilyShort",
		"sql" => " and c.nonFamilyShort=# "
	),
	array (
		"name" => "nonFamilyLong",
		"sql" => " and c.nonFamilyLong=# "
	),
	array (
		"name" => "adminProject",
		"sql" => " and c.adminProject=# "
	),
	array (
		"name" => "familyProject",
		"sql" => " and c.familyProject=# "
	)
);