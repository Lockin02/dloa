<?php
/**
 * @author Administrator
 * @Date 2012年4月6日 16:39:39
 * @version 1.0
 * @description:赠送/物料审批关联表 sql配置文件 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.presentId ,c.rObjCode ,c.presentCode ,c.presentName ,c.presentType ,c.ExaStatus ,c.ExaDTOne ,c.ExaDT ,c.changeTips ,c.updateTime ,c.updateName ,c.updateId ,c.createTime ,c.createName ,c.createId ,c.isTemp ,c.originalId  from oa_present_equ_link c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "presentId",
   		"sql" => " and c.presentId=# "
   	  ),
   array(
   		"name" => "rObjCode",
   		"sql" => " and c.rObjCode=# "
   	  ),
   array(
   		"name" => "presentCode",
   		"sql" => " and c.presentCode=# "
   	  ),
   array(
   		"name" => "presentName",
   		"sql" => " and c.presentName=# "
   	  ),
   array(
   		"name" => "presentType",
   		"sql" => " and c.presentType=# "
   	  ),
   array(
   		"name" => "ExaStatus",
   		"sql" => " and c.ExaStatus=# "
   	  ),
   array(
   		"name" => "ExaDTOne",
   		"sql" => " and c.ExaDTOne=# "
   	  ),
   array(
   		"name" => "ExaDT",
   		"sql" => " and c.ExaDT=# "
   	  ),
   array(
   		"name" => "changeTips",
   		"sql" => " and c.changeTips=# "
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
   		"name" => "isTemp",
   		"sql" => " and c.isTemp=# "
   	  ),
   array(
   		"name" => "originalId",
   		"sql" => " and c.originalId=# "
   	  )
)
?>