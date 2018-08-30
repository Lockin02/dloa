<?php
/**
 * @author Administrator
 * @Date 2013年3月7日 星期四 9:56:38
 * @version 1.0
 * @description:检验项目 sql配置文件 
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