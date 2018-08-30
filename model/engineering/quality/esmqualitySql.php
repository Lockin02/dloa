<?php
/**
 * @author Show
 * @Date 2011年12月8日 星期四 18:57:10
 * @version 1.0
 * @description:项目质量(oa_esm_project_quality) sql配置文件
 */
$sql_arr = array (
    "select_default"=>"select c.id ,c.projectId ,c.projectCode ,c.projectName ,c.qualityProblem ,c.problemType ,c.isDeal ,c.solution ,c.results ,c.submiterName ,c.submiterCode ,c.submitDate ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime  from oa_esm_project_quality c where 1=1 "
);

$condition_arr = array (
	array(
       "name" => "id",
   		"sql" => " and c.Id=# "
    ),
   array(
   		"name" => "projectId",
   		"sql" => " and c.projectId=# "
    ),
   array(
   		"name" => "projectCode",
   		"sql" => " and c.projectCode=# "
    ),
   array(
   		"name" => "projectName",
   		"sql" => " and c.projectName=# "
    ),
   array(
   		"name" => "qualityProblem",
   		"sql" => " and c.qualityProblem like CONCAT('%',#,'%') "
    ),
   array(
   		"name" => "problemType",
   		"sql" => " and c.problemType like CONCAT('%',#,'%') "
    ),
   array(
   		"name" => "isDeal",
   		"sql" => " and c.isDeal=# "
    ),
   array(
   		"name" => "solution",
   		"sql" => " and c.solution=# "
    ),
   array(
   		"name" => "results",
   		"sql" => " and c.results=# "
    ),
   array(
   		"name" => "submiterName",
   		"sql" => " and c.submiterName=# "
    ),
   array(
   		"name" => "submiterCode",
   		"sql" => " and c.submiterCode=# "
    ),
   array(
   		"name" => "submitDate",
   		"sql" => " and c.submitDate=# "
    ),
   array(
   		"name" => "createId",
   		"sql" => " and c.createId=# "
    ),
   array(
   		"name" => "createName",
   		"sql" => " and c.createName=# "
    ),
   array(
   		"name" => "createTime",
   		"sql" => " and c.createTime=# "
    ),
   array(
   		"name" => "updateId",
   		"sql" => " and c.updateId=# "
    ),
   array(
   		"name" => "updateName",
   		"sql" => " and c.updateName=# "
    ),
   array(
   		"name" => "updateTime",
   		"sql" => " and c.updateTime=# "
    )
)
?>