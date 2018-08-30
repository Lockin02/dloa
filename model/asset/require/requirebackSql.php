<?php
/**
 * @author Administrator
 * @Date 2013年8月1日 8:23:39
 * @version 1.0
 * @description:oa_asset_requireback sql配置文件 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.requireId ,c.backReason ,c.createName ,c.createId ,c.createTime  from oa_asset_requireback c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "requireId",
   		"sql" => " and c.requireId=# "
   	  ),
   array(
   		"name" => "backReason",
   		"sql" => " and c.backReason=# "
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
   		"name" => "createTime",
   		"sql" => " and c.createTime=# "
   	  )
)
?>