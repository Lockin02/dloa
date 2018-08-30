<?php
/**
 * @author Show
 * @Date 2012年12月15日 星期六 15:21:07
 * @version 1.0
 * @description:项目范围变更表 sql配置文件 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.changeId ,c.orgId ,c.activityId ,c.activityName ,c.projectId ,c.projectCode ,c.projectName ,c.planBeginDate ,c.planEndDate ,c.days ,c.workRate ,c.workload ,c.workloadUnit ,c.workloadUnitName ,c.budgetAll ,c.workContent ,c.remark ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime ,c.isChange  from oa_esm_change_activity c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "changeId",
   		"sql" => " and c.changeId=# "
   	  ),
   array(
   		"name" => "orgId",
   		"sql" => " and c.orgId=# "
   	  ),
   array(
   		"name" => "activityId",
   		"sql" => " and c.activityId=# "
   	  ),
   array(
   		"name" => "activityName",
   		"sql" => " and c.activityName=# "
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
   		"name" => "projectName",
   		"sql" => " and c.projectName=# "
   	  ),
   array(
   		"name" => "planBeginDate",
   		"sql" => " and c.planBeginDate=# "
   	  ),
   array(
   		"name" => "planEndDate",
   		"sql" => " and c.planEndDate=# "
   	  ),
   array(
   		"name" => "days",
   		"sql" => " and c.days=# "
   	  ),
   array(
   		"name" => "workRate",
   		"sql" => " and c.workRate=# "
   	  ),
   array(
   		"name" => "workload",
   		"sql" => " and c.workload=# "
   	  ),
   array(
   		"name" => "workloadUnit",
   		"sql" => " and c.workloadUnit=# "
   	  ),
   array(
   		"name" => "workloadUnitName",
   		"sql" => " and c.workloadUnitName=# "
   	  ),
   array(
   		"name" => "budgetAll",
   		"sql" => " and c.budgetAll=# "
   	  ),
   array(
   		"name" => "workContent",
   		"sql" => " and c.workContent=# "
   	  ),
   array(
   		"name" => "remark",
   		"sql" => " and c.remark=# "
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
   	  ),
   array(
   		"name" => "isChange",
   		"sql" => " and c.isChange=# "
   	  )
)
?>