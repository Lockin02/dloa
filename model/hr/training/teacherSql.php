<?php
/**
 * @author Show
 * @Date 2012年5月30日 星期三 9:56:29
 * @version 1.0
 * @description:培训管理-讲师管理 sql配置文件
 */
$sql_arr = array (
	"select_default"=>"select c.id ,c.teacherName ,c.teacherAccount ,c.isInner ,c.certifyDate ,c.levelIdName ,c.levelId ,c.scores ,c.courses ,c.phone ,c.mobile ,c.address ,c.remark ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime ,c.teacherNum ,c.trainingAgency ,c.belongDeptName ,c.belongDeptId c,lecturerPost ,c.lecturerPostId ,c.lecturerCategory  from oa_hr_training_teacher c where 1=1 "
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
		"name" => "teacherAccount",
		"sql" => " and c.teacherAccount=# "
	),
	array(
		"name" => "isInner",
		"sql" => " and c.isInner=# "
	),
	array(
		"name" => "certifyDate",
		"sql" => " and c.certifyDate LIKE BINARY CONCAT('%',#,'%') "
	),
	array(
		"name" => "levelName",
		"sql" => " and c.levelName=# "
	),
	array(
		"name" => "levelId",
		"sql" => " and c.levelId=# "
	),
	array(
		"name" => "scores",
		"sql" => " and c.scores=# "
	),
	array(
		"name" => "courses",
		"sql" => " and c.courses LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "phone",
		"sql" => " and c.phone=# "
	),
	array(
		"name" => "mobile",
		"sql" => " and c.mobile=# "
	),
	array(
		"name" => "address",
		"sql" => " and c.address=# "
	),
	array(
		"name" => "remark",
		"sql" => " and c.remark=# "
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
		"name" => "lecturerCategory",
		"sql" => " and c.lecturerCategory LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "teacherNum",
		"sql" => " and c.teacherNum LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "belongDeptName",
		"sql" => " and c.belongDeptName LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "trainingAgency",
		"sql" => " and c.trainingAgency LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "lecturerPost",
		"sql" => " and c.lecturerPost LIKE CONCAT('%',#,'%') "
	),
	array(
		"name" => "levelIdName",
		"sql" => " and c.levelIdName LIKE CONCAT('%',#,'%') "
	)
)
?>