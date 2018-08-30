<?php
/**
 * @author Administrator
 * @Date 2013年11月5日 星期二 14:17:30
 * @version 1.0
 * @description:人员技术类型 sql配置文件 
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