<?php
/**
 * @author Administrator
 * @Date 2013��10��9�� 9:41:20
 * @version 1.0
 * @description:���������� sql�����ļ� 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.name ,c.remark ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime  from oa_report_produce c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "name",
   		"sql" => " and c.name=# "
   	  ),
   array(
   		"name" => "remark",
   		"sql" => " and c.remark=# "
   	  ),
   array(
   		"name" => "createName",
   		"sql" => " and c.createName=# "
   	  ),
   array(
   		"name" => "createId",
   		"sql" => " and c.createId=# "
   	  ),
   array(
   		"name" => "createTime",
   		"sql" => " and c.createTime=# "
   	  ),
   array(
   		"name" => "updateName",
   		"sql" => " and c.updateName=# "
   	  ),
   array(
   		"name" => "updateId",
   		"sql" => " and c.updateId=# "
   	  ),
   array(
   		"name" => "updateTime",
   		"sql" => " and c.updateTime=# "
   	  )
)
?>