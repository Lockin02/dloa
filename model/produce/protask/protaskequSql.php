<?php
/**
 * @author Administrator
 * @Date 2011年6月2日 10:30:23
 * @version 1.0
 * @description:生产任务清单 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.mainId ,c.mainCode ,c.relDocType ,c.relDocId ,c.relDocCode ,c.relDocName ,c.productId ,c.productNo ,c.productName ,c.productModel ,c.number ,c.unitName ,c.aidUnit ,c.converRate ,c.referDate ,c.license ,c.remark  from oa_produce_protaskequ c where 1=1 "
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
   		"name" => "mainCode",
   		"sql" => " and c.mainCode=# "
   	  ),
   array(
   		"name" => "relDocType",
   		"sql" => " and c.relDocType=# "
   	  ),
   array(
   		"name" => "relDocId",
   		"sql" => " and c.relDocId=# "
   	  ),
   array(
   		"name" => "relDocCode",
   		"sql" => " and c.relDocCode=# "
   	  ),
   array(
   		"name" => "relDocName",
   		"sql" => " and c.relDocName=# "
   	  ),
   array(
   		"name" => "productId",
   		"sql" => " and c.productId=# "
   	  ),
   array(
   		"name" => "productNo",
   		"sql" => " and c.productNo=# "
   	  ),
   array(
   		"name" => "productName",
   		"sql" => " and c.productName=# "
   	  ),
   array(
   		"name" => "productModel",
   		"sql" => " and c.productModel=# "
   	  ),
   array(
   		"name" => "number",
   		"sql" => " and c.number=# "
   	  ),
   array(
   		"name" => "unitName",
   		"sql" => " and c.unitName=# "
   	  ),
   array(
   		"name" => "aidUnit",
   		"sql" => " and c.aidUnit=# "
   	  ),
   array(
   		"name" => "converRate",
   		"sql" => " and c.converRate=# "
   	  ),
   array(
   		"name" => "referDate",
   		"sql" => " and c.referDate=# "
   	  ),
   array(
   		"name" => "license",
   		"sql" => " and c.license=# "
   	  ),
   array(
   		"name" => "remark",
   		"sql" => " and c.remark=# "
   	  )
)
?>