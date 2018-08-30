<?php
/**
 * @author Administrator
 * @Date 2011年9月5日 11:22:39
 * @version 1.0
 * @description:仓存管理基础信息设置 sql配置文件 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.salesStockId ,c.salesStockName ,c.salesStockCode ,c.packingStockId ,c.packingStockName ,c.packingStockCode ,c.outStockId ,c.outStockName ,c.outStockCode,c.borrowStockId,c.borrowStockCode,c.borrowStockName  from oa_stock_syteminfo c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "salesStockId",
   		"sql" => " and c.salesStockId=# "
   	  ),
   array(
   		"name" => "salesStockName",
   		"sql" => " and c.salesStockName=# "
   	  ),
   array(
   		"name" => "salesStockCode",
   		"sql" => " and c.salesStockCode=# "
   	  ),
   array(
   		"name" => "packingStockId",
   		"sql" => " and c.packingStockId=# "
   	  ),
   array(
   		"name" => "packingStockName",
   		"sql" => " and c.packingStockName=# "
   	  ),
   array(
   		"name" => "packingStockCode",
   		"sql" => " and c.packingStockCode=# "
   	  ),
   array(
   		"name" => "outStockId",
   		"sql" => " and c.outStockId=# "
   	  ),
   array(
   		"name" => "outStockName",
   		"sql" => " and c.outStockName=# "
   	  ),
   array(
   		"name" => "outStockCode",
   		"sql" => " and c.outStockCode=# "
   	  )
)
?>