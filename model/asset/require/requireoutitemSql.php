<?php
/**
 * @author show
 * @Date 2014年09月01日
 * @version 1.0
 * @description:物料转资产申请明细sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.mainId ,c.assetId ,c.assetCode ,c.assetName ,c.salvage ,c.productId ,c.productCode ,c.productName ,
				c.spec ,c.number ,c.executedNum ,(c.number-if(c.executedNum is null,0,c.executedNum)) as shouldOutNum ,c.remark 
			from oa_asset_requireoutitem c where 1=1 "
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
		"name" => "assetId",
		"sql" => " and c.assetId=# "
	),
	array(
		"name" => "assetCode",
		"sql" => " and c.assetCode=# "
	),
	array(
		"name" => "assetName",
		"sql" => " and c.assetName=# "
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
   		"name" => "spec",
   		"sql" => " and c.spe=# "
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
   		"name" => "remark",
   		"sql" => " and c.remark=# "
   	  ),
   array(
   		"name" => "shouldOutNum",
   		"sql" => " and (c.number-if(c.executedNum is null,0,c.executedNum))># "
   	  )
)
?>