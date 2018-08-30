<?php
/**
 * @author Administrator
 * @Date 2011年3月4日 14:58:38
 * @version 1.0
 * @description:商机联系人信息表 sql配置文件 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.linkmanId ,c.linkmanName ,c.section ,c.post ,c.officeTel ,c.mobileTel ,c.email ,c.chanceCode ,c.chanceName ,c.chanceId  from oa_sale_chance_linkman c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "linkmanId",
   		"sql" => " and c.linkmanId=# "
   	  ),
   array(
   		"name" => "linkmanName",
   		"sql" => " and c.linkmanName=# "
   	  ),
   array(
   		"name" => "section",
   		"sql" => " and c.section=# "
   	  ),
   array(
   		"name" => "post",
   		"sql" => " and c.post=# "
   	  ),
   array(
   		"name" => "officeTel",
   		"sql" => " and c.officeTel=# "
   	  ),
   array(
   		"name" => "mobileTel",
   		"sql" => " and c.mobileTel=# "
   	  ),
   array(
   		"name" => "email",
   		"sql" => " and c.email=# "
   	  ),
   array(
   		"name" => "chanceCode",
   		"sql" => " and c.chanceCode=# "
   	  ),
   array(
   		"name" => "chanceName",
   		"sql" => " and c.chanceName=# "
   	  ),
   array(
   		"name" => "chanceId",
   		"sql" => " and c.chanceId=# "
   	  )
)
?>