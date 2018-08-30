<?php
/**
 * @author Administrator
 * @Date 2012-08-03 14:08:32
 * @version 1.0
 * @description:商机推进信息 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.chanceId ,c.boostType ,c.boostValue ,c.oldValue ,c.updateTime ,c.updateName ,c.updateId ,c.createTime ,c.createName ,c.createId  from oa_sale_chance_boost c where 1=1 "
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
   		"name" => "boostType",
   		"sql" => " and c.boostType=# "
   	  ),
   array(
   		"name" => "boostValue",
   		"sql" => " and c.boostValue=# "
   	  ),
   array(
   		"name" => "oldValue",
   		"sql" => " and c.oldValue=# "
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
   	  )
)
?>