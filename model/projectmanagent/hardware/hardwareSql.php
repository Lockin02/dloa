<?php
/**
 * @author Administrator
 * @Date 2013年5月29日 10:08:38
 * @version 1.0
 * @description:商机设备硬件管理 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.hardwareName ,c.updateTime ,c.updateName ,c.updateId ,c.createTime ,c.createName ,c.createId ,c.isUse  from oa_sale_hardware c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "hardwareName",
   		"sql" => " and c.hardwareName like CONCAT('%',#,'%') "
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
   		"name" => "isUse",
   		"sql" => " and c.isUse=# "
   	  )
)
?>