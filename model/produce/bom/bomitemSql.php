<?php
/**
 * @author Administrator
 * @Date 2011年12月30日 11:43:26
 * @version 1.0
 * @description:BOM分录表 sql配置文件 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.mainId ,c.productId ,c.productCode ,c.productName ,c.pattern ,c.unitName ,c.properties ,c.useNum ,c.useStatus ,c.planPercent ,c.lossRate ,c.effectiveDate ,c.expirationDate ,c.isAllow ,c.productType ,c.configPro ,c.isCharacter ,c.isKeyObj ,c.stockCode ,c.stockName ,c.stockId ,c.remark  from oa_produce_bom_item c where 1=1 "
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
   		"name" => "pattern",
   		"sql" => " and c.pattern=# "
   	  ),
   array(
   		"name" => "unitName",
   		"sql" => " and c.unitName=# "
   	  ),
   array(
   		"name" => "properties",
   		"sql" => " and c.properties=# "
   	  ),
   array(
   		"name" => "useNum",
   		"sql" => " and c.useNum=# "
   	  ),
   array(
   		"name" => "useStatus",
   		"sql" => " and c.useStatus=# "
   	  ),
   array(
   		"name" => "planPercent",
   		"sql" => " and c.planPercent=# "
   	  ),
   array(
   		"name" => "lossRate",
   		"sql" => " and c.lossRate=# "
   	  ),
   array(
   		"name" => "effectiveDate",
   		"sql" => " and c.effectiveDate=# "
   	  ),
   array(
   		"name" => "expirationDate",
   		"sql" => " and c.expirationDate=# "
   	  ),
   array(
   		"name" => "isAllow",
   		"sql" => " and c.isAllow=# "
   	  ),
   array(
   		"name" => "productType",
   		"sql" => " and c.productType=# "
   	  ),
   array(
   		"name" => "configPro",
   		"sql" => " and c.configPro=# "
   	  ),
   array(
   		"name" => "isCharacter",
   		"sql" => " and c.isCharacter=# "
   	  ),
   array(
   		"name" => "isKeyObj",
   		"sql" => " and c.isKeyObj=# "
   	  ),
   array(
   		"name" => "stockCode",
   		"sql" => " and c.stockCode=# "
   	  ),
   array(
   		"name" => "stockName",
   		"sql" => " and c.stockName=# "
   	  ),
   array(
   		"name" => "stockId",
   		"sql" => " and c.stockId=# "
   	  ),
   array(
   		"name" => "remark",
   		"sql" => " and c.remark=# "
   	  )
)
?>