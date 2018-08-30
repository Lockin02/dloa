<?php
/**
 * @author Show
 * @Date 2012年8月22日 星期三 17:25:00
 * @version 1.0
 * @description:任职资格-本专业领域经历 sql配置文件 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.applyId ,c.beginYear ,c.beginMonth ,c.endYear ,c.endMonth ,c.unitName ,c.deptName ,c.mainWork  from oa_hr_personnel_certifyapplyexp c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "applyId",
   		"sql" => " and c.applyId=# "
   	  ),
   array(
   		"name" => "beginYear",
   		"sql" => " and c.beginYear=# "
   	  ),
   array(
   		"name" => "beginMonth",
   		"sql" => " and c.beginMonth=# "
   	  ),
   array(
   		"name" => "endYear",
   		"sql" => " and c.endYear=# "
   	  ),
   array(
   		"name" => "endMonth",
   		"sql" => " and c.endMonth=# "
   	  ),
   array(
   		"name" => "unitName",
   		"sql" => " and c.unitName=# "
   	  ),
   array(
   		"name" => "deptName",
   		"sql" => " and c.deptName=# "
   	  ),
   array(
   		"name" => "mainWork",
   		"sql" => " and c.mainWork=# "
   	  )
)
?>