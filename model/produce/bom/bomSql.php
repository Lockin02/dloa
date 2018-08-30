<?php
/**
 * @author Administrator
 * @Date 2011年12月30日 11:45:00
 * @version 1.0
 * @description:BOM表 sql配置文件 注:同一物料不能同时出现在同一BOM的父项物料与子项物料中
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.docCode ,c.productId ,c.productCode ,c.productName ,c.pattern ,c.unitName ,c.properties ,c.proNum ,c.useStatus ,c.version ,c.auditerCode ,c.auditerName ,c.docStatus ,c.remark ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime  from oa_produce_bom c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "docCode",
   		"sql" => " and c.docCode  like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "productId",
   		"sql" => " and c.productId=# "
   	  ),
   array(
   		"name" => "productCode",
   		"sql" => " and c.productCode  like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "yproductCode",
   		"sql" => " and c.productCode  =# "
   	  ), 
   array(
   		"name" => "productName",
   		"sql" => " and c.productName  like CONCAT('%',#,'%') "
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
   		"name" => "properties",
   		"sql" => " and c.properties=# "
   	  ),
   array(
   		"name" => "proNum",
   		"sql" => " and c.proNum=# "
   	  ),
   array(
   		"name" => "useStatus",
   		"sql" => " and c.useStatus=# "
   	  ),
   array(
   		"name" => "version",
   		"sql" => " and c.version=# "
   	  ),
   array(
   		"name" => "auditerCode",
   		"sql" => " and c.auditerCode=# "
   	  ),
   array(
   		"name" => "auditerName",
   		"sql" => " and c.auditerName=# "
   	  ),
   array(
   		"name" => "docStatus",
   		"sql" => " and c.docStatus=# "
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
   	  ),
  array(
   		"name" => "itemProductCode",
   		"sql" => " and c.id in( select mainId from oa_produce_bom_item  where productCode like CONCAT('%',#,'%'))   "
   	  ),
  array(
   		"name" => "itemProductName",
   		"sql" => " and c.id in( select mainId from oa_produce_bom_item  where productName like CONCAT('%',#,'%'))   "
   	  )
)
?>