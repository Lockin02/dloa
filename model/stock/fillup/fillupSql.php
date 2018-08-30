<?php
/**
 * @author huangzf
 * @Date 2011年1月17日 11:51:07
 * @version 1.0
 * @description:补库计划 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.fillupCode ,c.auditStatus,c.stockId ,c.stockName ,c.stockCode ,c.remark ,c.purchRemark ,c.ExaStatus ,c.ExaDT ,c.updateId ,c.updateName ,c.createTime ,c.createName ,c.createId ,c.updateTime  " .
         		"from oa_stock_fillup c where 1=1 ",
		/*****************************************工作流部分***********************************/
	"sql_examine" => "select " .
		"w.task as taskId,p.ID as spid ,c.id ,c.fillupCode ,c.auditStatus,c.stockId ,c.stockName ,c.stockCode ,c.remark ,c.ExaStatus ,c.ExaDT ,c.updateId ,c.updateName ,c.createTime ,c.createName ,c.createId ,c.updateTime from flow_step_partent p left join wf_task w on p.wf_task_id = w.task left join user u on u.USER_ID = p.User ,oa_stock_fillup c " .
		" where w.Pid =c.id and w.examines <> 'no' "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "auditStatusc",
   		"sql" => " and c.auditStatusc=# "
   	  ),
   array(
   		"name" => "fillupCode",
   		"sql" => " and c.fillupCode =# "
   	  ),
   	array(
   		"name" => "fillupCodeX",
   		"sql" => " and c.fillupCode like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "stockId",
   		"sql" => " and c.stockId=# "
   	  ),
   array(
   		"name" => "stockName",
   		"sql" => " and c.stockName like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "stockCode",
   		"sql" => " and c.stockCode=# "
   	  ),
   array(
   		"name" => "remark",
   		"sql" => " and c.remark=# "
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
   		"name" => "updateId",
   		"sql" => " and c.updateId=# "
   	  ),
   array(
   		"name" => "updateName",
   		"sql" => " and c.updateName=# "
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
   		"name" => "updateTime",
   		"sql" => " and c.updateTime=# "
   	  ),
 	  	//审核工作流
	array(
		"name" => "findInName",//审批人ID
		"sql"=>" and  ( find_in_set( # , p.User ) > 0 ) "
	),
	array(
		"name" => "workFlowCode",//业务表
		"sql"=>" and w.code =# "
	),
	array(
		"name" => "Flag",//业务表
		"sql"=>" and p.Flag= # "
	),
	array(
		"name" => "taskId",
		"sql" => " and taskId = #"
	),
	array(
		"name" => "spid",
		"sql" => "and spid=#"
	)
)
?>