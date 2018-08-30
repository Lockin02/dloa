<?php
$sql_arr = array (
         "select_default"=>"select c.id ,c.objType ,c.objId ,c.objCode," .
         		"c.op ,c.reObjType ,c.reObjCode,c.reObjId,c.createId,c.createName," .
         		"c.createTime,c.remark  from oa_system_track_log c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "objType",
   		"sql" => " and c.objType=# "
   	  ),
   array(
   		"name" => "objId",
   		"sql" => " and c.objId=# "
   	  ),
   array(
   		"name" => "objCode",
   		"sql" => " and c.objCode=# "
   	  ),
   array(
   		"name" => "op",
   		"sql" => " and c.op=# "
   	  ),
   array(
   		"name" => "reObjType",
   		"sql" => " and c.reObjType=# "
   	  ),
   array(
   		"name" => "reObjId",
   		"sql" => " and c.reObjId=# "
   	  ),
   array(
   		"name" => "createId",
   		"sql" => " and c.createId=# "
   	  )
);
?>