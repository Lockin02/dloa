<?php
/**
 * @author Administrator
 * @Date 2013年10月24日 星期四 10:06:46
 * @version 1.0
 * @description:供应商技能领域 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.skillarea ,c.remark ,c.createTime ,c.createName ,c.createId ,c.updateTime ,c.updateName ,c.updateId  from oa_outsourcesupp_skillarea c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "skillarea",
   		"sql" => " and c.skillarea like concat('%',#,'%') "
   	  ),
   array(
   		"name" => "remark",
   		"sql" => " and c.remark=# "
   	  ),
   array(
   		"name" => "createTime",
   		"sql" => " and c.createTime=# "
   	  ),
   array(
   		"name" => "createName",
   		"sql" => " and c.createName=# "
   	  ),
   array(
   		"name" => "createId",
   		"sql" => " and c.createId=# "
   	  ),
   array(
   		"name" => "updateTime",
   		"sql" => " and c.updateTime=# "
   	  ),
   array(
   		"name" => "updateName",
   		"sql" => " and c.updateName=# "
   	  ),
   array(
   		"name" => "updateId",
   		"sql" => " and c.updateId=# "
   	  )
)
?>