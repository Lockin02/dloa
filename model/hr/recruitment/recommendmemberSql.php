<?php
/**
 * @author Administrator
 * @Date 2012年7月13日 星期五 14:05:50
 * @version 1.0
 * @description:协助人 sql配置文件 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.formCode ,c.parentId ,c.assesManId ,c.assesManName  from oa_hr_recommend_menber c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "formCode",
   		"sql" => " and c.formCode=# "
   	  ),
   array(
   		"name" => "parentId",
   		"sql" => " and c.parentId=# "
   	  ),
   array(
   		"name" => "assesManId",
   		"sql" => " and c.assesManId=# "
   	  ),
   array(
   		"name" => "assesManName",
   		"sql" => " and c.assesManName=# "
   	  )
)
?>