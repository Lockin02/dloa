<?php
/**
 * @author Administrator
 * @Date 2012年3月8日 14:14:51
 * @version 1.0
 * @description:合同开票计划 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.contractCode ,c.contractId ," .
         		"c.contractName ,c.money ,c.softMoney ,c.iTypeName ,c.iType ," .
         		"c.invDT ,c.remark ,c.isOver ,c.overDT ,c.isTemp ,c.originalId,c.changeTips" .
         		"  from oa_contract_invoice c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
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
   		"name" => "contractName",
   		"sql" => " and c.contractName=# "
   	  ),
   array(
   		"name" => "money",
   		"sql" => " and c.money=# "
   	  ),
   array(
   		"name" => "softMoney",
   		"sql" => " and c.softMoney=# "
   	  ),
   array(
   		"name" => "iTypeName",
   		"sql" => " and c.iTypeName=# "
   	  ),
   array(
   		"name" => "iType",
   		"sql" => " and c.iType=# "
   	  ),
   array(
   		"name" => "invDT",
   		"sql" => " and c.invDT=# "
   	  ),
   array(
   		"name" => "remark",
   		"sql" => " and c.remark=# "
   	  ),
   array(
   		"name" => "isOver",
   		"sql" => " and c.isOver=# "
   	  ),
   array(
   		"name" => "overDT",
   		"sql" => " and c.overDT=# "
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