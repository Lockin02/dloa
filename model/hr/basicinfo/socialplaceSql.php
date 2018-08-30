<?php
/**
 * @author Administrator
 * @Date 2012年8月11日 星期六 10:27:17
 * @version 1.0
 * @description:社保购买地 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.socialProId ,c.socialPro ,c.socialCityId ,c.socialCity ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime  from oa_hr_socialsecurity_place c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "socialProId",
   		"sql" => " and c.socialProId=# "
   	  ),
   array(
   		"name" => "socialPro",
   		"sql" => " and c.socialPro=# "
   	  ),
   array(
   		"name" => "socialCityId",
   		"sql" => " and c.socialCityId=# "
   	  ),
   array(
   		"name" => "socialCity",
   		"sql" => " and c.socialCity like concat('%',#,'%')"
   	  ),
   array(
   		"name" => "socialCityEq",
   		"sql" => " and c.socialCity=#"
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
   		"name" => "createTime",
   		"sql" => " and c.createTime=# "
   	  ),
   array(
   		"name" => "updateName",
   		"sql" => " and c.updateName=# "
   	  ),
   array(
   		"name" => "updateId",
   		"sql" => " and c.updateId=# "
   	  ),
   array(
   		"name" => "updateTime",
   		"sql" => " and c.updateTime=# "
   	  )
)
?>