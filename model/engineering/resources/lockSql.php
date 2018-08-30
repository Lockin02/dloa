<?php
$sql_arr = array (
         "select_default"=>"select c.id ,c.lockDate ,c.userId ,c.userName ,c.status from oa_esm_resource_lock c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.id=# "
        ),
   	array(
   		"name" => "lockDate",
   		"sql" => " and c.lockDate=# "
   	  	),
   	array(
   		"name" => "userId",
   		"sql" => " and c.userId=# "
   	  	),
   	array(
   		"name" => "userName",
   		"sql" => " and c.userName=# "
   	  	),
	array(
		"name" => "userNameLike",
		"sql" => " and c.userName like CONCAT('%',#,'%') "
	),
   	array(
   		"name" => "status",
   		"sql" => " and c.status=# "
   	  	)
)
?>