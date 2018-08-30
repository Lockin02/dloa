<?php
/**
 * @author Administrator
 * @Date 2013年5月30日 星期四 15:57:29
 * @version 1.0
 * @description:物料BOM清单 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.proTypeId ,c.proType,c.productId ,c.productCode ,c.productName ,c.pattern ,c.unitName ,c.parentProductID ,c.parentProductName ,c.parentProductCode ,c.parentName ,c.parentId ,c.lft ,c.rgt ,c.isLeaf ,c.materialNum ,c.parentMaterialNum ,c.remark ,c.lowLevelCode,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime  from oa_stock_product_material c where 1=1 ",
	"treelist" => "select c.id ,c.proTypeId ,c.proType ,c.productId,c.productCode ,c.productName ,c.pattern ,c.unitName ,c.parentProductID ,case (c.rgt-c.lft) when 1 then 'false' else 'true' end as isParent,c.parentProductName ,c.parentProductCode ,c.parentName ,c.parentId,c.parentId as _parentId,c.lowLevelCode,c.lft ,c.rgt ,c.isLeaf ,c.materialNum ,c.parentMaterialNum ,c.remark ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime  from oa_stock_product_material c where c.id<>-1 and 1=1 ",
	"leaflist" => "select c.id ,c.proTypeId ,c.proType ,c.productId,c.productCode ,c.productName ,c.pattern ,c.unitName ,c.parentProductID ,case (c.rgt-c.lft) when 1 then 'false' else 'true' end as isParent,c.parentProductName ,c.parentProductCode ,c.parentName ,c.parentId,c.parentId as _parentId,c.lowLevelCode,c.lft ,c.rgt ,c.isLeaf ,c.materialNum ,c.parentMaterialNum ,c.remark ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime  from oa_stock_product_material c where c.id<>-1 and rgt-lft=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "proTypeId",
   		"sql" => " and c.proTypeId=# "
   	  ),
   array(
   		"name" => "proType",
   		"sql" => " and c.proType=# "
   	  ),
   array(
   		"name" => "productId",
   		"sql" => " and c.productId=# "
   	  ),
   array(
   		"name" => "productCode",
   		"sql" => " and c.productCode=# "
   	  ),
   array(
   		"name" => "productName",
   		"sql" => " and c.productName=# "
   	  ),
   array(
   		"name" => "pattern",
   		"sql" => " and c.pattern=# "
   	  ),
   array(
   		"name" => "unitName",
   		"sql" => " and c.unitName=# "
   	  ),
   array(
   		"name" => "parentProductID",
   		"sql" => " and c.parentProductID=# "
   	  ),
   array(
   		"name" => "parentProductName",
   		"sql" => " and c.parentProductName=# "
   	  ),
   array(
   		"name" => "parentProductCode",
   		"sql" => " and c.parentProductCode=# "
   	  ),
   array(
   		"name" => "parentName",
   		"sql" => " and c.parentName=# "
   	  ),
   array(
   		"name" => "parentId",
   		"sql" => " and c.parentId=# "
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
   		"name" => "isLeaf",
   		"sql" => " and c.isLeaf=# "
   	  ),
   array(
   		"name" => "materialNum",
   		"sql" => " and c.materialNum=# "
   	  ),
   array(
   		"name" => "parentMaterialNum",
   		"sql" => " and c.parentMaterialNum=# "
   	  ),
   array(
   		"name" => "remark",
   		"sql" => " and c.remark=# "
   	  ),
   array(
   		"name" => "createName",
   		"sql" => " and c.createName=# "
   	  ),
   array(
   		"name" => "createId",
   		"sql" => " and c.createId=# "
   	  ),
   array(
   		"name" => "createTime",
   		"sql" => " and c.createTime=# "
   	  ),
   array(
   		"name" => "updateName",
   		"sql" => " and c.updateName=# "
   	  ),
   array(
   		"name" => "updateId",
   		"sql" => " and c.updateId=# "
   	  ),
   array(
   		"name" => "updateTime",
   		"sql" => " and c.updateTime=# "
   	  )
)
?>