<?php
/**
 * @author Administrator
 * @Date 2013��5��29�� 11:33:37
 * @version 1.0
 * @description:�̻�Ӳ���豸�� sql�����ļ� 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.chanceId ,c.hardwareName ,c.hardwareId ,c.number ,c.price ,c.money  from oa_sale_chance_hardware c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "chanceId",
   		"sql" => " and c.chanceId=# "
   	  ),
   array(
   		"name" => "hardwareName",
   		"sql" => " and c.hardwareName=# "
   	  ),
   array(
   		"name" => "hardwareId",
   		"sql" => " and c.hardwareId=# "
   	  ),
   array(
   		"name" => "number",
   		"sql" => " and c.number=# "
   	  ),
   array(
   		"name" => "price",
   		"sql" => " and c.price=# "
   	  ),
   array(
   		"name" => "money",
   		"sql" => " and c.money=# "
   	  )
)
?>