<?php
/**
 * @author Administrator
 * @Date 2011年5月8日 14:16:25
 * @version 1.0
 * @description:研发合同培训计划 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.orderCode ,c.orderName ,c.orderId ,c.beginDT ,c.endDT ,c.traNum ,c.adress ,c.content ,c.trainer ,c.isOver ,c.overDT,c.isTemp ,c.originalId  from oa_rdproject_trainingplan c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "orderCode",
   		"sql" => " and c.orderCode=# "
   	  ),
   array(
   		"name" => "orderName",
   		"sql" => " and c.orderName=# "
   	  ),
   array(
   		"name" => "orderId",
   		"sql" => " and c.orderId=# "
   	  ),
   array(
   		"name" => "beginDT",
   		"sql" => " and c.beginDT=# "
   	  ),
   array(
   		"name" => "endDT",
   		"sql" => " and c.endDT=# "
   	  ),
   array(
   		"name" => "traNum",
   		"sql" => " and c.traNum=# "
   	  ),
   array(
   		"name" => "adress",
   		"sql" => " and c.adress=# "
   	  ),
   array(
   		"name" => "content",
   		"sql" => " and c.content=# "
   	  ),
   array(
   		"name" => "trainer",
   		"sql" => " and c.trainer=# "
   	  ),
   array(
   		"name" => "isOver",
   		"sql" => " and c.isOver=# "
   	  ),
   array(
   		"name" => "overDT",
   		"sql" => " and c.overDT=# "
   	  )
)
?>