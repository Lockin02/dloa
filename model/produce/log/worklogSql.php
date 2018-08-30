<?php
/**
 * @author Administrator
 * @Date 2012年5月16日 星期三 14:12:39
 * @version 1.0
 * @description:工作日志 sql配置文件 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.produceTaskId ,c.produceTaskCode ,c.weekId ,c.executionDate ,c.effortRate ,c.warpRate ,c.workloadDay ,c.workloadSurplus ,c.planEndDate ,c.description ,c.problem ,c.isAttention ,c.createName ,c.createId ,c.createTime ,c.updateName ,c.updateId ,c.updateTime  from oa_produce_worklog c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "produceTaskId",
   		"sql" => " and c.produceTaskId=# "
   	  ),
   array(
   		"name" => "produceTaskCode",
   		"sql" => " and c.produceTaskCode=# "
   	  ),
   array(
   		"name" => "weekId",
   		"sql" => " and c.weekId=# "
   	  ),
   array(
   		"name" => "executionDate",
   		"sql" => " and c.executionDate=# "
   	  ),
   array(
   		"name" => "effortRate",
   		"sql" => " and c.effortRate=# "
   	  ),
   array(
   		"name" => "warpRate",
   		"sql" => " and c.warpRate=# "
   	  ),
   array(
   		"name" => "workloadDay",
   		"sql" => " and c.workloadDay=# "
   	  ),
   array(
   		"name" => "workloadSurplus",
   		"sql" => " and c.workloadSurplus=# "
   	  ),
   array(
   		"name" => "planEndDate",
   		"sql" => " and c.planEndDate=# "
   	  ),
   array(
   		"name" => "description",
   		"sql" => " and c.description=# "
   	  ),
   array(
   		"name" => "problem",
   		"sql" => " and c.problem=# "
   	  ),
   array(
   		"name" => "isAttention",
   		"sql" => " and c.isAttention=# "
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