<?php
/**
 * @author Show
 * @Date 2012年5月29日 星期二 9:24:35
 * @version 1.0
 * @description:培训课程表 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.courseName ,c.courseType ,c.courseTypeName ,c.agency ,c.teacherName ,c.teacherId ,c.courseDate ,c.address ,c.lessons ,c.fee ,c.outline ,c.forWho ,c.status ,c.remark ,c.personsListName ,c.personsListAccount ,c.personsListNo ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime ,c.sysCompanyName ,c.sysCompanyId  from oa_hr_training_course c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "courseNameM",
   		"sql" => " and c.courseName like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "courseName",
   		"sql" => " and c.courseName=# "
   	  ),
   array(
   		"name" => "courseType",
   		"sql" => " and c.courseType=# "
   	  ),
   array(
   		"name" => "courseTypeNameM",
   		"sql" => " and c.courseTypeName like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "courseTypeName",
   		"sql" => " and c.courseTypeName=# "
   	  ),
   array(
   		"name" => "agencyM",
   		"sql" => " and c.agency like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "agency",
   		"sql" => " and c.agency=# "
   	  ),
   array(
   		"name" => "teacherNameM",
   		"sql" => " and c.teacherName like CONCAT('%',#,'%') "
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
   		"name" => "courseDate",
   		"sql" => " and c.courseDate=# "
   	  ),
   array(
   		"name" => "address",
   		"sql" => " and c.address=# "
   	  ),
   array(
   		"name" => "lessons",
   		"sql" => " and c.lessons=# "
   	  ),
   array(
   		"name" => "fee",
   		"sql" => " and c.fee=# "
   	  ),
   array(
   		"name" => "outline",
   		"sql" => " and c.outline=# "
   	  ),
   array(
   		"name" => "forWho",
   		"sql" => " and c.forWho=# "
   	  ),
   array(
   		"name" => "status",
   		"sql" => " and c.status=# "
   	  ),
   array(
   		"name" => "remark",
   		"sql" => " and c.remark=# "
   	  ),
   array(
   		"name" => "personsListName",
   		"sql" => " and c.personsListName=# "
   	  ),
   array(
   		"name" => "personsListAccount",
   		"sql" => " and c.personsListAccount=# "
   	  ),
   array(
   		"name" => "personsListNo",
   		"sql" => " and c.personsListNo=# "
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
   	  )
)
?>