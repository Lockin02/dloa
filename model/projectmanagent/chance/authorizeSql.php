<?php
/**
 * @author Administrator
 * @Date 2012-09-08 14:24:37
 * @version 1.0
 * @description:商机团队成员权限表 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.chanceId ,c.trackman ,c.trackmanId ,c.limit  from oa_sale_chance_authorize c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "chanceId",
   		"sql" => " and c.chanceId=# "
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
   		"name" => "limit",
   		"sql" => " and c.limit=# "
   	  )
)
?>