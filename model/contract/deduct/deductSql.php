<?php
/**
 * @author Administrator
 * @Date 2012-04-11 20:09:54
 * @version 1.0
 * @description:©ш©НиЙгК sqlеДжцнд╪Ч
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.contractId ,c.state,c.dispose,c.contractName ,c.contractCode ,c.contractMoney ,c.deductMoney ,c.deductReason ,c.ExaStatus ,c.ExaDT ,c.updateTime ,c.updateName ,c.updateId ,c.createTime ,c.createName ,c.createId  from oa_contract_deduct c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
    array(
        "name" => "state",
        "sql" => " and c.state=# "
    ),
   array(
   		"name" => "contractId",
   		"sql" => " and c.contractId=# "
   	  ),
   array(
   		"name" => "contractName",
   		"sql" => " and c.contractName=# "
   	  ),
   array(
   		"name" => "contractCode",
   		"sql" => " and c.contractCode like CONCAT('%',#,'%')"
   	  ),
   array(
   		"name" => "contractMoney",
   		"sql" => " and c.contractMoney=# "
   	  ),
   array(
   		"name" => "deductMoney",
   		"sql" => " and c.deductMoney=# "
   	  ),
   array(
   		"name" => "deductReason",
   		"sql" => " and c.deductReason=# "
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
   	  )
)
?>