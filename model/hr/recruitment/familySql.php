<?php
/**
 * @author Administrator
 * @Date 2012-07-19 11:15:45
 * @version 1.0
 * @description:职位申请表-家庭成员 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.employmentId ,c.name ,c.age ,c.relation ,c.work ,c.post ,c.information,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime  from oa_hr_employment_family c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "employmentId",
   		"sql" => " and c.employmentId=# "
   	  ),
   array(
   		"name" => "name",
   		"sql" => " and c.name=# "
   	  ),
   array(
   		"name" => "age",
   		"sql" => " and c.age=# "
   	  ),
   array(
   		"name" => "relation",
   		"sql" => " and c.relation=# "
   	  ),
   array(
   		"name" => "work",
   		"sql" => " and c.work=# "
   	  ),
   array(
   		"name" => "post",
   		"sql" => " and c.post=# "
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