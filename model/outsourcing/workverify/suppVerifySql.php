<?php
/**
 * @author Admin
 * @Date 2014年1月16日 13:36:19
 * @version 1.0
 * @description:外包供应商工作量确认单 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.parentCode ,c.parentId ,c.formCode ,c.formDate ,c.beginDate ,c.endDate ,c.outsourceSuppId ,c.outsourceSuppCode ,c.outsourceSupp ,c.projectId ,c.projectCode ,c.projecttName ,c.remark ,c.status ,c.statusName ,c.closeDate ,c.closeDesc ,c.ExaStatus ,c.ExaDT ,c.approveId ,c.approveName ,c.approveTime ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime,c.workMonth  from oa_outsourcing_suppverify c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "parentCode",
   		"sql" => " and c.parentCode=# "
   	  ),
   array(
   		"name" => "parentId",
   		"sql" => " and c.parentId=# "
   	  ),
   array(
   		"name" => "formCode",
   		"sql" => " and c.formCode=# "
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
   		"name" => "outsourceSuppId",
   		"sql" => " and c.outsourceSuppId=# "
   	  ),
   array(
   		"name" => "outsourceSuppCode",
   		"sql" => " and c.outsourceSuppCode=# "
   	  ),
   array(
   		"name" => "outsourceSupp",
   		"sql" => " and c.outsourceSupp=# "
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
   		"name" => "statusNeq",
   		"sql" => " and c.status!=# "
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