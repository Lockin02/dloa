<?php
/**
 * @author Show
 * @Date 2012年12月15日 星期六 15:21:37
 * @version 1.0
 * @description:项目变更人力预算 sql配置文件 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.changeId ,c.orgId ,c.projectId ,c.projectName ,c.projectCode ,c.activityId ,c.activityName ,c.personLevelId ,c.personLevel ,c.planBeginDate ,c.planEndDate ,c.days ,c.number ,c.price ,c.coefficient ,c.personDays ,c.personCostDays ,c.personCost ,c.remark ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime  from oa_esm_change_person c where 1=1 "
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
   		"name" => "projectId",
   		"sql" => " and c.projectId=# "
   	  ),
   array(
   		"name" => "projectName",
   		"sql" => " and c.projectName=# "
   	  ),
   array(
   		"name" => "projectCode",
   		"sql" => " and c.projectCode=# "
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
   		"name" => "personLevelId",
   		"sql" => " and c.personLevelId=# "
   	  ),
   array(
   		"name" => "personLevel",
   		"sql" => " and c.personLevel=# "
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
   		"name" => "number",
   		"sql" => " and c.number=# "
   	  ),
   array(
   		"name" => "price",
   		"sql" => " and c.price=# "
   	  ),
   array(
   		"name" => "coefficient",
   		"sql" => " and c.coefficient=# "
   	  ),
   array(
   		"name" => "personDays",
   		"sql" => " and c.personDays=# "
   	  ),
   array(
   		"name" => "personCostDays",
   		"sql" => " and c.personCostDays=# "
   	  ),
   array(
   		"name" => "personCost",
   		"sql" => " and c.personCost=# "
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
   	  )
)
?>