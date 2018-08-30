<?php
/**
 * @author Administrator
 * @Date 2012年7月19日 星期四 16:20:22
 * @version 1.0
 * @description:面试评语 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.invitationId ,c.invitationCode ,c.interviewType ,c.parentId ,c.parentCode ,c.resumeId ,c.resumeCode ,c.applicantName ,c.userAccount ,c.userName ,c.sexy ,c.positionsName ,c.positionsId ,c.deptName ,c.deptId ,c.projectGroup ,c.useWriteEva ,c.interviewEva ,c.interviewer ,c.interviewerId ,c.interviewDate ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime,c.interviewId,c.interviewerType  from oa_hr_interview_comment c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.id=# "
        ),
   array(
   		"name" => "invitationId",
   		"sql" => " and c.invitationId=# "
   	  ),
   array(
   		"name" => "interviewId",
   		"sql" => " and c.interviewId=# "
   	  ),
   array(
   		"name" => "invitationCode",
   		"sql" => " and c.invitationCode=# "
   	  ),
   array(
   		"name" => "interviewType",
   		"sql" => " and c.interviewType=# "
   	  ),
   array(
   		"name" => "parentId",
   		"sql" => " and c.parentId=# "
   	  ),
   array(
   		"name" => "parentCode",
   		"sql" => " and c.parentCode=# "
   	  ),
   array(
   		"name" => "resumeId",
   		"sql" => " and c.resumeId=# "
   	  ),
   array(
   		"name" => "resumeCode",
   		"sql" => " and c.resumeCode=# "
   	  ),
   array(
   		"name" => "applicantName",
   		"sql" => " and c.applicantName=# "
   	  ),
   array(
   		"name" => "userAccount",
   		"sql" => " and c.userAccount=# "
   	  ),
   array(
   		"name" => "userName",
   		"sql" => " and c.userName like concat('%',#,'%') "
   	  ),
   array(
   		"name" => "sexy",
   		"sql" => " and c.sexy=# "
   	  ),
   array(
   		"name" => "positionsName",
   		"sql" => " and c.positionsName  like concat('%',#,'%') "
   	  ),
   array(
   		"name" => "positionsId",
   		"sql" => " and c.positionsId=# "
   	  ),
   array(
   		"name" => "deptName",
   		"sql" => " and c.deptName like concat('%',#,'%') "
   	  ),
   array(
   		"name" => "deptId",
   		"sql" => " and c.deptId=# "
   	  ),
   array(
   		"name" => "projectGroup",
   		"sql" => " and c.projectGroup=# "
   	  ),
   array(
   		"name" => "useWriteEva",
   		"sql" => " and c.useWriteEva=# "
   	  ),
   array(
   		"name" => "interviewEva",
   		"sql" => " and c.interviewEva=# "
   	  ),
   array(
   		"name" => "interviewer",
   		"sql" => " and c.interviewer like concat('%',#,'%') "
   	  ),
   array(
   		"name" => "interviewerType",
   		"sql" => " and c.interviewerType=# "
   	  ),
   array(
   		"name" => "interviewerId",
   		"sql" => " and c.interviewerId=# "
   	  ),
   array(
   		"name" => "interviewDate",
   		"sql" => " and c.interviewDate=# "
   	  ),
   array(
   		"name" => "createId",
   		"sql" => " and c.createId=# "
   	  ),
   array(
   		"name" => "createName",
   		"sql" => " and c.createName=# "
   	  ),
   array(
   		"name" => "createTime",
   		"sql" => " and c.createTime=# "
   	  ),
   array(
   		"name" => "updateId",
   		"sql" => " and c.updateId=# "
   	  ),
   array(
   		"name" => "updateName",
   		"sql" => " and c.updateName=# "
   	  ),
   array(
   		"name" => "updateTime",
   		"sql" => " and c.updateTime=# "
   	  )
)
?>