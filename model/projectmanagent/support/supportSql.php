<?php
/**
 * @author Administrator
 * @Date 2012-10-19 10:32:11
 * @version 1.0
 * @description:售前支持申请 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.id ,c.supportCode,c.SingleType ,c.SingleId ,c.prinvipalName ,c.prinvipalId ,c.projectCode ,c.projectName ,c.customerName ,c.customerId ,c.customerType ,c.customerTypeName ,c.signSubject ,c.signSubjectName ,c.recentExDate ,c.exDate ,c.exchangeName ,c.exchangeId ,c.linkman ,c.contact ,c.AClocation ,c.customerInfo ,c.opponents ,c.objectives ,c.impact ,c.expectedNum ,c.highestlevel ,c.customerRemark ,c.goals ,c.exContent ,c.exPlan ,c.prepared ,c.beginDate ,c.closeDate ,c.supContent ,c.otherRemark ,c.updateTime ,c.updateName ,c.updateId ,c.createTime ,c.createName ,c.createId ,c.ExaStatus ,c.ExaDT  from oa_sale_chance_support c where 1=1 "
);

$condition_arr = array (
    array(
       "name" => "supportCode",
       "sql" => " and c.supportCode like CONCAT('%',#,'%')"
    ),
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "SingleType",
   		"sql" => " and c.SingleType=# "
   	  ),
   array(
   		"name" => "SingleId",
   		"sql" => " and c.SingleId=# "
   	  ),
   array(
   		"name" => "prinvipalName",
   		"sql" => " and c.prinvipalName=# "
   	  ),
   array(
   		"name" => "prinvipalId",
   		"sql" => " and c.prinvipalId=# "
   	  ),
   array(
   		"name" => "projectCode",
   		"sql" => " and c.projectCode like CONCAT('%',#,'%')"
   	  ),
   array(
   		"name" => "projectName",
   		"sql" => " and c.projectName like CONCAT('%',#,'%')"
   	  ),
   array(
   		"name" => "customerName",
   		"sql" => " and c.customerName=# "
   	  ),
   array(
   		"name" => "customerId",
   		"sql" => " and c.customerId=# "
   	  ),
   array(
   		"name" => "customerType",
   		"sql" => " and c.customerType=# "
   	  ),
   array(
   		"name" => "customerTypeName",
   		"sql" => " and c.customerTypeName=# "
   	  ),
   array(
   		"name" => "signSubject",
   		"sql" => " and c.signSubject=# "
   	  ),
   array(
   		"name" => "signSubjectName",
   		"sql" => " and c.signSubjectName=# "
   	  ),
   array(
   		"name" => "recentExDate",
   		"sql" => " and c.recentExDate=# "
   	  ),
   array(
   		"name" => "exDate",
   		"sql" => " and c.exDate=# "
   	  ),
   array(
   		"name" => "exchangeName",
   		"sql" => " and c.exchangeName=# "
   	  ),
   array(
   		"name" => "exchangeId",
   		"sql" => " and c.exchangeId=# "
   	  ),
   array(
   		"name" => "linkman",
   		"sql" => " and c.linkman=# "
   	  ),
   array(
   		"name" => "contact",
   		"sql" => " and c.contact=# "
   	  ),
   array(
   		"name" => "AClocation",
   		"sql" => " and c.AClocation=# "
   	  ),
   array(
   		"name" => "customerInfo",
   		"sql" => " and c.customerInfo=# "
   	  ),
   array(
   		"name" => "opponents",
   		"sql" => " and c.opponents=# "
   	  ),
   array(
   		"name" => "objectives",
   		"sql" => " and c.objectives=# "
   	  ),
   array(
   		"name" => "impact",
   		"sql" => " and c.impact=# "
   	  ),
   array(
   		"name" => "expectedNum",
   		"sql" => " and c.expectedNum=# "
   	  ),
   array(
   		"name" => "highestlevel",
   		"sql" => " and c.highestlevel=# "
   	  ),
   array(
   		"name" => "customerRemark",
   		"sql" => " and c.customerRemark=# "
   	  ),
   array(
   		"name" => "goals",
   		"sql" => " and c.goals=# "
   	  ),
   array(
   		"name" => "exContent",
   		"sql" => " and c.exContent=# "
   	  ),
   array(
   		"name" => "exPlan",
   		"sql" => " and c.exPlan=# "
   	  ),
   array(
   		"name" => "prepared",
   		"sql" => " and c.prepared=# "
   	  ),
   array(
   		"name" => "beginDate",
   		"sql" => " and c.beginDate=# "
   	  ),
   array(
   		"name" => "closeDate",
   		"sql" => " and c.closeDate=# "
   	  ),
   array(
   		"name" => "supContent",
   		"sql" => " and c.supContent=# "
   	  ),
   array(
   		"name" => "otherRemark",
   		"sql" => " and c.otherRemark=# "
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
   	  )
)
?>