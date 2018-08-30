<?php
/**
 * @author Administrator
 * @Date 2012-07-12 14:04:29
 * @version 1.0
 * @description:人资模板设置 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.formworkName ,c.isUse ,c.formworkContent  from oa_hr_formwork c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "ids",
   		"sql" => " and c.Id in(arr)"
        ),
   array(
   		"name" => "formworkName",
   		"sql" => " and c.formworkName like CONCAT('%',#,'%')"
   	  ),
   array(
   		"name" => "isUse",
   		"sql" => " and c.isUse=# "
   	  ),
   array(
   		"name" => "formworkContent",
   		"sql" => " and c.formworkContent=# "
   	  )
)
?>