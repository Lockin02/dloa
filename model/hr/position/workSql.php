<?php
/**
 * @author Administrator
 * @Date 2012年7月9日 星期一 14:16:18
 * @version 1.0
 * @description:职位工作职责 sql配置文件 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.parentId ,c.parentCode ,c.positionName ,c.jobContents ,c.specificContents ,c.jobTarget  from oa_hr_position_work c where 1=1 "
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
   		"name" => "jobContents",
   		"sql" => " and c.jobContents=# "
   	  ),
   array(
   		"name" => "specificContents",
   		"sql" => " and c.specificContents=# "
   	  ),
   array(
   		"name" => "jobTarget",
   		"sql" => " and c.jobTarget=# "
   	  )
)
?>