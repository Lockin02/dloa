<?php
/**
 * @author Show
 * @Date 2012年8月31日 星期五 14:53:01
 * @version 1.0
 * @description:员工试用培训计划 sql配置文件 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.planName ,c.description ,c.memberName ,c.memberId ,c.status ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime  from oa_hr_trialplan c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "planName",
   		"sql" => " and c.planName=# "
   	  ),
   array(
   		"name" => "description",
   		"sql" => " and c.description=# "
   	  ),
   array(
   		"name" => "memberName",
   		"sql" => " and c.memberName=# "
   	  ),
   array(
   		"name" => "memberId",
   		"sql" => " and c.memberId=# "
   	  ),
   array(
   		"name" => "status",
   		"sql" => " and c.status=# "
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