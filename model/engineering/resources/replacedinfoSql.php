<?php
/**
 * @author Administrator
 * @Date 2012-11-19 14:53:06
 * @version 1.0
 * @description:�豸����-���滻�豸����-���滻���� sql�����ļ�
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.replacedId ,c.remark,c.deviceName ,c.deviceId ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime  from oa_esm_resource_replacedInfo c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "replacedId",
   		"sql" => " and c.replacedId=# "
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