<?php
/**
 * @author Administrator
 * @Date 2012年7月14日 星期六 14:10:24
 * @version 1.0
 * @description:增员申请协助人 sql配置文件 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.formCode ,c.parentId ,c.assesManId ,c.assesManName  from oa_hr_apply_menber c where 1=1 "
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