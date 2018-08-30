<?php
/**
 * @author Administrator
 * @Date 2013年12月15日 星期日 22:23:01
 * @version 1.0
 * @description:外包结算人员租赁 sql配置文件 
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.mainId ,c.personLevel ,c.personLevelName ,c.pesonName ,c.userAccount ,c.userNo ,c.suppName ,c.suppId ,c.beginDate ,c.endDate ,c.totalDay ,c.inBudgetPrice ,c.selfPrice ,c.outBudgetPrice ,c.rentalPrice ,c.trafficMoney ,c.otherMoney ,c.customerDeduct ,c.examinDuduct ,c.skillsRequired ,c.interviewResults ,c.interviewName ,c.interviewId ,c.remark ,c.changeTips ,c.isTemp ,c.originalId ,c.isDel  from oa_outsourcing_account_personrental c where 1=1 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "mainId",
   		"sql" => " and c.mainId=# "
   	  ),
   array(
   		"name" => "personLevel",
   		"sql" => " and c.personLevel=# "
   	  ),
   array(
   		"name" => "personLevelName",
   		"sql" => " and c.personLevelName=# "
   	  ),
   array(
   		"name" => "pesonName",
   		"sql" => " and c.pesonName=# "
   	  ),
   array(
   		"name" => "userAccount",
   		"sql" => " and c.userAccount=# "
   	  ),
   array(
   		"name" => "userNo",
   		"sql" => " and c.userNo=# "
   	  ),
   array(
   		"name" => "suppName",
   		"sql" => " and c.suppName=# "
   	  ),
   array(
   		"name" => "suppId",
   		"sql" => " and c.suppId=# "
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
   		"name" => "totalDay",
   		"sql" => " and c.totalDay=# "
   	  ),
   array(
   		"name" => "inBudgetPrice",
   		"sql" => " and c.inBudgetPrice=# "
   	  ),
   array(
   		"name" => "selfPrice",
   		"sql" => " and c.selfPrice=# "
   	  ),
   array(
   		"name" => "outBudgetPrice",
   		"sql" => " and c.outBudgetPrice=# "
   	  ),
   array(
   		"name" => "rentalPrice",
   		"sql" => " and c.rentalPrice=# "
   	  ),
   array(
   		"name" => "trafficMoney",
   		"sql" => " and c.trafficMoney=# "
   	  ),
   array(
   		"name" => "otherMoney",
   		"sql" => " and c.otherMoney=# "
   	  ),
   array(
   		"name" => "customerDeduct",
   		"sql" => " and c.customerDeduct=# "
   	  ),
   array(
   		"name" => "examinDuduct",
   		"sql" => " and c.examinDuduct=# "
   	  ),
   array(
   		"name" => "skillsRequired",
   		"sql" => " and c.skillsRequired=# "
   	  ),
   array(
   		"name" => "interviewResults",
   		"sql" => " and c.interviewResults=# "
   	  ),
   array(
   		"name" => "interviewName",
   		"sql" => " and c.interviewName=# "
   	  ),
   array(
   		"name" => "interviewId",
   		"sql" => " and c.interviewId=# "
   	  ),
   array(
   		"name" => "remark",
   		"sql" => " and c.remark=# "
   	  ),
   array(
   		"name" => "changeTips",
   		"sql" => " and c.changeTips=# "
   	  ),
   array(
   		"name" => "isTemp",
   		"sql" => " and c.isTemp=# "
   	  ),
   array(
   		"name" => "originalId",
   		"sql" => " and c.originalId=# "
   	  ),
   array(
   		"name" => "isDel",
   		"sql" => " and c.isDel=# "
   	  )
)
?>