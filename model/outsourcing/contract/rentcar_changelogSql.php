<?php
/**
 * @author Show
 * @Date 2014年3月6日 星期四 10:13:09
 * @version 1.0
 * @description:租车同变更记录表 sql配置文件 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.tempId ,c.objId ,c.changeManId ,c.changeManName ,c.changeTime ,c.changeReason ,c.ExaStatus ,c.ExaDT ,c.auditOptions ,c.remark ,c.objField  from oa_contract_rentcar_changelog c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "tempId",
   		"sql" => " and c.tempId=# "
   	  ),
   array(
   		"name" => "objId",
   		"sql" => " and c.objId=# "
   	  ),
   array(
   		"name" => "changeManId",
   		"sql" => " and c.changeManId=# "
   	  ),
   array(
   		"name" => "changeManName",
   		"sql" => " and c.changeManName=# "
   	  ),
   array(
   		"name" => "changeTime",
   		"sql" => " and c.changeTime=# "
   	  ),
   array(
   		"name" => "changeReason",
   		"sql" => " and c.changeReason=# "
   	  ),
   array(
   		"name" => "ExaStatus",
   		"sql" => " and c.ExaStatus=# "
   	  ),
   array(
   		"name" => "ExaDT",
   		"sql" => " and c.ExaDT=# "
   	  ),
   array(
   		"name" => "auditOptions",
   		"sql" => " and c.auditOptions=# "
   	  ),
   array(
   		"name" => "remark",
   		"sql" => " and c.remark=# "
   	  ),
   array(
   		"name" => "objField",
   		"sql" => " and c.objField=# "
   	  )
)
?>