<?php
/**
 * @author Show
 * @Date 2013��7��15�� 15:15:40
 * @version 1.0
 * @description:�ؿ���ڼ� sql�����ļ� 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.periodName ,c.periodTypeName ,c.periodType ,c.beginDays ,c.endDays ,c.description  from oa_contract_periodconfig c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "periodName",
   		"sql" => " and c.periodName=# "
   	  ),
   array(
   		"name" => "periodTypeName",
   		"sql" => " and c.periodTypeName=# "
   	  ),
   array(
   		"name" => "periodType",
   		"sql" => " and c.periodType=# "
   	  ),
   array(
   		"name" => "beginDays",
   		"sql" => " and c.beginDays=# "
   	  ),
   array(
   		"name" => "endDays",
   		"sql" => " and c.endDays=# "
   	  ),
   array(
   		"name" => "description",
   		"sql" => " and c.description=# "
   	  )
)
?>