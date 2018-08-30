<?php
/**
 * @author Show
 * @Date 2012年11月27日 星期二 11:23:19
 * @version 1.0
 * @description:考核等级配置表 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.name ,c.upperLimit ,c.lowerLimit  from oa_esm_ass_level c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "name",
   		"sql" => " and c.name=# "
   	  ),
   array(
   		"name" => "upperLimit",
   		"sql" => " and c.upperLimit=# "
   	  ),
   array(
   		"name" => "lowerLimit",
   		"sql" => " and c.lowerLimit=# "
   	  )
)
?>