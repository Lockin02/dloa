<?php
/**
 * @author Administrator
 * @Date 2013��3��6�� ������ 17:29:18
 * @version 1.0
 * @description:���鷽ʽ sql�����ļ� 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.checkType  from oa_produce_quality_checktype c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "checkType",
   		"sql" => " and c.checkType=# "
   	  ),
 array(
   		"name" => "checkTypeEq",
   		"sql" => " and c.checkType=# "
   	  )   	  
)
?>