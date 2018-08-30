<?php
/**
 * @author Show
 * @Date 2011年12月27日 星期二 20:38:02
 * @version 1.0
 * @description:应付其他发票明细 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.mainId ,c.productName ,c.productNo ,c.productId ,c.number ,c.unit ,c.price ,c.rate ,c.taxPrice ,c.assessment ,c.amount ,c.allCount ,c.objId ,c.objCode ,c.objType  from oa_finance_invother_detail c where 1=1 "
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
   		"name" => "productName",
   		"sql" => " and c.productName=# "
   	  ),
   array(
   		"name" => "productNo",
   		"sql" => " and c.productNo=# "
   	  ),
   array(
   		"name" => "productId",
   		"sql" => " and c.productId=# "
   	  ),
   array(
   		"name" => "number",
   		"sql" => " and c.number=# "
   	  ),
   array(
   		"name" => "unit",
   		"sql" => " and c.unit=# "
   	  ),
   array(
   		"name" => "price",
   		"sql" => " and c.price=# "
   	  ),
   array(
   		"name" => "rate",
   		"sql" => " and c.rate=# "
   	  ),
   array(
   		"name" => "taxPrice",
   		"sql" => " and c.taxPrice=# "
   	  ),
   array(
   		"name" => "assessment",
   		"sql" => " and c.assessment=# "
   	  ),
   array(
   		"name" => "amount",
   		"sql" => " and c.amount=# "
   	  ),
   array(
   		"name" => "allCount",
   		"sql" => " and c.allCount=# "
   	  ),
   array(
   		"name" => "objId",
   		"sql" => " and c.objId=# "
   	  ),
   array(
   		"name" => "objCode",
   		"sql" => " and c.objCode=# "
   	  ),
   array(
   		"name" => "objType",
   		"sql" => " and c.objType=# "
   	  )
)
?>