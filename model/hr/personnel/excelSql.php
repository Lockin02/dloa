<?php
/**
 * @author Michael
 * @Date 2014��7��21�� 11:45:06
 * @version 1.0
 * @description:���¹���-������ѡ��¼ sql�����ļ� 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.checkValue ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime  from oa_hr_personnel_excel c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "checkValue",
   		"sql" => " and c.checkValue=# "
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