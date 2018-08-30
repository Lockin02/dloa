<?php
/**
 * @author Administrator
 * @Date 2012-08-02 15:58:33
 * @version 1.0
 * @description:竞争对手 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.chanceId ,c.competitor ,c.superiority ,c.disadvantaged ,c.remark  from oa_sale_chance_competitor c where 1=1 "
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
   		"name" => "competitor",
   		"sql" => " and c.competitor=# "
   	  ),
   array(
   		"name" => "superiority",
   		"sql" => " and c.superiority=# "
   	  ),
   array(
   		"name" => "disadvantaged",
   		"sql" => " and c.disadvantaged=# "
   	  ),
   array(
   		"name" => "remark",
   		"sql" => " and c.remark=# "
   	  )
)
?>