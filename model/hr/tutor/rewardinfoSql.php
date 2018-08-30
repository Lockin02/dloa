<?php
/**
 * @author Administrator
 * @Date 2012-08-29 17:09:05
 * @version 1.0
 * @description:导师奖励管理--明细 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.rewardId ,c.userNo ,c.userAccount ,c.userName,c.tutorDeptId,c.tutorDeptName ,c.assessmentScore ,c.tutorId ,c.studentNo ,c.studentAccount ,c.studentName ,c.tryEndDate ,c.rewardPrice ,c.situation,c.isGrant,c.scheduleRemark  from oa_hr_tutor_rewardinfo c where 1=1 ",
         "select_simple"=>"select c.id,r.isPublish from oa_hr_tutor_rewardinfo c left join oa_hr_tutor_reward r on r.id=c.rewardId where 1=1"
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "rewardId",
   		"sql" => " and c.rewardId=# "
   	  ),
   array(
   		"name" => "userNo",
   		"sql" => " and c.userNo=# "
   	  ),
   array(
   		"name" => "userAccount",
   		"sql" => " and c.userAccount=# "
   	  ),
   array(
   		"name" => "userName",
   		"sql" => " and c.userName=# "
   	  ),
   array(
   		"name" => "tutorDeptId",
   		"sql" => " and c.tutorDeptId=# "
   	  ),
   array(
   		"name" => "assessmentScore",
   		"sql" => " and c.assessmentScore=# "
   	  ),
   array(
   		"name" => "tutorId",
   		"sql" => " and c.tutorId=# "
   	  ),
   array(
   		"name" => "studentNo",
   		"sql" => " and c.studentNo=# "
   	  ),
   array(
   		"name" => "studentAccount",
   		"sql" => " and c.studentAccount=# "
   	  ),
   array(
   		"name" => "studentName",
   		"sql" => " and c.studentName=# "
   	  ),
   array(
   		"name" => "tryEndDate",
   		"sql" => " and c.tryEndDate=# "
   	  ),
   array(
   		"name" => "rewardPrice",
   		"sql" => " and c.rewardPrice=# "
   	  ),
   array(
   		"name" => "situation",
   		"sql" => " and c.situation=# "
   	  )
)
?>