<?php
/**
 * @author Administrator
 * @Date 2013年3月6日 星期三 17:29:11
 * @version 1.0
 * @description:质量标准 sql配置文件 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.standardName  from oa_produce_quality_standard c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "standardName",
   		"sql" => " and c.standardName=# "
   	  ),
 array(
   		"name" => "standardNameEq",
   		"sql" => " and c.standardName=# "
   	  )   	  
)
?>