<?php
/**
 * @author Administrator
 * @Date 2012年3月11日 15:15:40
 * @version 1.0
 * @description:产品分类信息 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.goodsType ,c.parentName ,c.parentId ,c.orderNum ,c.lft ,c.rgt  from oa_goods_type c where 1=1 ",
		"select_treeinfo"=>"select c.id ,c.goodsType as name ,c.parentName ,c.parentId ,c.orderNum ,c.lft ,c.rgt,case (c.rgt-c.lft) when 1 then 0 else 1 end as isParent  from oa_goods_type c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "goodsType",
   		"sql" => " and c.goodsType=# "
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
   		"name" => "orderNum",
   		"sql" => " and c.orderNum=# "
   	  ),
   array(
   		"name" => "lft",
   		"sql" => " and c.lft=# "
   	  ),
   array(
   		"name" => "dlft",
   		"sql" => " and c.lft ># "
   	  ),
   array(
   		"name" => "xrgt",
   		"sql" => " and c.rgt <# "
   	  ),
   array(
   		"name" => "rgt",
   		"sql" => " and c.rgt=# "
   	  ),
   array (//自定义条件
		"name" => "mySearch",
		"sql" => "$"
	),
	array (
			"name" => "goodsTypeSch",
			"sql" => " and c.goodsType like concat('%',#,'%') "
	),
	array (
			"name" => "parentNameSch",
			"sql" => " and c.parentName like concat('%',#,'%') "
	)
)
?>