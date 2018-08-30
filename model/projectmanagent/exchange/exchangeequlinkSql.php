<?php
/**
 * @author Administrator
 * @Date 2012年4月12日 7:34:29
 * @version 1.0
 * @description:换货/物料审批关联表 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.exchangeId ,c.rObjCode ,c.exchangeCode ,c.exchangeName ,c.exchangeType ,c.ExaStatus ,c.ExaDTOne ,c.ExaDT ,c.changeTips ,c.updateTime ,c.updateName ,c.updateId ,c.createTime ,c.createName ,c.createId ,c.isTemp ,c.originalId from oa_exchange_equ_link c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "exchangeId",
   		"sql" => " and c.exchangeId=# "
   	  ),
   array(
   		"name" => "rObjCode",
   		"sql" => " and c.rObjCode=# "
   	  ),
   array(
   		"name" => "exchangeCode",
   		"sql" => " and c.exchangeCode=# "
   	  ),
   array(
   		"name" => "exchangeName",
   		"sql" => " and c.exchangeName=# "
   	  ),
   array(
   		"name" => "exchangeType",
   		"sql" => " and c.exchangeType=# "
   	  ),
   array(
   		"name" => "ExaStatus",
   		"sql" => " and c.ExaStatus=# "
   	  ),
   array(
   		"name" => "ExaDTOne",
   		"sql" => " and c.ExaDTOne=# "
   	  ),
   array(
   		"name" => "ExaDT",
   		"sql" => " and c.ExaDT=# "
   	  ),
   array(
   		"name" => "changeTips",
   		"sql" => " and c.changeTips=# "
   	  ),
   array(
   		"name" => "updateTime",
   		"sql" => " and c.updateTime=# "
   	  ),
   array(
   		"name" => "updateName",
   		"sql" => " and c.updateName=# "
   	  ),
   array(
   		"name" => "updateId",
   		"sql" => " and c.updateId=# "
   	  ),
   array(
   		"name" => "createTime",
   		"sql" => " and c.createTime=# "
   	  ),
   array(
   		"name" => "createName",
   		"sql" => " and c.createName=# "
   	  ),
   array(
   		"name" => "createId",
   		"sql" => " and c.createId=# "
   	  ),
   array(
   		"name" => "isTemp",
   		"sql" => " and c.isTemp=# "
   	  ),
   array(
   		"name" => "originalId",
   		"sql" => " and c.originalId=# "
   	  )
)
?>