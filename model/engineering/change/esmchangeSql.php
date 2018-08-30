<?php
/**
 * @author Show
 * @Date 2011年12月3日 星期六 14:17:32
 * @version 1.0
 * @description:项目变更申请单(oa_esm_change_baseinfo) sql配置文件
 */
$sql_arr = array (
    "select_default"=>"select c.id ,c.projectId ,c.projectCode ,c.projectName ,c.applyName ,c.applyId ,c.applyDate ,
    		c.orgBudgetAll ,c.orgBudgetField ,c.orgBudgetOther ,c.newBudgetAll ,c.newBudgetField ,c.newBudgetOther ,c.actEndDate,
    		c.salesmanId ,c.salesman ,c.changeDescription ,c.ExaStatus ,c.ExaDT ,c.createId ,c.createName ,c.createTime ,c.updateId ,
    		c.updateName ,c.updateTime,c.orgBudgetOutsourcing,c.newBudgetOutsourcing,c.officeId,c.officeName,
    		c.orgBudgetPerson,c.orgBudgetPeople,c.orgBudgetDay,c.orgBudgetEqu,
    		c.newBudgetPerson,c.newBudgetPeople,c.newBudgetDay,c.newBudgetEqu,p.contractType,c.orgPlanEndDate,c.newPlanEndDate
    	from oa_esm_change_baseinfo c left join oa_esm_project p on c.projectId = p.id where 1=1 "
);

$condition_arr = array (
	array(
       "name" => "id",
   		"sql" => " and c.Id=# "
    ),
    array(
   		"name" => "projectId",
   		"sql" => " and c.projectId=# "
    ),
    array(
   		"name" => "projectCode",
   		"sql" => " and c.projectCode  like CONCAT('%',#,'%') "
    ),
    array(
   		"name" => "projectName",
   		"sql" => " and c.projectName  like CONCAT('%',#,'%') "
    ),
    array(
   		"name" => "applyName",
   		"sql" => " and c.applyName  like CONCAT('%',#,'%') "
    ),
    array(
   		"name" => "applyId",
   		"sql" => " and c.applyId=# "
    ),
    array(
   		"name" => "applyDate",
   		"sql" => " and c.applyDate=# "
    ),
    array(
   		"name" => "orgBudgetAll",
   		"sql" => " and c.orgBudgetAll=# "
    ),
    array(
   		"name" => "orgBudgetField",
   		"sql" => " and c.orgBudgetField=# "
    ),
    array(
   		"name" => "orgBudgetOther",
   		"sql" => " and c.orgBudgetOther=# "
    ),
    array(
   		"name" => "newBudgetAll",
   		"sql" => " and c.newBudgetAll=# "
    ),
    array(
   		"name" => "newBudgetField",
   		"sql" => " and c.newBudgetField=# "
    ),
    array(
   		"name" => "newBudgetOther",
   		"sql" => " and c.newBudgetOther=# "
    ),
    array(
   		"name" => "changeDescription",
   		"sql" => " and c.changeDescription=# "
    ),
    array(
   		"name" => "ExaStatus",
   		"sql" => " and c.ExaStatus=# "
    ),
    array(
   		"name" => "ExaStatusNo",
   		"sql" => " and c.ExaStatus <> # "
    ),
    array(
   		"name" => "ExaStatusIn",
   		"sql" => " and c.ExaStatus in(arr) "
    ),
    array(
   		"name" => "ExaDT",
   		"sql" => " and c.ExaDT=# "
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
   		"name" => "updateId",
   		"sql" => " and c.updateId=# "
    )
);