<?php
/**
 * @author yxin1
 * @Date 2014年12月1日 13:43:29
 * @version 1.0
 * @description:表格指标表 sql配置文件 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.objCode ,c.objName ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime  from oa_system_gridindicators c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "objCode",
   		"sql" => " and c.objCode=# "
   	  ),
   array(
   		"name" => "objName",
   		"sql" => " and c.objName=# "
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