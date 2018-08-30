<?php
/**
 * @author Administrator
 * @Date 2011年1月17日 13:05:29
 * @version 1.0
 * @description:补库计划产品信息 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.fillUpId ,c.productId ,c.productName ,c.pattern,c.unitName,c.sequence ,c.stockNum ,c.arrivalNum ,c.unArrivalNum ,c.intentArrTime,c.issuedPurNum,c.qualityCode,c.qualityName,c.isPurch,c.amountAllOld,c.appOpinion,c.arrivalPeriod,c.purchPeriod,c.leastOrderNum  from oa_stock_fillup_detail c where c.isPurch=1 and 1=1 ",
         "select_all"=>"select c.id ,c.fillUpId ,c.productId ,c.productName ,c.pattern,c.unitName,c.sequence ,c.stockNum ,c.arrivalNum ,c.unArrivalNum ,c.intentArrTime,c.issuedPurNum,c.qualityCode,c.qualityName,c.isPurch,c.amountAllOld  from oa_stock_fillup_detail c where  1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "fillUpId",
   		"sql" => " and c.fillUpId=# "
   	  ),
   array(
   		"name" => "productId",
   		"sql" => " and c.productId=# "
   	  ),
   array(
   		"name" => "productName",
   		"sql" => " and c.productName=# "
   	  ),
   array(
   		"name" => "sequence",
   		"sql" => " and c.sequence=# "
   	  ),
   array(
   		"name" => "stockNum",
   		"sql" => " and c.stockNum=# "
   	  ),
   array(
   		"name" => "arrivalNum",
   		"sql" => " and c.arrivalNum=# "
   	  ),
   array(
   		"name" => "unArrivalNum",
   		"sql" => " and c.unArrivalNum=# "
   	  ),
   array(
   		"name" => "intentArrTime",
   		"sql" => " and c.intentArrTime=# "
   	  )
)
?>