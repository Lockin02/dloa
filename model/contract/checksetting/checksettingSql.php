<?php
/**
 * @author tse
 * @Date 2014年4月1日 10:47:06
 * @version 1.0
 * @description:验收管理设置 sql配置文件 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.clause ,c.dateName ,c.dateCode ,c.days ,c.description  from oa_contract_check_setting c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "clause",
   		"sql" => " and c.clause=# "
   	  ),
   array(
   		"name" => "dateName",
   		"sql" => " and c.dateName=# "
   	  ),
   array(
   		"name" => "dateCode",
   		"sql" => " and c.dateCode=# "
   	  ),
   array(
   		"name" => "days",
   		"sql" => " and c.days=# "
   	  ),
   array(
   		"name" => "description",
   		"sql" => " and c.description=# "
   	  )
)
?>