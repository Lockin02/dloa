<?php
/**
 * @author Administrator
 * @Date 2012-10-24 15:52:02
 * @version 1.0
 * @description:合同信息备注 sql配置文件 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.contractId ,c.createName ,c.createId ,c.createTime ,c.content  from oa_contract_remark c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "contractId",
   		"sql" => " and c.contractId=# "
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
   		"name" => "content",
   		"sql" => " and c.content=# "
   	  )
)
?>