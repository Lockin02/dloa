<?php
/**
 * @author Michael
 * @Date 2014年3月6日 星期四 10:14:03
 * @version 1.0
 * @description:租车合同签收明细 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.parentId ,c.objId ,c.detailTypeCn ,c.detailType ,c.tempId ,c.detailId ,c.changeFieldCn ,c.changeField ,c.oldValue ,c.newValue ,c.objField  from oa_contract_rentcar_signlogdetail c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "parentId",
   		"sql" => " and c.parentId=# "
   	  ),
   array(
   		"name" => "objId",
   		"sql" => " and c.objId=# "
   	  ),
   array(
   		"name" => "detailTypeCn",
   		"sql" => " and c.detailTypeCn=# "
   	  ),
   array(
   		"name" => "detailType",
   		"sql" => " and c.detailType=# "
   	  ),
   array(
   		"name" => "tempId",
   		"sql" => " and c.tempId=# "
   	  ),
   array(
   		"name" => "detailId",
   		"sql" => " and c.detailId=# "
   	  ),
   array(
   		"name" => "changeFieldCn",
   		"sql" => " and c.changeFieldCn=# "
   	  ),
   array(
   		"name" => "changeField",
   		"sql" => " and c.changeField=# "
   	  ),
   array(
   		"name" => "oldValue",
   		"sql" => " and c.oldValue=# "
   	  ),
   array(
   		"name" => "newValue",
   		"sql" => " and c.newValue=# "
   	  ),
   array(
   		"name" => "objField",
   		"sql" => " and c.objField=# "
   	  )
)
?>