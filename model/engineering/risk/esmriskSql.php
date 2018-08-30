<?php
/**
 * @author Show
 * @Date 2011年12月10日 星期六 9:59:32
 * @version 1.0
 * @description:项目风险(oa_esm_project_risk) sql配置文件
 */
$sql_arr = array (
    "select_default"=>"select c.id ,c.projectId ,c.projectCode ,c.projectName ,c.riskName ,c.solution ,c.submiterName ,c.submiterCode ,c.submitDate ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime  from oa_esm_project_risk c where 1=1 "
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
   		"name" => "riskName",
   		"sql" => " and c.riskName like CONCAT('%',#,'%') "
    ),
   array(
   		"name" => "solution",
   		"sql" => " and c.solution like CONCAT('%',#,'%') "
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