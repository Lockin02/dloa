<?php
/**
 * สนำรืดฬฌsql
 */
$sql_arr = array (
	"select_useStatus" => "select c.id,c.name,c.deprFlag,c.remark,c.status,c.createName,c.createId,c.createTime,c.updateName,c.updateId,c.updateTime from oa_asset_useStatus c where  1=1 "
);
$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.id=# "
        ),
   array(
   		"name" => "name",
   		"sql" => " and c.name like CONCAT('%',#,'%')"
   	  ),
   array(
   		"name" => "deprFlag",
   		"sql" => " and c.deprFlag like CONCAT('%',#,'%')"
   	  ),
    array(
   		"name" => "remark",
   		"sql" => " and c.remark like CONCAT('%',#,'%')"
   	  ),
   array(
   		"name" => "status",
   		"sql" => " and c.status like CONCAT('%',#,'%')"
   	  )

)
?>