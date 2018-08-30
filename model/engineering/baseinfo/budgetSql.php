<?php
/**
 * @author Show
 * @Date 2011年11月25日 星期五 9:38:59
 * @version 1.0
 * @description:预算项目(oa_esm_baseinfo_budget) sql配置文件 status
 0 启用
 1.禁用
 */
$sql_arr = array (
    "select_default"=>"select c.id ,c.budgetCode ,c.budgetName ,c.parentId ,c.parentCode ,c.parentName ,c.currencyId ,c.currencyName ,c.currencyCode ,c.currencyUnit ,c.subjectName ,c.subjectCode ,c.orderNum ,c.isLeaf ,c.remark ,c.budgetType ,c.status  from oa_esm_baseinfo_budget c where c.id<>-1 ",
	"select_treelist" => "select c.id,c.budgetName as name,c.budgetCode as code,if((c.isLeaf)=1,0,1) as isParent,c.parentId from oa_esm_baseinfo_budget c where c.id<>-1 and 1=1 ",
	"select_treelistRtBoolean" => "select c.id,c.budgetName as name,c.budgetCode as code,if((c.isLeaf)=1,'false','true') as isParent,c.parentId from oa_esm_baseinfo_budget c where c.id<>-1 and 1=1 ",
	"select_all"=>"select c.id ,c.budgetCode ,c.budgetName ,c.parentId ,c.parentCode ,c.parentName ,c.currencyId ,c.currencyName ,c.currencyCode ,c.currencyUnit ,c.subjectName ,c.subjectCode ,c.orderNum ,c.isLeaf ,c.remark ,c.budgetType ,c.status  from oa_esm_baseinfo_budget c where c.id<>-1 ",
	"select_budgetdl"=>"select
			c.CostTypeID as id,c.CostTypeName as budgetName,c.showDays,c.isReplace,c.isEqu,c.invoiceType,c.invoiceTypeName,c.ParentCostType as parentName,
			c.ParentCostTypeID as parentId
		from
			cost_type c
		where c.CostTypeID not in ( select ParentCostTypeID from cost_type where isNew = '1' GROUP BY ParentCostTypeID)"
);

$condition_arr = array (
	array(
        "name" => "id",
   		"sql" => " and c.id=# "
    ),
   array(
   		"name" => "budgetCode",
   		"sql" => " and c.budgetCode like CONCAT('%',#,'%') "
    ),
    array(
   		"name" => "budgetCodeEq",
   		"sql" => " and c.budgetCode=# "
    ),
   array(
   		"name" => "budgetName",
   		"sql" => " and c.budgetName  like CONCAT('%',#,'%') "
    ),
   array(
   		"name" => "budgetNameDLSearch",
   		"sql" => " and c.CostTypeName like CONCAT('%',#,'%') "
    ),
   array(
   		"name" => "parentNameDLSearch",
   		"sql" => " and c.ParentCostType like CONCAT('%',#,'%') "
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
   		"sql" => " and c.parentName=# "
    ),
   array(
   		"name" => "currencyId",
   		"sql" => " and c.currencyId=# "
    ),
   array(
   		"name" => "currencyName",
   		"sql" => " and c.currencyName=# "
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
   		"name" => "subjectName",
   		"sql" => " and c.subjectName=# "
    ),
   array(
   		"name" => "subjectCode",
   		"sql" => " and c.subjectCode=# "
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
   		"name" => "remark",
   		"sql" => " and c.remark=# "
    ),
   array(
   		"name" => "status",
   		"sql" => " and c.status=# "
    ),
   array(
   		"name" => "budgetType",
   		"sql" => " and c.budgetType=# "
    ),
   array(
   		"name" => "isNewDL",
   		"sql" => " and c.isNew=# "
    )
)
?>