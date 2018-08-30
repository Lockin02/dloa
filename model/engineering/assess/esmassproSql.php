<?php
/**
 * @author Show
 * @Date 2012年12月1日 星期六 9:53:08
 * @version 1.0
 * @description:项目考核指标 sql配置文件 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.projectId ,c.projectCode ,c.projectName ,c.templateId ,c.templateName ,c.score ,c.indexIds ,c.indexNames ,c.needIndexIds ,c.needIndexNames ,c.baseScore ,c.needScore ,c.useIndexIds ,c.useIndexNames  from oa_esm_project_assess c where 1=1 "
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
   		"name" => "templateId",
   		"sql" => " and c.templateId=# "
   	  ),
   array(
   		"name" => "templateName",
   		"sql" => " and c.templateName=# "
   	  ),
   array(
   		"name" => "score",
   		"sql" => " and c.score=# "
   	  ),
   array(
   		"name" => "indexIds",
   		"sql" => " and c.indexIds=# "
   	  ),
   array(
   		"name" => "indexNames",
   		"sql" => " and c.indexNames=# "
   	  ),
   array(
   		"name" => "needIndexIds",
   		"sql" => " and c.needIndexIds=# "
   	  ),
   array(
   		"name" => "needIndexNames",
   		"sql" => " and c.needIndexNames=# "
   	  ),
   array(
   		"name" => "baseScore",
   		"sql" => " and c.baseScore=# "
   	  ),
   array(
   		"name" => "needScore",
   		"sql" => " and c.needScore=# "
   	  ),
   array(
   		"name" => "useIndexIds",
   		"sql" => " and c.useIndexIds=# "
   	  ),
   array(
   		"name" => "useIndexNames",
   		"sql" => " and c.useIndexNames=# "
   	  )
)
?>