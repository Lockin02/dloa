<?php
/**
 * @author show
 * @Date 2013年10月17日 15:38:39
 * @version 1.0
 * @description:项目周预决算 sql配置文件 
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