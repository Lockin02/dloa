<?php
/**
 * @author Administrator
 * @Date 2012��1��11�� 16:58:32
 * @version 1.0
 * @description:������Ա sql�����ļ� 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.formCode ,c.parentId ,c.assesManId ,c.assesManName  from oa_supp_suppasses_menber c where 1=1 "
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