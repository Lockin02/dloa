<?php
$sql_arr = array (
         "select_default"=>"select c.id ,c.userId ,c.caseName ,c.modelName,c.caseSql ,c.updateId ,c.updateName,c.createId,c.createName,c.createTime,c.remark  from oa_adv_case c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "userId",
   		"sql" => " and c.userId=# "
   	  ),
   array(
   		"name" => "modelName",
   		"sql" => " and c.modelName=# "
   	  )
);
?>