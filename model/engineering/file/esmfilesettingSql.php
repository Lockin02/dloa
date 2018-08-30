<?php
/**
 * @author tse
 * @Date 2014年1月4日 9:23:11
 * @version 1.0
 * @description:项目文档设置 sql配置文件 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.fileName ,c.fileType ,c.isNeedUpload ,c.description  from oa_esm_flie_setting c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
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
   	  ),
	array(
		"name" => "fileNameSch",
		"sql" => " and c.fileName like concat('%', # ,'%' ) "
	),
	array(
		"name" => "fileTypeSch",
		"sql" => " and c.fileType like concat('%', # ,'%' ) "
	),
	array(
		"name" => "notIds",
		"sql" => " and c.id not in(arr) "
	),
	array(
		"name" => "ids",
		"sql" => " and c.id in(arr) "
	)
)
?>