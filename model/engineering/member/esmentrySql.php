<?php
/**
 * @author tse
 * @Date 2014��3��3�� 15:48:16
 * @version 1.0
 * @description:��Ա����� sql�����ļ� 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.memberName ,c.memberId ,c.personLevel ,c.beginDate ,c.endDate ,c.remark,c.projectId  from oa_esm_project_entry c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "memberName",
   		"sql" => " and c.memberName=# "
   	  ),
   array(
   		"name" => "memberId",
   		"sql" => " and c.memberId=# "
   	  ),
   array(
   		"name" => "personLevel",
   		"sql" => " and c.personLevel=# "
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
   		"name" => "remark",
   		"sql" => " and c.remark=# "
   	  ),
   array(
   		"name" => "projectId",
   		"sql" => " and c.projectId=# "
   	  )
)
?>