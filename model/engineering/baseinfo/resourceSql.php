 	<?php
/**
 * @author Show
 * @Date 2011年11月25日 星期五 13:59:48
 * @version 1.0
 * @description:资源目录(oa_esm_baseinfo_resource) sql配置文件 status
                                                       0 启用
                                                       1.禁用
 */
$sql_arr = array (
    "select_default" => "select c.id ,c.resourceCode ,c.resourceName ,c.parentId ,c.parentCode ,c.parentName ,c.orderNum ,c.isLeaf ,c.price ,c.currencyName ,c.currencyId ,c.currencyCode ,c.currencyUnit ,c.units ,c.budgetCode ,c.budgetName ,c.status ,c.remark,c.resourceNature  from oa_esm_baseinfo_resource c where c.id <> -1",
    "select_treelist" => "select c.id,c.resourceName as name,c.resourceCode as code,if((c.isLeaf)=1,0,1) as isParent,c.parentId from oa_esm_baseinfo_resource c where c.id<>-1 and 1=1 ",
    "select_treelistRtBoolean" => "select c.id,c.resourceName as name,c.resourceCode as code,if((c.isLeaf)=1,'false','true') as isParent,c.parentId from oa_esm_baseinfo_resource c where c.id<>-1 and 1=1 ",
    "select_all" =>"select c.id ,c.resourceCode ,c.resourceName ,c.parentId ,c.parentCode ,c.parentName ,c.orderNum ,c.isLeaf ,c.price ,c.currencyName ,c.currencyId ,c.currencyCode ,c.currencyUnit ,c.units ,c.budgetCode ,c.budgetName ,c.status ,c.remark  from oa_esm_baseinfo_resource c where 1=1 "
);

$condition_arr = array (
	array(
       "name" => "id",
   		"sql" => " and c.Id=# "
    ),
    array(
       "name" => "noid",
   		"sql" => " and c.Id <> # "
    ),
	array(
   		"name" => "resourceCode",
   		"sql" => " and c.resourceCode like CONCAT('%',#,'%') "
    ),
    array(
   		"name" => "resourceCodeEq",
   		"sql" => " and c.resourceCode=# "
    ),
    array(
   		"name" => "resourceName",
   		"sql" => " and c.resourceName  like CONCAT('%',#,'%') "
    ),
    array(
   		"name" => "codeOrName",
   		"sql" => " and (c.resourceCode like CONCAT('%',#,'%') or c.resourceName like CONCAT('%',#,'%'))"
    ),
    array(
   		"name" => "parentId",
   		"sql" => " and c.parentId=# "
    ),
    array(
   		"name" => "parentCode",
   		"sql" => " and c.parentCode  like CONCAT('%',#,'%') "
    ),
    array(
   		"name" => "parentName",
   		"sql" => " and c.parentName like CONCAT('%',#,'%') "
    ),
    array(
   		"name" => "orderNum",
   		"sql" => " and c.orderNum=# "
    ),
    array(
   		"name" => "isLeaf",
   		"sql" => " and c.isLeaf=# "
    ),
    array(
   		"name" => "price",
   		"sql" => " and c.price=# "
    ),
    array(
   		"name" => "currencyName",
   		"sql" => " and c.currencyName=# "
    ),
    array(
   		"name" => "currencyId",
   		"sql" => " and c.currencyId=# "
    ),
    array(
   		"name" => "currencyCode",
   		"sql" => " and c.currencyCode=# "
    ),
    array(
   		"name" => "currencyUnit",
   		"sql" => " and c.currencyUnit=# "
    ),
    array(
   		"name" => "units",
   		"sql" => " and c.units=# "
    ),
    array(
   		"name" => "budgetCode",
   		"sql" => " and c.budgetCode=# "
    ),
    array(
   		"name" => "budgetName",
   		"sql" => " and c.budgetName=# "
    ),
    array(
   		"name" => "status",
   		"sql" => " and c.status=# "
    ),
    array(
   		"name" => "remark",
   		"sql" => " and c.remark=# "
    )
)
?>