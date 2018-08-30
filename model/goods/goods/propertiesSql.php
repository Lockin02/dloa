<?php
/**
 * @author Administrator
 * @Date 2012年3月1日 20:09:22
 * @version 1.0
 * @description:产品属性配置(树形) sql配置文件 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.mainId ,c.parentId ,c.parentName ,c.propertiesName ,c.orderNum ,c.lft ,c.rgt ,c.propertiesType ,c.isLeast ,c.isInput ,c.existNum,c.remark  from oa_goods_properties c where 1=1 ",
		"select_treeinfo"=>"select c.id ,c.mainId ,c.parentId ,c.parentName ,c.propertiesName as name ,c.orderNum ,c.lft ,c.rgt,case (c.rgt-c.lft) when 1 then 0 else 1 end as isParent  from oa_goods_properties c where 1=1 ",
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "mainId",
   		"sql" => " and c.mainId=# "
   	  ),
   array(
   		"name" => "parentId",
   		"sql" => " and c.parentId=# "
   	  ),
   array(
   		"name" => "parentName",
   		"sql" => " and c.parentName=# "
   	  ),
   array(
   		"name" => "propertiesName",
   		"sql" => " and c.propertiesName=# "
   	  ),
   array(
   		"name" => "orderNum",
   		"sql" => " and c.orderNum=# "
   	  ),
   array(
   		"name" => "xyOrderNum",
   		"sql" => " and c.orderNum <# "
   	  ),   	  
   array(
   		"name" => "dlft",
   		"sql" => " and c.lft>=# "
   	  ),
   array(
   		"name" => "xlft",
   		"sql" => " and c.lft<# "
   		),
   array(
   		"name" => "lft",
   		"sql" => " and c.lft=# "
   	  ),
   array(
   		"name" => "rgt",
   		"sql" => " and c.rgt=# "
   	  ),
   array(
   		"name" => "propertiesType",
   		"sql" => " and c.propertiesType=# "
   	  ),
   array(
   		"name" => "isLeast",
   		"sql" => " and c.isLeast=# "
   	  ),
   array(
   		"name" => "isInput",
   		"sql" => " and c.isInput=# "
   	  ),
   array(
   		"name" => "existNum",
   		"sql" => " and c.existNum=# "
   	  ),   	  
   array(
   		"name" => "remark",
   		"sql" => " and c.remark=# "
   	  )
)
?>