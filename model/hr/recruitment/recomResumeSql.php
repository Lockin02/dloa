<?php
/**
 * @author Administrator
 * @Date 2012年7月17日 星期二 14:29:10
 * @version 1.0
 * @description:内部推荐简历库 sql配置文件 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.formCode ,c.parentId ,c.resumeId ,c.resumeCode ,c.applicantName ,c.sex ,c.workSeniority ,c.phone ,c.email ,c.state  from oa_hr_recommend_resume c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "formCode",
   		"sql" => " and c.formCode like concat('%',#,'%') "
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
   		"sql" => " and c.resumeCode like concat('%',#,'%') "
   	  ),
   array(
   		"name" => "applicantName",
   		"sql" => " and c.applicantName like concat('%',#,'%') "
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