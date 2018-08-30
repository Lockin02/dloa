<?php
/**
 * @author Administrator
 * @Date 2013年10月28日 星期一 19:56:25
 * @version 1.0
 * @description:等级变更记录 sql配置文件 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.suppCode ,c.suppId ,c.suppGrade ,c.remark ,c.suppGradeOld ,c.updateTime ,c.updateName ,c.updateId ,c.createTime ,c.createName ,c.createId ,c.approveTime ,c.approveName ,c.approveId ,c.ExaDT ,c.ExaStatus  from oa_outsourcesupp_gradechange c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "suppCode",
   		"sql" => " and c.suppCode=# "
   	  ),
   array(
   		"name" => "suppId",
   		"sql" => " and c.suppId=# "
   	  ),
   array(
   		"name" => "suppGrade",
   		"sql" => " and c.suppGrade=# "
   	  ),
   array(
   		"name" => "remark",
   		"sql" => " and c.remark=# "
   	  ),
   array(
   		"name" => "suppGradeOld",
   		"sql" => " and c.suppGradeOld=# "
   	  ),
   array(
   		"name" => "updateTime",
   		"sql" => " and c.updateTime=# "
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
   		"name" => "createTime",
   		"sql" => " and c.createTime=# "
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
   		"name" => "approveTime",
   		"sql" => " and c.approveTime=# "
   	  ),
   array(
   		"name" => "approveName",
   		"sql" => " and c.approveName=# "
   	  ),
   array(
   		"name" => "approveId",
   		"sql" => " and c.approveId=# "
   	  ),
   array(
   		"name" => "ExaDT",
   		"sql" => " and c.ExaDT=# "
   	  ),
   array(
   		"name" => "ExaStatus",
   		"sql" => " and c.ExaStatus=# "
   	  )
)
?>