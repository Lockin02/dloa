<?php
/**
 * @author yxin1
 * @Date 2014年4月24日 9:53:16
 * @version 1.0
 * @description:法定节假日详细 sql配置文件 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.parentId ,c.holiday ,c.remark  from oa_hr_worktime_setequ c where 1=1 "
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
   		"name" => "holiday",
   		"sql" => " and c.holiday=# "
   	  ),
   array(
   		"name" => "remark",
   		"sql" => " and c.remark=# "
   	  )
)
?>