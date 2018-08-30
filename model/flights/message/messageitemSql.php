<?php
/**
 * @author sony
 * @Date 2013年7月10日 17:37:38
 * @version 1.0
 * @description:改签子表字段 sql配置文件 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.mainId ,c.changeNum ,c.startDate ,c.arriveDate ,c.arrivePlace ,c.changeCost ,c.changeReason ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime ,c.profession ,c.ticketSum ,c.icondition  from oa_flights_message_item c where 1=1 "
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
   		"name" => "changeNum",
   		"sql" => " and c.changeNum=# "
   	  ),
   array(
   		"name" => "startDate",
   		"sql" => " and c.startDate=# "
   	  ),
   array(
   		"name" => "arriveDate",
   		"sql" => " and c.arriveDate=# "
   	  ),
   array(
   		"name" => "arrivePlace",
   		"sql" => " and c.arrivePlace=# "
   	  ),
   array(
   		"name" => "changeCost",
   		"sql" => " and c.changeCost=# "
   	  ),
   array(
   		"name" => "changeReason",
   		"sql" => " and c.changeReason=# "
   	  ),
   array(
   		"name" => "createId",
   		"sql" => " and c.createId=# "
   	  ),
   array(
   		"name" => "createName",
   		"sql" => " and c.createName=# "
   	  ),
   array(
   		"name" => "createTime",
   		"sql" => " and c.createTime=# "
   	  ),
   array(
   		"name" => "updateId",
   		"sql" => " and c.updateId=# "
   	  ),
   array(
   		"name" => "updateName",
   		"sql" => " and c.updateName=# "
   	  ),
   array(
   		"name" => "updateTime",
   		"sql" => " and c.updateTime=# "
   	  ),
   array(
   		"name" => "profession",
   		"sql" => " and c.profession=# "
   	  ),
   array(
   		"name" => "ticketSum",
   		"sql" => " and c.ticketSum=# "
   	  ),
   array(
   		"name" => "icondition",
   		"sql" => " and c.icondition=# "
   	  )
)
?>