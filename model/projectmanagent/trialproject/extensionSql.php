<?php
/**
 * @author Administrator
 * @Date 2012-06-18 16:25:39
 * @version 1.0
 * @description:ясфзиЙгК sqlеДжцнд╪Ч
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.endDateOld,c.affirmMoneyOld,c.trialprojectId ,c.trialprojectCode ,c.extensionDate ,c.extensionReason,c.affirmMoney,c.updateTime ,c.updateName ,c.updateId ,c.createTime ,c.createName ,c.createId ,c.ExaStatus ,c.ExaDT,c.budgetMoney,c.costReason,c.extensionTime,c.newProjectDays  from oa_trialproject_extension c where 1=1 ",
		 "select_extension"=>"select c.id ,c.endDateOld,c.affirmMoneyOld,c.trialprojectId ,c.trialprojectCode ,c.extensionDate ,c.extensionReason,c.affirmMoney,c.updateTime ,c.updateName ,c.updateId ,c.createTime ,c.createName ,c.createId ,c.ExaStatus ,c.ExaDT,c.budgetMoney,c.costReason,c.extensionTime,c.newProjectDays  from oa_trialproject_extension c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "trialprojectId",
   		"sql" => " and c.trialprojectId=# "
   	  ),
   array(
   		"name" => "trialprojectCode",
   		"sql" => " and c.trialprojectCode=# "
   	  ),
   array(
   		"name" => "extensionDate",
   		"sql" => " and c.extensionDate=# "
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
   		"name" => "ExaStatus",
   		"sql" => " and c.ExaStatus=# "
   	  ),
   array(
   		"name" => "ExaDT",
   		"sql" => " and c.ExaDT=# "
   	  ),
   array(
   		"name" => "budgetMoney",
   		"sql" => " and c.budgetMoney=# "
   	  )
)
?>