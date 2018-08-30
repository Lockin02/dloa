<?php
/**
 * @author tse
 * @Date 2014年1月4日 9:23:34
 * @version 1.0
 * @description:项目文档类型 sql配置文件 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.mainId ,c.projectId ,c.fileName as name,c.fileType ,c.isNeedUpload ,c.description  from oa_esm_file_type c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "mainId",
   		"sql" => " and c.mainId=# "
   	  ),
   array(
   		"name" => "projectId",
   		"sql" => " and c.projectId=# "
   	  ),
   array(
   		"name" => "fileName",
   		"sql" => " and c.fileName=# "
   	  ),
   array(
   		"name" => "fileType",
   		"sql" => " and c.fileType=# "
   	  ),
   array(
   		"name" => "isNeedUpload",
   		"sql" => " and c.isNeedUpload=# "
   	  ),
   array(
   		"name" => "description",
   		"sql" => " and c.description=# "
   	  )
)
?>