<?php
/**
 * @author Administrator
 * @Date 2012-11-08 19:21:39
 * @version 1.0
 * @description:商机关闭信息 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.handleType,c.chanceId ,c.closeInfo ,c.beforeStatus ,c.updateTime ,c.updateName ,c.updateId ,c.createTime ,c.createName ,c.createId  from oa_sale_chance_close c where 1=1 "
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
   		"name" => "closeInfo",
   		"sql" => " and c.closeInfo=# "
   	  ),
   array(
   		"name" => "beforeStatus",
   		"sql" => " and c.beforeStatus=# "
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