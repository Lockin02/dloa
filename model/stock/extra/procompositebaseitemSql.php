<?php
/**
 * @author huangzf
 * @Date 2012年7月11日 星期三 13:43:27
 * @version 1.0
 * @description:常用设备备货时间及库存信息表清单 sql配置文件 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.mainId ,c.goodsId ,c.goodsName ,c.productId ,c.productCode ,c.productName ,c.pattern ,c.unitName ,c.purchDays ,c.deliverDays ,c.forecastSaleNum ,c.planPurchNum ,c.availableNum ,c.exeNum,c.isProduce ,c.remark  from oa_stock_extra_procompositeitem c where 1=1 "
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
   		"name" => "goodsId",
   		"sql" => " and c.goodsId=# "
   	  ),
   array(
   		"name" => "goodsName",
   		"sql" => " and c.goodsName=# "
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
   		"name" => "purchDays",
   		"sql" => " and c.purchDays=# "
   	  ),
   array(
   		"name" => "deliverDays",
   		"sql" => " and c.deliverDays=# "
   	  ),
   array(
   		"name" => "forecastSaleNum",
   		"sql" => " and c.forecastSaleNum=# "
   	  ),
   array(
   		"name" => "planPurchNum",
   		"sql" => " and c.planPurchNum=# "
   	  ),
   array(
   		"name" => "availableNum",
   		"sql" => " and c.availableNum=# "
   	  ),
   array(
   		"name" => "isProduce",
   		"sql" => " and c.isProduce=# "
   	  ),
   array(
   		"name" => "remark",
   		"sql" => " and c.remark=# "
   	  )
)
?>