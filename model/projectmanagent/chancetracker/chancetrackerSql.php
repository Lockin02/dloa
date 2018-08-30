<?php
/**
 * @author Administrator
 * @Date 2011年3月4日 14:56:13
 * @version 1.0
 * @description:商机跟踪人 sql配置文件 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.trackman ,c.trackmanId ,c.chanceId ,c.chanceName ,c.chanceCode  from oa_sale_chance_trackman c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "trackman",
   		"sql" => " and c.trackman=# "
   	  ),
   array(
   		"name" => "trackmanId",
   		"sql" => " and c.trackmanId=# "
   	  ),
   array(
   		"name" => "chanceId",
   		"sql" => " and c.chanceId=# "
   	  ),
   array(
   		"name" => "chanceName",
   		"sql" => " and c.chanceName=# "
   	  ),
   array(
   		"name" => "chanceCode",
   		"sql" => " and c.chanceCode=# "
   	  )
)
?>