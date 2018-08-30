<?php
/**
 * @author evan
 * @Date 2010年12月7日 9:19:54
 * @version 1.0
 * @description:项目任务书 oa_esm_mission sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.name ,c.contractId ,c.startDate ,c.endDate ,c.detailedDescription ,c.requirements ,c.personnelRequire ,c.status ,c.executor ,c.executorId ,c.executorTime ,c.projectId ,c.projectName  from oa_esm_mission c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
		"name" => "name",
		"sql" => "and c.name like CONCAT('%',#,'%')"
	),
	array(
		"name" => "projectName",
		"sql" => "and c.projectName like CONCAT('%',#,'%')"
	),
   array(
   		"name" => "contractId",
   		"sql" => " and c.contractId=# "
   	  ),
   array(
   		"name" => "startDate",
   		"sql" => " and c.startDate=# "
   	  ),
   array(
   		"name" => "endDate",
   		"sql" => " and c.endDate=# "
   	  ),
   array(
   		"name" => "detailedDescription",
   		"sql" => " and c.detailedDescription=# "
   	  ),
   array(
   		"name" => "requirements",
   		"sql" => " and c.requirements=# "
   	  ),
   array(
   		"name" => "personnelRequire",
   		"sql" => " and c.personnelRequire=# "
   	  ),
   array(
   		"name" => "status",
   		"sql" => " and c.status=# "
   	  ),
   array(
   		"name" => "executor",
   		"sql" => " and c.executor=# "
   	  ),
   array(
   		"name" => "executorId",
   		"sql" => " and c.executorId=# "
   	  ),
   array(
   		"name" => "executorTime",
   		"sql" => " and c.executorTime=# "
   	  ),
   array(
   		"name" => "projectId",
   		"sql" => " and c.projectId=# "
   	  ),
   array(
   		"name" => "projectName",
   		"sql" => " and c.projectName=# "
   	  )
)
?>