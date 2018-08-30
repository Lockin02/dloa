<?php
/**
 * @author Administrator
 * @Date 2012年5月16日 星期三 14:23:03
 * @version 1.0
 * @description:工作周报 sql配置文件 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.weekTitle ,c.weekBeginDate ,c.weekEndDate ,c.isAttention ,c.remark ,c.depId ,c.depName ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime  from oa_produce_weeklog c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "weekTitle",
   		"sql" => " and c.weekTitle=# "
   	  ),
   array(
   		"name" => "weekBeginDate",
   		"sql" => " and c.weekBeginDate=# "
   	  ),
   array(
   		"name" => "weekEndDate",
   		"sql" => " and c.weekEndDate=# "
   	  ),
   array(
   		"name" => "isAttention",
   		"sql" => " and c.isAttention=# "
   	  ),
   array(
   		"name" => "remark",
   		"sql" => " and c.remark=# "
   	  ),
   array(
   		"name" => "depId",
   		"sql" => " and c.depId=# "
   	  ),
   array(
   		"name" => "depName",
   		"sql" => " and c.depName=# "
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