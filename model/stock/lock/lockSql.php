<?php

/**
 * @author Show
 * @Date 2011年1月17日 星期一 11:05:31
 * @version 1.0
 * @description:仓库锁定记录表 sql配置文件
 */
$sql_arr = array (
	"select_default" => "select c.id ,c.stockId,c.stockName ,c.inventoryId ,c.lockNum ,c.lockType ,c.objId,c.objEquId,c.outStockDocId,c.createName ,c.createId ,c.createTime,c.updateName ,c.updateId ,c.updateTime,c.productId,c.productName,c.productNo from oa_stock_lock c where 1=1 ",
	"select_locknum" => "select   case   objType
				when  'oa_sale_order' then (select orderTempCode from oa_sale_order where id=objId)
				when  'oa_sale_service' then (select orderTempCode from oa_sale_service where id=objId)
				when  'oa_sale_lease' then (select orderTempCode from oa_sale_lease where id=objId)  else objCode
			end as objCode,
	 		case objType
					when 'oa_sale_order' then '销售合同'
					when 'oa_sale_service' then '服务合同'
					when 'oa_sale_lease'  then '租赁合同'
			else objType end as objType,
			objId,
			stockName,
			productId,
			productName,
			sum(lockNum) as lockNum
				from oa_stock_lock  group by objType,objId, stockId,productId",
			"sub_locknum"=>"select sum(c.lockNum) as lockNum from oa_stock_lock c where 1=1 "
);

$condition_arr = array (
	array (
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array (
		"name" => "objId",
		"sql" => " and c.objId=# "
	),
	array (
		"name" => "stockId",
		"sql" => " and c.stockId=# "
	),
	array (
		"name" => "inventoryId",
		"sql" => " and c.inventoryId=# "
	),
	array (
		"name" => "lockNum",
		"sql" => " and c.lockNum=# "
	),
	array (
		"name" => "lockType",
		"sql" => " and c.lockType=# "
	),
	array (
		"name" => "objType",
		"sql" => " and c.objType=# "
	),
	array (
		"name" => "objCode",
		"sql" => " and c.objCode=# "
	),
	array (
		"name" => "objEquId",
		"sql" => " and c.objEquId=# "
	),
	array (
		"name" => "productId",
		"sql" => " and c.productId=# "
	),
	array (
		"name" => "productName",
		"sql" => " and c.productName like CONCAT('%',#,'%') "
	),
	array (
		"name" => "productNo",
		"sql" => " and c.productNo like CONCAT('%',#,'%') "
	),
	array (
		"name" => "outStockDocId",
		"sql" => " and c.outStockDocId=# "
	),
	array (
		"name" => "createName",
		"sql" => " and c.createName=# "
	),
	array (
		"name" => "createId",
		"sql" => " and c.createId=# "
	),
	array (
		"name" => "createTime",
		"sql" => " and c.createTime=# "
	),
	array (
		"name" => "updateName",
		"sql" => " and c.updateName=# "
	),
	array (
		"name" => "updateId",
		"sql" => " and c.updateId=# "
	),
	array (
		"name" => "updateTime",
		"sql" => " and c.updateTime=# "
	),
	array (
		"name" => "inStockDocId",
		"sql" => " and c.inStockDocId=# "
	)
)
?>