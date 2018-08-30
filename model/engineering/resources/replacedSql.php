<?php
/**
 * @author Administrator
 * @Date 2012-11-19 14:52:42
 * @version 1.0
 * @description:设备管理-可替换设备管理 sql配置文件 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.deviceName ,c.deviceId ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime  from oa_esm_resource_replaced c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "deviceName",
   		"sql" => " and c.deviceName=# "
   	  ),
   array(
   		"name" => "deviceId",
   		"sql" => " and c.deviceId=# "
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
   	  )
)
?>