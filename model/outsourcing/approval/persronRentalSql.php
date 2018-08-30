<?php
/**
 * @author Administrator
 * @Date 2013年11月20日 星期三 10:20:01
 * @version 1.0
 * @description:外包立项人员租赁 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.mainId ,c.personLevel ,c.personLevelName ,c.pesonName ,c.userAccount ,c.userNo ,c.suppName ,c.suppId ,c.beginDate ,c.endDate ,c.totalDay ,c.inBudgetPrice ,c.selfPrice ,c.outBudgetPrice ,c.rentalPrice ,c.skillsRequired ,c.interviewResults ,c.interviewName ,c.interviewId ,c.remark,c.originalId,c.changeTips,c.isAddContract  from oa_outsourcing_approval_personrental c where 1=1 "
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
   	  )
)
?>