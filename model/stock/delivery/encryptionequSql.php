<?php
/**
 * @author Michael
 * @Date 2014年5月30日 10:13:44
 * @version 1.0
 * @description:交付加密锁任务从表 sql配置文件
 */
$sql_arr = array (
	"select_default"=>"select c.id ,c.parentId ,c.sourceDocId ,c.sourceDocCode ,c.productId ,c.productCode ,c.productName ,c.pattern ,c.unitName ,c.needNum ,c.finshNum ,c.inventoryNum ,c.produceNum ,c.planFinshDate ,c.actualFinshDate ,c.putNum ,c.state ,c.remark ,c.equId ,c.license from oa_delivery_encryptionequ c where 1=1 "
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
		"name" => "sourceDocId",
		"sql" => " and c.sourceDocId=# "
	),
	array(
		"name" => "sourceDocCode",
		"sql" => " and c.sourceDocCode=# "
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
		"name" => "needNum",
		"sql" => " and c.needNum=# "
	),
	array(
		"name" => "finshNum",
		"sql" => " and c.finshNum=# "
	),
	array(
		"name" => "inventoryNum",
		"sql" => " and c.inventoryNum=# "
	),
	array(
		"name" => "produceNum",
		"sql" => " and c.produceNum=# "
	),
	array(
		"name" => "planFinshDate",
		"sql" => " and c.planFinshDate=# "
	),
	array(
		"name" => "actualFinshDate",
		"sql" => " and c.actualFinshDate=# "
	),
	array(
		"name" => "putNum",
		"sql" => " and c.putNum=# "
	),
	array(
		"name" => "state",
		"sql" => " and c.state=# "
	),
	array(
		"name" => "remark",
		"sql" => " and c.remark=# "
	),
	array(
		"name" => "equId",
		"sql" => " and c.equId=# "
	),
	array(
		"name" => "license",
		"sql" => " and c.license=# "
	)
)
?>