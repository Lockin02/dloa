<?php
/**
 * @author Administrator
 * @Date 2013��11��5�� ���ڶ� 14:17:30
 * @version 1.0
 * @description:��Ա�������� sql�����ļ� 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.parentId ,c.skillTypeName ,c.skillTypeCode ,c.levelName ,c.levelCode ,c.remark  from oa_outsourcesupp_personlevel c where 1=1 "
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
   		"name" => "skillTypeName",
   		"sql" => " and c.skillTypeName=# "
   	  ),
   array(
   		"name" => "skillTypeCode",
   		"sql" => " and c.skillTypeCode=# "
   	  ),
   array(
   		"name" => "levelName",
   		"sql" => " and c.levelName=# "
   	  ),
   array(
   		"name" => "levelCode",
   		"sql" => " and c.levelCode=# "
   	  ),
   array(
   		"name" => "remark",
   		"sql" => " and c.remark=# "
   	  )
)
?>