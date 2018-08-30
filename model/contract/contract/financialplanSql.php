<?php
/**
 * @author Administrator
 * @Date 2012年3月8日 14:15:29
 * @version 1.0
 * @description:收开计划表 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.contractName ,c.contractCode ,c.contractId ,c.invoiceMoney ,c.incomeMoney,c.planDate ,c.changeTips ," .
         		"c.remark ,c.isTemp ,c.originalId,c.changeTips" .
         		"  from oa_contract_financialplan c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.id=# "
        ),
   array(
   		"name" => "contractName",
   		"sql" => " and c.contractName=# "
   	  ),
   array(
   		"name" => "contractCode",
   		"sql" => " and c.contractCode=# "
   	  ),
   array(
   		"name" => "contractId",
   		"sql" => " and c.contractId=# "
   	  ),
   array(
   		"name" => "invoiceMoney",
   		"sql" => " and c.invoiceMoney=# "
   	  ),
   array(
   		"name" => "incomeMoney",
   		"sql" => " and c.incomeMoney=# "
   	  ),
   array(
   		"name" => "planDate",
   		"sql" => " and c.planDate=# "
   	  ),
   array(
   		"name" => "changeTips",
   		"sql" => " and c.changeTips=# "
   	  ),
   array(
   		"name" => "remark",
   		"sql" => " and c.remark=# "
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