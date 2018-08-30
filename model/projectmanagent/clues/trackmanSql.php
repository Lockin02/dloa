<?php
/**
 * @author Administrator
 * @Date 2011年3月5日 10:20:18
 * @version 1.0
 * @description:线索跟踪人 sql配置文件 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.trackman ,c.trackmanId ,c.cluesId ,c.cluesName ,c.cluesCode  from oa_sale_clues_trackman c where 1=1 "
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
   		"name" => "cluesId",
   		"sql" => " and c.cluesId=# "
   	  ),
   array(
   		"name" => "cluesName",
   		"sql" => " and c.cluesName=# "
   	  ),
   array(
   		"name" => "cluesCode",
   		"sql" => " and c.cluesCode=# "
   	  )
)
?>