<?php
/**
 * @author Administrator
 * @Date 2012年8月6日 21:39:29
 * @version 1.0
 * @description:员工转正考核工作相关 sql配置文件 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.parentId ,c.parentCode ,c.workPoint ,c.outPoint ,c.finishTime ,c.ownType  from oa_hr_permanent_linkcontent c where 1=1 "
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
   		"name" => "workPoint",
   		"sql" => " and c.workPoint=# "
   	  ),
   array(
   		"name" => "outPoint",
   		"sql" => " and c.outPoint=# "
   	  ),
   array(
   		"name" => "finishTime",
   		"sql" => " and c.finishTime=# "
   	  ),
   array(
   		"name" => "ownType",
   		"sql" => " and c.ownType=# "
   	  )
)
?>