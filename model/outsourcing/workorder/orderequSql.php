<?php
/**
 * @author phz
 * @Date 2014年1月18日 星期六 17:09:44
 * @version 1.0
 * @description:工单从表 sql配置文件 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.parentId ,c.personName ,c.personId ,c.IdCard ,c.email ,c.phone ,c.exceptStart ,c.exceptEnd ,c.price ,c.payWay ,c.payExplain ,c.remark  from oa_outsourcing_workorder_orderequ c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "parentId",
   		"sql" => " and c.parentId=# "
   	  ),
   array(
   		"name" => "personName",
   		"sql" => " and c.personName=# "
   	  ),
   array(
   		"name" => "personId",
   		"sql" => " and c.personId=# "
   	  ),
   array(
   		"name" => "IdCard",
   		"sql" => " and c.IdCard=# "
   	  ),
   array(
   		"name" => "email",
   		"sql" => " and c.email=# "
   	  ),
   array(
   		"name" => "exceptStart",
   		"sql" => " and c.exceptStart=# "
   	  ),
   array(
   		"name" => "exceptEnd",
   		"sql" => " and c.exceptEnd=# "
   	  ),
   array(
   		"name" => "price",
   		"sql" => " and c.price=# "
   	  ),
   array(
   		"name" => "payWay",
   		"sql" => " and c.payWay=# "
   	  ),
   array(
   		"name" => "payExplain",
   		"sql" => " and c.payExplain=# "
   	  ),
   array(
   		"name" => "remark",
   		"sql" => " and c.remark=# "
   	  )
)
?>