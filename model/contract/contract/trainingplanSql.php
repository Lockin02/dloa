<?php
/**
 * @author Administrator
 * @Date 2012��3��8�� 14:13:18
 * @version 1.0
 * @description:��ͬ��ѵ�ƻ� sql�����ļ�
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.contractrCode ,c.contractId ," .
         		"c.contractName ,c.beginDT ,c.endDT ,c.traNum ," .
         		"c.adress ,c.content ,c.trainer ,c.isOver ,c.overDT ," .
         		"c.isTemp ,c.originalId,c.changeTips  from oa_contract_trainingplan c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "contractrCode",
   		"sql" => " and c.contractrCode=# "
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
   		"name" => "beginDT",
   		"sql" => " and c.beginDT=# "
   	  ),
   array(
   		"name" => "endDT",
   		"sql" => " and c.endDT=# "
   	  ),
   array(
   		"name" => "traNum",
   		"sql" => " and c.traNum=# "
   	  ),
   array(
   		"name" => "adress",
   		"sql" => " and c.adress=# "
   	  ),
   array(
   		"name" => "content",
   		"sql" => " and c.content=# "
   	  ),
   array(
   		"name" => "trainer",
   		"sql" => " and c.trainer=# "
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