<?php
/**
 * @author Show
 * @Date 2012��11��27�� ���ڶ� 10:31:09
 * @version 1.0
 * @description:ָ������ѡ�� sql�����ļ�
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.name ,c.score ,c.mainId  from oa_esm_ass_options c where 1=1 "
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
   		"name" => "score",
   		"sql" => " and c.score=# "
   	  ),
   array(
   		"name" => "mainId",
   		"sql" => " and c.mainId=# "
   	  )
)
?>