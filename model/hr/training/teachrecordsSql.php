<?php
/**
 * @author Show
 * @Date 2012年5月31日 星期四 10:13:30
 * @version 1.0
 * @description:培训管理-授课记录 sql配置文件
 */
$sql_arr = array (
	"select_default"=>"select c.id ,c.teacherName ,c.teacherId ,c.teachDate ,c.teachEndDate,c.courseName ,c.courseId ,c.address ,c.assessmentScore ,c.status ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime ,c.sysCompanyName ,c.sysCompanyId ,c.teachingClass ,c.theParticipationName ,c.subsidiesToTeach ,c.distribution ,c.userNo ,c.courseCode ,c.trainsMethod ,c.trainsMethodCode ,c.orgDeptName ,c.orgDeptId ,c.trainsNum ,c.courseEvaluateScore ,c.trainsOrgEvaluateScore ,c.followTime ,c.duration ,c.trainsType ,c.trainsTypeName ,c.agency ,c.joinNum ,c.assessment ,c.assessmentName ,c.trainsMonth ,c.fee from oa_hr_training_teachrecords c where 1=1 "
);

$condition_arr = array (
	array(
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array(
		"name" => "teacherNameM",
		"sql" => " and c.teacherName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "teacherName",
		"sql" => " and c.teacherName=# "
	),
	array(
		"name" => "teacherId",
		"sql" => " and c.teacherId=# "
	),
	array(
		"name" => "teachDate",
		"sql" => " and c.teachDate LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "teachEndDate",
		"sql" => " and c.teachEndDate LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "courseNameM",
		"sql" => " and c.courseName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "courseName",
		"sql" => " and c.courseName=# "
	),
	array(
		"name" => "courseId",
		"sql" => " and c.courseId=# "
	),
	array(
		"name" => "address",
		"sql" => " and c.address LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "assessmentScore",
		"sql" => " and c.assessmentScore=# "
	),
	array(
		"name" => "status",
		"sql" => " and c.status=# "
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
	),
	array(
		"name" => "sysCompanyName",
		"sql" => " and c.sysCompanyName=# "
	),
	array(
		"name" => "sysCompanyId",
		"sql" => " and c.sysCompanyId=# "
	),
	array(
		"name" => "userNo",
		"sql" => " and c.userNo LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "teachingClass",
		"sql" => " and c.teachingClass LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "theParticipationName",
		"sql" => " and c.theParticipationName LIKE CONCAT('%',#,'%') "
	)
)
?>