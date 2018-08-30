<?php
/**
 * @author Administrator
 * @Date 2012-08-09 09:35:57
 * @version 1.0
 * @description:离职清单模板 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.items ,c.parentCode,c.recipientName ,c.recipientId ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime,c.remark,c.advanceAffirm,c.sort ,c.mailAffirm ,c.sendPremise  from oa_leave_handover_formwork c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "items",
   		"sql" => " and c.items=# "
   	  ),
   array(
   		"name" => "parentCode",
   		"sql" => " and c.parentCode=# "
   	  ),
   array(
   		"name" => "recipientName",
   		"sql" => " and c.recipientName=# "
   	  ),
   array(
   		"name" => "recipientId",
   		"sql" => " and c.recipientId=# "
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
   	  ),
   array(
   		"name" => "advanceAffirm",
   		"sql" => " and c.advanceAffirm=# "
   	  ),
   array(
   		"name" => "sort",
   		"sql" => " and c.sort=# "
   	  )
)
?>