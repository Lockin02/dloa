<?php
/**
 * @author show
 * @Date 2013��10��17�� 15:38:39
 * @version 1.0
 * @description:��Ŀ��Ԥ���� sql�����ļ� 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.mainId ,c.parentName ,c.costTypeName ,c.budget ,c.fee ,c.processAct  from oa_esm_project_weekfee c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "mainId",
   		"sql" => " and c.mainId=# "
   	  ),
   array(
   		"name" => "parentName",
   		"sql" => " and c.parentName=# "
   	  ),
   array(
   		"name" => "costTypeName",
   		"sql" => " and c.costTypeName=# "
   	  ),
   array(
   		"name" => "budget",
   		"sql" => " and c.budget=# "
   	  ),
   array(
   		"name" => "fee",
   		"sql" => " and c.fee=# "
   	  ),
   array(
   		"name" => "processAct",
   		"sql" => " and c.processAct=# "
   	  )
)
?>