<?php
/**
 * @author Show
 * @Date 2013年12月10日 星期二 17:12:50
 * @version 1.0
 * @description:物料协议价明细表 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.parentId ,c.productId ,c.productCode ,c.productName ,c.lowerNum ,c.ceilingNum ,c.taxPrice ,c.startValidDate,c.validDate ,c.suppId ,c.suppName ,c.suppCode ,c.isEffective ,c.giveCondition ,c.remark  from oa_purchase_material_equ c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "parentId",
   		"sql" => " and c.parentId=# "
   	  ),
   array(
   		"name" => "productId",
   		"sql" => " and c.productId=# "
   	  ),
   array(
   		"name" => "productCode",
   		"sql" => " and c.productCode like CONCAT('%',#,'%')  "
   	  ),
   array(
   		"name" => "productName",
   		"sql" => " and c.productName like CONCAT('%',#,'%')  "
   	  ),
   array(
   		"name" => "lowerNum",
   		"sql" => " and c.lowerNum=# "
   	  ),
   array(
   		"name" => "ceilingNum",
   		"sql" => " and c.ceilingNum=# "
   	  ),
   array(
   		"name" => "taxPrice",
   		"sql" => " and c.taxPrice=# "
   	  ),
   array(
   		"name" => "validDate",
   		"sql" => " and c.validDate=# "
   	  ),
   array(
   		"name" => "befValidDate",
   		"sql" => " and c.validDate >= # "
   	  ),
   array(
   		"name" => "isValidDate",
   		"sql" => " and c.validDate >= # and c.startValidDate <= #"
   	  ),
   array(
   		"name" => "suppId",
   		"sql" => " and c.suppId=# "
   	  ),
   array(
   		"name" => "suppName",
   		"sql" => " and c.suppName like CONCAT('%',#,'%')  "
   	  ),
   array(
   		"name" => "suppCode",
   		"sql" => " and c.suppCode=# "
   	  ),
   array(
   		"name" => "isEffective",
   		"sql" => " and c.isEffective=# "
   	  ),
   array(
   		"name" => "giveCondition",
   		"sql" => " and c.giveCondition=# "
   	  ),
   array(
   		"name" => "remark",
   		"sql" => " and c.remark=# "
   	  )
)
?>