<?php
/**
 * @author Administrator
 * @Date 2012��7��9�� ����һ 14:16:48
 * @version 1.0
 * @description:ְλ����Ҫ�� sql�����ļ� 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.parentId ,c.parentCode ,c.positionName ,c.featureItem ,c.contents  from oa_hr_position_ability c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "parentId",
   		"sql" => " and c.parentId=# "
   	  ),
   array(
   		"name" => "parentCode",
   		"sql" => " and c.parentCode=# "
   	  ),
   array(
   		"name" => "positionName",
   		"sql" => " and c.positionName=# "
   	  ),
   array(
   		"name" => "featureItem",
   		"sql" => " and c.featureItem=# "
   	  ),
   array(
   		"name" => "contents",
   		"sql" => " and c.contents=# "
   	  )
)
?>