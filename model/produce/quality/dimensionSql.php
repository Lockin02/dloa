<?php
/**
 * @author Administrator
 * @Date 2013��3��7�� ������ 9:56:38
 * @version 1.0
 * @description:������Ŀ sql�����ļ� 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.dimName  from oa_produce_quality_dimension c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "dimName",
   		"sql" => " and c.dimName=# "
   	  ),
   array(
   		"name" => "dimNameEq",
   		"sql" => " and c.dimName=# "
   	  )   	  
)
?>