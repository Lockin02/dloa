<?php
/**
 * @author Show
 * @Date 2014��1��7�� ���ڶ� 9:21:37
 * @version 1.0
 * @description:������Ӧ��-������Դ��Ϣ sql�����ļ�
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.parentId ,c.area ,c.areaId ,c.carAmount ,c.driverAmount ,c.rentPrice  from oa_outsourcessupp_vehiclesuppequ c where 1=1 ",
         "select_sum"=>"select sum(c.carAmount) as carAmount ,sum(c.driverAmount) as driverAmount ,ROUND(avg(c.rentPrice) ,2) as rentPrice from oa_outsourcessupp_vehiclesuppequ c where 1=1 "
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
   		"name" => "area",
   		"sql" => " and c.area=# "
   	  ),
   array(
   		"name" => "areaId",
   		"sql" => " and c.areaId=# "
   	  ),
   array(
   		"name" => "carAmount",
   		"sql" => " and c.carAmount=# "
   	  ),
   array(
   		"name" => "driverAmount",
   		"sql" => " and c.driverAmount=# "
   	  ),
   array(
   		"name" => "rentPrice",
   		"sql" => " and c.rentPrice=# "
   	  )
)
?>