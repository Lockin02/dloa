<?php
/**
 * @author Administrator
 * @Date 2012-07-23 14:04:07
 * @version 1.0
 * @description:职位申请表-项目经历 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.employmentId ,c.beginDate ,c.closeDate ,c.projectName ,c.projectSkill ,c.projectRole  from oa_hr_employment_project c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "beginDate",
   		"sql" => " and c.beginDate=# "
   	  ),
   array(
   		"name" => "closeDate",
   		"sql" => " and c.closeDate=# "
   	  ),
   array(
   		"name" => "projectName",
   		"sql" => " and c.projectName=# "
   	  ),
   array(
   		"name" => "projectSkill",
   		"sql" => " and c.projectSkill=# "
   	  ),
   array(
   		"name" => "projectRole",
   		"sql" => " and c.projectRole=# "
   	  ),
   array(
   		"name" => "employmentId",
   		"sql" => " and c.employmentId=# "
   	  )
)
?>