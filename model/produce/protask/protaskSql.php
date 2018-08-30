<?php
/**
 * @author Administrator
 * @Date 2011年6月2日 10:30:25
 * @version 1.0
 * @description:生产任务书 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.taskCode ,c.relDocType ,c.relDocId ,c.relDocCode ,c.relDocName ,c.issuedDeptId ,c.issuedDeptName ,c.execDeptId ,c.execDeptName ,c.customerId ,c.customerName ,c.referDate ,c.issuedDate ,c.proStatus ,c.issuedStatus ,c.qualityType ,c.taskType ,c.issuedmanId ,c.issuedman ,c.remark ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime ,c.rObjCode  from oa_produce_protask c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "taskCode",
   		"sql" => " and c.taskCode like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "relDocType",
   		"sql" => " and c.relDocType=# "
   	  ),
   array(
   		"name" => "relDocId",
   		"sql" => " and c.relDocId=# "
   	  ),
   array(
   		"name" => "relDoceCode",
   		"sql" => " and c.relDocCode like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "relDocName",
   		"sql" => " and c.relDocName like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "issuedDeptId",
   		"sql" => " and c.issuedDeptId=# "
   	  ),
   array(
   		"name" => "issuedDeptName",
   		"sql" => " and c.issuedDeptName=# "
   	  ),
   array(
   		"name" => "execDeptId",
   		"sql" => " and c.execDeptId=# "
   	  ),
   array(
   		"name" => "execDeptName",
   		"sql" => " and c.execDeptName=# "
   	  ),
   array(
   		"name" => "customerId",
   		"sql" => " and c.customerId=# "
   	  ),
   array(
   		"name" => "customerName",
   		"sql" => " and c.customerName=# "
   	  ),
   array(
   		"name" => "referDate",
   		"sql" => " and c.referDate=# "
   	  ),
   array(
   		"name" => "proStatus",
   		"sql" => " and c.proStatus=# "
   	  ),
   array(
   		"name" => "issuedStatus",
   		"sql" => " and c.issuedStatus=# "
   	  ),
   array(
   		"name" => "qualityType",
   		"sql" => " and c.qualityType=# "
   	  ),
   array(
   		"name" => "taskType",
   		"sql" => " and c.taskType=# "
   	  ),
   array(
   		"name" => "issuedmanId",
   		"sql" => " and c.issuedmanId=# "
   	  ),
   array(
   		"name" => "issuedman",
   		"sql" => " and c.issuedman=# "
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
   	  ),
   array(
   		"name" => "rObjCode",
   		"sql" => " and c.rObjCode like CONCAT('%',#,'%') "
   	  )
)
?>