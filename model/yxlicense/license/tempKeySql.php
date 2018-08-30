<?php
/**
 * @author Show
 * @Date 2011年5月13日 星期五 11:19:40
 * @version 1.0
 * @description:license值暂用存储表 sql配置文件
 */
$sql_arr = array (
    "select_default"=>"select c.id ,c.objId ,c.objCode ,c.objType ,c.extId ,c.extCode ,c.extType ,c.licenseType ,c.thisVal ,c.fileName ,c.fileUploadPath,c.extVal,c.templateId,c.rowVal,c.HWTplId  from oa_license_tempKey c where 1=1 ",
    "selectFileName"=>"select c.fileName  from oa_license_tempKey c where 1=1 "
);


$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
    ),
   array(
   		"name" => "objId",
   		"sql" => " and c.objId=# "
   ),
   array(
        "name" => "objIdIsNull",
        "sql" => " and c.objId is null "
   ),
   array(
   		"name" => "objCode",
   		"sql" => " and c.objCode=# "
   ),
   array(
   		"name" => "objType",
   		"sql" => " and c.objType=# "
   ),
   array(
   		"name" => "extId",
   		"sql" => " and c.extId=# "
   ),
   array(
   		"name" => "extCode",
   		"sql" => " and c.extCode=# "
   ),
   array(
   		"name" => "extType",
   		"sql" => " and c.extType=# "
   ),
   array(
   		"name" => "licenseType",
   		"sql" => " and c.licenseType=# "
   ),
   array(
   		"name" => "thisVal",
   		"sql" => " and c.thisVal=# "
   ),
   array(
   		"name" => "fileName",
   		"sql" => " and c.fileName=# "
   ),
   array(
   		"name" => "fileUploadPath",
   		"sql" => " and c.fileUploadPath=# "
   ),
   array(
   		"name" => "rowVal",
   		"sql" => " and c.rowVal=# "
   ),
)
?>