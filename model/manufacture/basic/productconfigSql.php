<?php
/**
 * @author Michael
 * @Date 2015��3��24�� 9:40:31
 * @version 1.0
 * @description:�����������ñ�ͷ sql�����ļ� 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.processId ,c.configId ,c.configCode ,c.configName ,c.configType ,c.configTypeId ,c.colName ,c.colCode ,c.colNum  from oa_manufacture_productconfig c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "processId",
   		"sql" => " and c.processId=# "
   	  ),
   array(
   		"name" => "configId",
   		"sql" => " and c.configId=# "
   	  ),
   array(
   		"name" => "configCode",
   		"sql" => " and c.configCode=# "
   	  ),
   array(
   		"name" => "configName",
   		"sql" => " and c.configName=# "
   	  ),
   array(
   		"name" => "configType",
   		"sql" => " and c.configType=# "
   	  ),
   array(
   		"name" => "configTypeId",
   		"sql" => " and c.configTypeId=# "
   	  ),
   array(
   		"name" => "colName",
   		"sql" => " and c.colName=# "
   	  ),
   array(
   		"name" => "colCode",
   		"sql" => " and c.colCode=# "
   	  ),
   array(
   		"name" => "colNum",
   		"sql" => " and c.colNum=# "
   	  )
)
?>