<?php
/**
 * @author Administrator
 * @Date 2012-07-19 11:15:13
 * @version 1.0
 * @description:职位申请表-教育经历 sql配置文件 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.employmentId ,c.userNo ,c.userAccount ,c.userName ,c.organization ,c.content ,c.education ,c.educationName ,c.certificate ,c.beginDate ,c.closeDate ,c.remark ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime  from oa_hr_employment_education c where 1=1 "
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
   		"name" => "organization",
   		"sql" => " and c.organization=# "
   	  ),
   array(
   		"name" => "content",
   		"sql" => " and c.content=# "
   	  ),
   array(
   		"name" => "education",
   		"sql" => " and c.education=# "
   	  ),
   array(
   		"name" => "educationName",
   		"sql" => " and c.educationName=# "
   	  ),
   array(
   		"name" => "certificate",
   		"sql" => " and c.certificate=# "
   	  ),
   array(
   		"name" => "beginDate",
   		"sql" => " and c.beginDate=# "
   	  ),
   array(
   		"name" => "closeDate",
   		"sql" => " and c.closeDate=# "
   	  ),
   array(
   		"name" => "remark",
   		"sql" => " and c.remark=# "
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