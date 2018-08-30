<?php
/**
 * @author Michael
 * @Date 2014年3月24日 星期一 14:50:04
 * @version 1.0
 * @description:租车合同租赁车辆信息 sql配置文件
 */
$sql_arr = array (
	"select_default"=>"select c.id ,c.contractId ,c.orderCode ,c.carModel ,c.carModelCode ,c.carNumber ,c.driver ,c.idNumber ,c.displacement ,c.isTemp ,c.originalId ,c.isDel from oa_contract_vehicle c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "contractId",
   		"sql" => " and c.contractId=# "
   	  ),
   array(
   		"name" => "orderCode",
   		"sql" => " and c.orderCode=# "
   	  ),
   array(
   		"name" => "carModel",
   		"sql" => " and c.carModel=# "
   	  ),
   array(
   		"name" => "carNumber",
   		"sql" => " and c.carNumber=# "
   	  ),
   array(
   		"name" => "driver",
   		"sql" => " and c.driver=# "
   	  ),
   array(
   		"name" => "idNumber",
   		"sql" => " and c.idNumber=# "
   	  ),
   array(
   		"name" => "displacement",
   		"sql" => " and c.displacement=# "
        ),
   array(
         "name" => "isTemp",
         "sql" => " and c.isTemp=# "
   	  ),
   array(
         "name" => "originalId",
         "sql" => " and c.originalId=# "
        ),
   array(
         "name" => "isDel",
         "sql" => " and c.isDel=# "
        )
)
?>