<?php
/**
 * @author Administrator
 * @Date 2012-07-19 11:15:08
 * @version 1.0
 * @description:职位申请表-工作经历 sql配置文件 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.employmentId ,c.userNo ,c.userAccount ,c.userName ,c.company ,c.dept ,c.position ,c.treatment ,c.beginDate ,c.closeDate ,c.seniority ,c.isSeniority ,c.responsibilities ,c.leaveReason ,c.prove ,c.remark ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime  from oa_hr_employment_work c where 1=1 "
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
   		"name" => "company",
   		"sql" => " and c.company=# "
   	  ),
   array(
   		"name" => "dept",
   		"sql" => " and c.dept=# "
   	  ),
   array(
   		"name" => "position",
   		"sql" => " and c.position=# "
   	  ),
   array(
   		"name" => "treatment",
   		"sql" => " and c.treatment=# "
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
   		"name" => "seniority",
   		"sql" => " and c.seniority=# "
   	  ),
   array(
   		"name" => "isSeniority",
   		"sql" => " and c.isSeniority=# "
   	  ),
   array(
   		"name" => "responsibilities",
   		"sql" => " and c.responsibilities=# "
   	  ),
   array(
   		"name" => "leaveReason",
   		"sql" => " and c.leaveReason=# "
   	  ),
   array(
   		"name" => "prove",
   		"sql" => " and c.prove=# "
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