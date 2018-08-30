<?php
/**
 * @author Administrator
 * @Date 2012年3月8日 14:27:17
 * @version 1.0
 * @description:配置项内容 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.mainId ,c.itemContent ,c.isNeed ,c.licenseTypeCode,c.licenseTypeName,c.licenseTemplateId," .
         		"c.isDefault ,c.defaultNum ,c.productId ,c.productCode ,c.productName ,c.pattern ,c.proNum ,c.status ," .
         		"c.remark  from oa_goods_properties_item c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
	array(
   		"name" => "ids",
   		"sql" => " and c.Id in(arr) "
        ),
   array(
   		"name" => "mainId",
   		"sql" => " and c.mainId=# "
   	  ),
   array(
   		"name" => "itemContent",
   		"sql" => " and c.itemContent=# "
   	  ),
   array(
   		"name" => "isNeed",
   		"sql" => " and c.isNeed=# "
   	  ),
   array(
   		"name" => "isDefault",
   		"sql" => " and c.isDefault=# "
   	  ),
   array(
   		"name" => "defaultNum",
   		"sql" => " and c.defaultNum=# "
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
   		"name" => "proNum",
   		"sql" => " and c.proNum=# "
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