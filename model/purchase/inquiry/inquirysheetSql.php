<?php
/*采购询价单
 * Created on 2010-12-27
 * can
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 $sql_arr=array(
     "inquirysheet_list"=>"select c.id ,c.inquiryCode ,c.deptId ,c.deptName ,c.purcherId ,c.purcherName ,c.state ," .
     		"c.inquiryBgDate ,c.inquiryEndDate ,c.effectiveDate ,c.expiryDate ,c.paymentCondition ," .
     		"c.paymetType ,c.dateHope ,c.deliveryPlace ,c.remark ,c.suppId ,c.suppName ,c.amaldarRemark ," .
     		"c.amaldarName ,c.amaldarId ,c.amaldarDate ,c.ExaStatus ,c.ExaDT ,c.createId ,c.createName ,c.createTime ," .
     		"c.updateId ,c.updateName ,c.updateTime  from oa_purch_inquiry c where 1=1",
     		/*****************************************工作流部分***********************************/
			"sql_examine" => "select " .
			"w.task as taskId,p.ID as spid ,c.id ,c.inquiryCode ,c.deptId ,c.deptName ,c.purcherId ,c.purcherName ,c.state ," .
     		"c.inquiryBgDate ,c.inquiryEndDate ,c.effectiveDate ,c.expiryDate ,c.paymentCondition ," .
     		"c.paymetType ,c.dateHope ,c.deliveryPlace ,c.remark ,c.suppId ,c.suppName ,c.amaldarRemark ," .
     		"c.amaldarName ,c.amaldarId ,c.amaldarDate ,c.ExaStatus ,c.ExaDT ,c.createId ,c.createName ,c.createTime ," .
     		"c.updateId ,c.updateName ,c.updateTime " .
			" from flow_step_partent p left join wf_task w on p.wf_task_id = w.task left join user u on u.USER_ID = p.User ,oa_purch_inquiry c " .
			" where w.Pid =c.id and w.examines <> 'no' ",
			"sql_audited"=> "select " .
			"w.task as taskId,p.ID as spid ,c.id ,c.inquiryCode ,c.deptId ,c.deptName ,c.purcherId ,c.purcherName ,c.state ," .
     		"c.inquiryBgDate ,c.inquiryEndDate ,c.effectiveDate ,c.expiryDate ,c.paymentCondition ," .
     		"c.paymetType ,c.dateHope ,c.deliveryPlace ,c.remark ,c.suppId ,c.suppName ,c.amaldarRemark ," .
     		"c.amaldarName ,c.amaldarId ,c.amaldarDate ,c.ExaStatus ,c.ExaDT ,c.createId ,c.createName ,c.createTime ," .
     		"c.updateId ,c.updateName ,c.updateTime " .
			" from flow_step_partent p left join wf_task w on p.wf_task_id = w.task left join user u on u.USER_ID = p.User ,oa_purch_inquiry c " .
			" where p.Flag='1' and w.Pid =c.id "
 );

 $condition_arr=array(
      array(
         "name"=>"state",
         "sql" => "and c.state = # "
      ),
      array(
         "name"=>"id",
         "sql" => "and c.id = # "
      ),
      array(
         "name"=>"idArr",
         "sql" => "and c.id in(arr)"
      ),
      array(
         "name"=>"purcherId",
         "sql" => "and c.purcherId = # "
      ),
      array(
         "name"=>"purcherName",
         "sql" => "and c.purcherName like CONCAT('%',#,'%')"
      ),
      array(
         "name"=>"inquiryCode",
         "sql" => "and c.inquiryCode like CONCAT('%',#,'%')"
      ),
      array(
      	"name"=>"states",
      	"sql"=>" and c.state in(arr)"
      )
      ,array(
      	"name" => "suppId",
      	"sql" => " and c.suppId = # "
      )
      ,array(
      	"name" => "updateTime",
      	"sql" => " and c.updateTime = # "
      )
      ,array(
      	"name" => "paymentCondition",
      	"sql" => " and c.paymentCondition = # "
      ),array(
      	"name" => "ExaStatus",
      	"sql" => " and c.ExaStatus = # "
      ),
		//审核工作流
	array (
			"name" => "findInName", //审批人ID
			"sql" => " and  ( find_in_set( # , p.User ) > 0 ) "
	),
	array (
			"name" => "workFlowCode", //业务表
			"sql" => " and w.code =# "
	),
	array (
			"name" => "Flag", //业务表
			"sql" => " and p.Flag= # "
	),
	array (
		"name" => "taskId",
		"sql" => " and taskId = #"
	),
	array (
		"name" => "spid",
		"sql" => "and spid=#"
	),
	array (
		"name" => "suppName",
		"sql" => "and c.suppName like CONCAT('%',#,'%')"
	),
	array(
		"name" => "productNumb",
		"sql" => "and c.id in(select parentId from oa_purch_inquiry_equ where productNumb like CONCAT('%',#,'%'))"
	),
	array(
		"name" => "productName",
		"sql" => "and c.id in(select parentId from oa_purch_inquiry_equ where productName like CONCAT('%',#,'%'))"
	)
 );
?>
