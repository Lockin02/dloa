<?php
/**
 * @author Administrator
 * @Date 2012年7月17日 星期二 10:43:19
 * @version 1.0
 * @description:增员申请简历库 sql配置文件 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.formCode ,c.parentId ,c.resumeId ,c.resumeCode ,c.applicantName ,c.sex ,c.workSeniority ,c.phone ,c.email ,c.state  from oa_hr_apply_resume c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "formCode",
   		"sql" => " and c.formCode=# "
   	  ),
   array(
   		"name" => "parentId",
   		"sql" => " and c.parentId=# "
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
   		"name" => "sex",
   		"sql" => " and c.sex=# "
   	  ),
   array(
   		"name" => "workSeniority",
   		"sql" => " and c.workSeniority=# "
   	  ),
   array(
   		"name" => "phone",
   		"sql" => " and c.phone=# "
   	  ),
   array(
   		"name" => "email",
   		"sql" => " and c.email=# "
   	  ),
   array(
   		"name" => "state",
   		"sql" => " and c.state=# "
   	  )
)
?>