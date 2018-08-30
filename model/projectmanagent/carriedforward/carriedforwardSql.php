<?php
/**
 * @author Show
 * @Date 2011年7月30日 15:23:12
 * @version 1.0
 * @description:合同结转表 sql配置文件
 */
$sql_arr = array (
    "select_default"=>"select c.id ,c.saleId ,c.saleCode ,c.saleType ,c.outStockId ,c.outStockCode ,c.outStockType ,c.thisDate ,c.status,createName  from oa_sale_carriedforward c where 1=1 "
);

$condition_arr = array (
	array(
       "name" => "id",
   		"sql" => " and c.Id=# "
    ),
   array(
   		"name" => "saleId",
   		"sql" => " and c.saleId=# "
    ),
   array(
   		"name" => "saleCode",
   		"sql" => " and c.saleCode=# "
    ),
   array(
        "name" => "saleCodeSearch",
        "sql" => " and c.saleCode like concat('%' , # , '%')"
    ),
   array(
   		"name" => "saleType",
   		"sql" => " and c.saleType=# "
    ),
   array(
   		"name" => "outStockId",
   		"sql" => " and c.outStockId=# "
    ),
   array(
   		"name" => "outStockCode",
   		"sql" => " and c.outStockCode=# "
    ),
   array(
        "name" => "outStockCodeSearch",
        "sql" => " and c.outStockCode like concat('%' , # , '%')"
    ),
   array(
   		"name" => "outStockType",
   		"sql" => " and c.outStockType=# "
    ),
   array(
   		"name" => "thisDate",
   		"sql" => " and c.thisDate=# "
    ),
   array(
   		"name" => "status",
   		"sql" => " and c.status=# "
    )
)
?>