<?php
/**
 * @author Show
 * @Date 2012年5月30日 星期三 14:02:31
 * @version 1.0
 * @description:培训管理-课程详细记录 sql配置文件
 */
$sql_arr = array (
	"select_default"=>"select c.id ,c.userNo ,c.userAccount ,c.userName ,c.deptName ,c.deptId ,c.jobName ,c.jobId ,c.courseName ,c.courseId ,c.isInner ,c.trainsType ,c.trainsTypeName ,c.beginDate ,c.endDate ,c.agency ,c.teacherName ,c.teacherId ,c.address ,c.fee ,c.assessment ,c.assessmentName ,c.assessmentScore ,c.isUploadTA ,c.isUploadTU ,c.status ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime ,c.duration ,c.courseCode ,c.trainsMethod ,c.trainsMethodCode ,c.orgDeptName ,c.orgDeptId ,c.courseEvaluateScore ,c.trainsOrgEvaluateScore ,c.followTime ,c.trainsNum ,c.trainsMonth from oa_hr_training_course_records c where 1=1 "
);

$condition_arr = array (
	array(
		"name" => "id",
		"sql" => " and c.Id=# "
	),
	array(
		"name" => "userNo",
		"sql" => " and c.userNo=# "
	),
	array(
		"name" => "userNoM",
		"sql" => " and c.userNo LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "userAccount",
		"sql" => " and c.userAccount=# "
	),
	array(
		"name" => "userNameM",
		"sql" => " and c.userName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "userName",
		"sql" => " and c.userName=# "
	),
	array(
		"name" => "deptNameM",
		"sql" => " and c.deptName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "deptName",
		"sql" => " and c.deptName=# "
	),
	array(
		"name" => "deptId",
		"sql" => " and c.deptId=# "
	),
	array(
		"name" => "jobName",
		"sql" => " and c.jobName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "jobId",
		"sql" => " and c.jobId=# "
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
		"name" => "isInner",
		"sql" => " and c.isInner=# "
	),
	array(
		"name" => "trainsType",
		"sql" => " and c.trainsType=# "
	),
	array(
		"name" => "trainsTypeName",
		"sql" => " and c.trainsTypeName=# "
	),
	array(
		"name" => "beginDate",
		"sql" => " and c.beginDate=# "
	),
	array(
		"name" => "endDate",
		"sql" => " and c.endDate=# "
	),
	array(
		"name" => "agencyM",
		"sql" => " and c.agency LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "agency",
		"sql" => " and c.agency=# "
	),
	array(
		"name" => "teacherName",
		"sql" => " and c.teacherName=# "
	),
	array(
		"name" => "teacherNameM",
		"sql" => " and c.teacherName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "teacherId",
		"sql" => " and c.teacherId=# "
	),
	array(
		"name" => "address",
		"sql" => " and c.address LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "fee",
		"sql" => " and c.fee=# "
	),
	array(
		"name" => "assessment",
		"sql" => " and c.assessment=# "
	),
	array(
		"name" => "assessmentName",
		"sql" => " and c.assessmentName=# "
	),
	array(
		"name" => "assessmentScore",
		"sql" => " and c.assessmentScore=# "
	),
	array(
		"name" => "isUploadTA",
		"sql" => " and c.isUploadTA=# "
	),
	array(
		"name" => "isUploadTU",
		"sql" => " and c.isUploadTU=# "
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
	)
)
?>