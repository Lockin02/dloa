<?php
/**
 * @author Administrator
 * @Date 2013年9月24日 星期二 16:05:37
 * @version 1.0
 * @description:工作量确认单 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.formCode ,c.formDate ,c.beginDate ,c.endDate ,c.projectId ,c.projectCode ,c.projecttName ,c.remark ,c.status ,c.statusName ,c.closeDate ,c.closeDesc ,c.ExaStatus ,c.ExaDT ,c.approveId ,c.approveName ,c.approveTime ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime  from oa_outsourcing_workverify c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "formCode",
   		"sql" => " and c.formCode like CONCAT('%',#,'%')"
   	  ),
   array(
   		"name" => "formDate",
   		"sql" => " and c.formDate=# "
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
   		"name" => "endDateCheck",
   		"sql" => " and date_format(c.endDate,'%Y-%m')=# "
   	  ),
   array(
   		"name" => "projectId",
   		"sql" => " and c.projectId=# "
   	  ),
   array(
   		"name" => "projectCode",
   		"sql" => " and c.projectCode=# "
   	  ),
   array(
   		"name" => "projecttName",
   		"sql" => " and c.projecttName=# "
   	  ),
   array(
   		"name" => "remark",
   		"sql" => " and c.remark=# "
   	  ),
   array(
   		"name" => "status",
   		"sql" => " and c.status=# "
   	  ),
   array(
   		"name" => "statusArr",
   		"sql" => " and c.status in(arr) "
   	  ),
   array(
   		"name" => "statusName",
   		"sql" => " and c.statusName=# "
   	  ),
   array(
   		"name" => "closeDate",
   		"sql" => " and c.closeDate=# "
   	  ),
   array(
   		"name" => "closeDesc",
   		"sql" => " and c.closeDesc=# "
   	  ),
   array(
   		"name" => "ExaStatus",
   		"sql" => " and c.ExaStatus=# "
   	  ),
   array(
   		"name" => "ExaDT",
   		"sql" => " and c.ExaDT=# "
   	  ),
   array(
   		"name" => "approveId",
   		"sql" => " and c.approveId=# "
   	  ),
   array(
   		"name" => "approveName",
   		"sql" => " and c.approveName=# "
   	  ),
   array(
   		"name" => "approveTime",
   		"sql" => " and c.approveTime=# "
   	  ),
   array(
   		"name" => "createId",
   		"sql" => " and c.createId=# "
   	  ),
   array(
   		"name" => "createName",
   		"sql" => " and c.createName like CONCAT('%',#,'%') "
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