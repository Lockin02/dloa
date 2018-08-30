<?php
/**
 * @author huangzf
 * @Date 2011年11月1日 11:20:04
 * @version 1.0
 * @description:系统日志设置 sql配置文件 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.tableName ,c.businessName ,c.pkName ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime  from oa_syslog_setting c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "tableName",
   		"sql" => " and c.tableName=# "
   	  ),
   array(
   		"name" => "businessName",
   		"sql" => " and c.businessName=# "
   	  ),
   array(
   		"name" => "pkName",
   		"sql" => " and c.pkName=# "
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
   		"name" => "updateTime",
   		"sql" => " and c.updateTime=# "
   	  )
)
?>