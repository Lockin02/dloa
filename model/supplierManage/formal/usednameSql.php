<?php
/**
 * @author ACan
 * @Date 2016��6��14�� 14:51:41
 * @version 1.0
 * @description:��Ӧ�������� sql�����ļ� 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.suppId ,c.suppName ,c.busiCode ,c.usedName ,c.newName ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime  from oa_supp_usedname c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "suppId",
   		"sql" => " and c.suppId=# "
   	  ),
   array(
   		"name" => "suppName",
   		"sql" => " and c.suppName=# "
   	  ),
   array(
   		"name" => "busiCode",
   		"sql" => " and c.busiCode=# "
   	  ),
   array(
   		"name" => "usedName",
   		"sql" => " and c.usedName=# "
   	  ),
   array(
   		"name" => "newName",
   		"sql" => " and c.newName=# "
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