<?php
/**
 * @author Administrator
 * @Date 2011年3月3日 21:18:03
 * @version 1.0
 * @description:产品硬件配置 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.hardWareId ,c.configType,c.configName ,c.configPattern,c.configCode ,c.configNum ,c.explains  from oa_stock_product_configuration_temp c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "hardWareId",
   		"sql" => " and c.hardWareId=# "
   	  ),
   array(
   		"name" => "configType",
   		"sql" => " and c.configType=# "
   	  ),    	  
   array(
   		"name" => "configName",
   		"sql" => " and c.configName=# "
   	  ),
   array(
   		"name" => "configPattern",
   		"sql" => " and c.configPattern=# "
   	  ),
   array(
   		"name" => "configCode",
   		"sql" => " and c.configCode=# "
   	  ),   	  
   array(
   		"name" => "configNum",
   		"sql" => " and c.configNum=# "
   	  ),
   array(
   		"name" => "explains",
   		"sql" => " and c.explains=# "
   	  )
)
?>