<?php
/**
 * @author Administrator
 * @Date 2011年11月28日 10:42:46
 * @version 1.0
 * @description:员工借试用续借单 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.borrowId ,c.raendDate ,c.reendDate ,c.renewremark ,c.renewdate ,c.renewName ,c.renewNameId ,c.ExaStatus ,c.ExaDT ,c.updateTime ,c.updateName ,c.updateId ,c.createTime ,c.createName ,c.createId,c.reStatus  from oa_borrow_renew c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "borrowId",
   		"sql" => " and c.borrowId=# "
   	  ),
   array(
   		"name" => "raendDate",
   		"sql" => " and c.raendDate=# "
   	  ),
   array(
   		"name" => "reendDate",
   		"sql" => " and c.reendDate=# "
   	  ),
   array(
   		"name" => "renewremark",
   		"sql" => " and c.renewremark=# "
   	  ),
   array(
   		"name" => "renewdate",
   		"sql" => " and c.renewdate=# "
   	  ),
   array(
   		"name" => "renewName",
   		"sql" => " and c.renewName=# "
   	  ),
   array(
   		"name" => "renewNameId",
   		"sql" => " and c.renewNameId=# "
   	  ),
   array(
   		"name" => "ExaStatus",
   		"sql" => " and c.ExaStatus=# "
   	  ),
   array(
   		"name" => "ExaDT",
   		"sql" => " and c.ExaDT=# "
   	  ),
   array(
   		"name" => "updateTime",
   		"sql" => " and c.updateTime=# "
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
   		"name" => "createTime",
   		"sql" => " and c.createTime=# "
   	  ),
   array(
   		"name" => "createName",
   		"sql" => " and c.createName=# "
   	  ),
   array(
   		"name" => "createId",
   		"sql" => " and c.createId=# "
   	  )
)
?>