<?php
/**
 * @author show
 * @Date 2014年09月01日
 * @version 1.0
 * @description:物料转资产申请明细sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.mainId ,c.productId ,c.productCode ,c.productName ,c.productPrice ,c.brand ,c.spec ,c.number ,c.executedNum ,(c.number-c.executedNum) as shouldOutNum ,
				c.receiveNum ,(c.executedNum-c.receiveNum) as shouldReceiveNum ,c.cardNum ,c.remark ,c.name ,c.description ,c.requireItemId
			from oa_asset_requireinitem c where 1=1 "
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
		"name" => "productPrice",
		"sql" => " and c.productPrice=# "
	),
   array(
		"name" => "brand",
		"sql" => " and c.brand=# "
	),
   array(
   		"name" => "spec",
   		"sql" => " and c.spec=# "
   	),
   array(
   		"name" => "number",
   		"sql" => " and c.number=# "
   	),
   array(
   		"name" => "executedNum",
   		"sql" => " and c.executedNum=# "
   	),
   array(
		"name" => "receiveNum",
		"sql" => " and c.receiveNum=# "
	),
   array(
   		"name" => "cardNum",
   		"sql" => " and c.cardNum=# "
   	),
   array(
   		"name" => "remark",
   		"sql" => " and c.remark=# "
   	),
   array(
		"name" => "requireId",
		"sql" => " and c.mainId in(select r.id from oa_asset_requirein r where r.requireId=#) "
	),
//自定义条件
   array(
		"name" => "numCondition",
		"sql" => "$"
	)
)
?>