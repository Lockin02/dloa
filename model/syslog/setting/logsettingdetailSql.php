<?php
/**
 * @author huangzf
 * @Date 2011��11��1�� 11:20:37
 * @version 1.0
 * @description:ϵͳ��־������ϸ sql�����ļ� 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.mainId ,c.columnName ,c.columnText ,c.columnDataType  from oa_syslog_setting_detail c where 1=1 "
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
   		"name" => "columnName",
   		"sql" => " and c.columnName=# "
   	  ),
   array(
   		"name" => "columnText",
   		"sql" => " and c.columnText=# "
   	  ),
   array(
   		"name" => "columnDataType",
   		"sql" => " and c.columnDataType=# "
   	  )
)
?>