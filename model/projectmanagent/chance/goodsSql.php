<?php
/**
 * @author Administrator
 * @Date 2012-08-11 15:28:33
 * @version 1.0
 * @description:商机简单产品表 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.chanceId ,c.goodsId ,c.goodsTypeId ,c.goodsTypeName ,c.goodsName ,c.number ,c.price ,c.money  from oa_sale_chance_goods c where 1=1 ",
         "select_timing"=>"select c.id ,c.oldId,c.chanceId ,c.goodsId ,c.goodsTypeId ,c.goodsTypeName ,c.goodsName ,c.number ,c.price ,c.money,c.timingDate  from oa_sale_chance_timingGoods c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "chanceId",
   		"sql" => " and c.chanceId=# "
   	  ),
   array(
   		"name" => "goodsId",
   		"sql" => " and c.goodsId=# "
   	  ),
   array(
   		"name" => "goodsTypeId",
   		"sql" => " and c.goodsTypeId=# "
   	  ),
   array(
   		"name" => "goodsTypeName",
   		"sql" => " and c.goodsTypeName=# "
   	  ),
   array(
   		"name" => "goodsName",
   		"sql" => " and c.goodsName=# "
   	  ),
   array(
   		"name" => "number",
   		"sql" => " and c.number=# "
   	  ),
   array(
   		"name" => "price",
   		"sql" => " and c.price=# "
   	  ),
   array(
   		"name" => "money",
   		"sql" => " and c.money=# "
   	  )
)
?>