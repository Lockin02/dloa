<?php
/**
 * @author Show
 * @Date 2012��12��3�� ����һ 10:42:07
 * @version 1.0
 * @description:�ܱ�����ָ�� sql�����ļ� 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.weekId ,c.indexName ,c.indexId ,c.optionName ,c.optionId ,c.score  from oa_esm_weeklog_rsindex c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "weekId",
   		"sql" => " and c.weekId=# "
   	  ),
   array(
   		"name" => "indexName",
   		"sql" => " and c.indexName=# "
   	  ),
   array(
   		"name" => "indexId",
   		"sql" => " and c.indexId=# "
   	  ),
   array(
   		"name" => "optionName",
   		"sql" => " and c.optionName=# "
   	  ),
   array(
   		"name" => "optionId",
   		"sql" => " and c.optionId=# "
   	  ),
   array(
   		"name" => "score",
   		"sql" => " and c.score=# "
   	  )
)
?>