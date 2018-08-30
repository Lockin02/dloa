<?php
/**
 * @author Administrator
 * @Date 2011年5月8日 14:02:38
 * @version 1.0
 * @description:研发合同 sql配置文件
 */
$sql_arr = array (
     "select_default"=>"select c.id ,c.sign ,c.orderstate ,c.orderCode ,c.orderTempCode ,c.orderName ,c.ExaStatus ,c.rdprojectNeed,c.parentOrder ,c.parentOrderId ," .
     		"c.cusNameId ,c.cusName ,c.customerOrder ,c.orderDate ,c.timeLimit ,c.customerLinkman ,c.linkmanNo ,c.orderPrincipal ,c.sectionPrincipal ," .
     		"c.sciencePrincipal ,c.invoiceType ,c.orderMoney,c.orderTempMoney,c.customerType,c.contractPeriod ,c.remark ,c.customerId ,c.customerName ,c.customerType," .
     		"c.updateTime ,c.updateName ,c.updateId ,c.createTime ,c.createName ,c.createId ,c.ExaDT ,c.closeName ,c.closeTime ,c.closeRegard " .
     		",c.district ,c.orderPrincipalId,c.saleman,c.state,c.isTemp ,c.originalId,c.beginDate,c.endDate,c.areaName,c.areaCode,c.areaPrincipal," .
     		"c.areaPrincipalId,c.remark,c.isBecome,c.objCode,c.orderProvince,c.orderProvinceId,c.orderCity,c.orderCityId  from oa_sale_rdproject c where c.isTemp=0 ",
     'select_auditing' => "select w.task,p.ID as id, u.USER_NAME as UserName,
		c.id as contractId,c.orderCode,c.orderTempCode,c.orderName,c.createName,c.ExaStatus,c.createTime,c.rdprojectNeed,
		c.district,c.saleman,c.cusName,c.orderPrincipal,c.orderPrincipalId,c.sectionPrincipal,c.sciencePrincipal,c.isTemp ,c.originalId
		from
		flow_step_partent p
		left join wf_task w on p.wf_task_id = w.task
		left join user u on u.USER_ID = p.User ,
		oa_sale_rdproject c
		where c.isTemp=0 and
		p.Flag='0' and
		w.Pid =c.id and " .
		"w.examines <> 'no'",
	'select_audited' => "select w.task,p.ID as id, u.USER_NAME as UserName,
		c.id as contractId,c.orderCode,c.orderTempCode,c.orderName,c.createName,c.ExaStatus,c.createTime,c.rdprojectNeed,
		c.district,c.saleman,c.cusName,c.orderPrincipal,c.orderPrincipalId,c.sectionPrincipal,c.sciencePrincipal,c.isTemp ,c.originalId
		from
		flow_step_partent p
		left join wf_task w on p.wf_task_id = w.task
		left join user u on u.USER_ID = p.User ,
		oa_sale_rdproject c where c.isTemp=0 and
		p.Flag='1' and
		w.Pid =c.id ",


		/**************变更审批 add by suxc********************/
	'change_auditing' => "select w.task,p.ID as id, u.USER_NAME as UserName,
		c.id as contractId,c.orderCode,c.orderTempCode,c.orderName,c.createName,c.ExaStatus,c.createTime,
		c.district,c.saleman,c.cusName,c.orderPrincipal,c.orderPrincipalId,c.sectionPrincipal,c.sciencePrincipal,c.isTemp ,c.originalId
		from
		flow_step_partent p
		left join wf_task w on p.wf_task_id = w.task
		left join user u on u.USER_ID = p.User ,
		oa_sale_rdproject c
		where c.isTemp=1 and
		p.Flag='0' and
		w.Pid =c.id and " .
		"w.examines <> 'no'",
	'change_audited' => "select w.task,p.ID as id, u.USER_NAME as UserName,
		c.id as contractId,c.orderCode,c.orderTempCode,c.orderName,c.createName,c.ExaStatus,c.createTime,
		c.district,c.saleman,c.cusName,c.orderPrincipal,c.orderPrincipalId,c.sectionPrincipal,c.sciencePrincipal,c.isTemp ,c.originalId
		from
		flow_step_partent p
		left join wf_task w on p.wf_task_id = w.task
		left join user u on u.USER_ID = p.User ,
		oa_sale_rdproject c where c.isTemp=1 and
		p.Flag='1' and
		w.Pid =c.id "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
        "name" => "orderPrincipalId",
        "sql" => "and c.orderPrincipalId =#"
   ),
   array(
   		"name" => "sign",
   		"sql" => " and c.sign=# "
   	  ),
   array(
        "name" => "states",
        "sql" => " and c.state in(arr)"
        ),
   array(
   		"name" => "orderstate",
   		"sql" => " and c.orderstate=# "
   	  ),
   array(
   		"name" => "orderCode",
   		"sql" => " and c.orderCode=# "
   	  ),
   array(
   		"name" => "ajaxOrderTempCode",
   		"sql" => " and c.orderTempCode=# "
   	  ),
   array(
   		"name" => "ajaxOrderCode",
   		"sql" => " and c.orderCode=# "
   	  ),
   array(
   		"name" => "OrajaxOrderCode",
   		"sql" => " or c.orderCode=# "
   	  ),
   	 array(
        "name" => 'ajaxCodeChecking',
        "sql" => "$"
    ),
   array(
   		"name" => "OrajaxOrderTempCode",
   		"sql" => " or c.orderTempCode=# "
   	  ),
   array(
   		"name" => "orderTempCode",
   		"sql" => " and c.orderTempCode=# "
   	  ),
   array(
   		"name" => "orderName",
   		"sql" => " and c.orderName like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "ExaStatus",
   		"sql" => " and c.ExaStatus in(arr) "
   	  ),
   array(
   		"name" => "ExaStatuss",
   		"sql" => " and c.ExaStatus in(arr)"
   	  ),
   array(
   		"name" => "ExaStatusArr",
   		"sql" => " and c.ExaStatus in(arr)"
   	  ),
   array(
   		"name" => "parentOrder",
   		"sql" => " and c.parentOrder=# "
   	  ),
   array(
   		"name" => "parentOrderId",
   		"sql" => " and c.parentOrderId=# "
   	  ),
   array(
   		"name" => "cusNameId",
   		"sql" => " and c.cusNameId=# "
   	  ),
   array(
   		"name" => "cusName",
   		"sql" => " and c.cusName=# "
   	  ),
   array(
   		"name" => "customerOrder",
   		"sql" => " and c.customerOrder=# "
   	  ),
   array(
   		"name" => "orderDate",
   		"sql" => " and c.orderDate=# "
   	  ),
   array(
   		"name" => "timeLimit",
   		"sql" => " and c.timeLimit=# "
   	  ),
   array(
   		"name" => "customerLinkman",
   		"sql" => " and c.customerLinkman=# "
   	  ),
   array(
   		"name" => "linkmanNo",
   		"sql" => " and c.linkmanNo=# "
   	  ),
   array(
   		"name" => "orderPrincipal",
   		"sql" => " and c.orderPrincipal=# "
   	  ),
   array(
   		"name" => "sectionPrincipal",
   		"sql" => " and c.sectionPrincipal=# "
   	  ),
   array(
   		"name" => "sciencePrincipal",
   		"sql" => " and c.sciencePrincipal=# "
   	  ),
   array(
   		"name" => "invoiceType",
   		"sql" => " and c.invoiceType=# "
   	  ),
   array(
   		"name" => "contractPeriod",
   		"sql" => " and c.contractPeriod=# "
   	  ),
   array(
   		"name" => "customerId",
   		"sql" => " and c.customerId=# "
   	  ),
   array(
   		"name" => "customerName",
   		"sql" => " and c.customerName=# "
   	  ),
   array(
   		"name" => "customerType",
   		"sql" => " and c.customerType=# "
   	  ),
   array(
   		"name" => "customerProvince",
   		"sql" => " and c.customerProvince=# "
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
   		"name" => "ExaDT",
   		"sql" => " and c.ExaDT=# "
   	  ),
   array(
   		"name" => "closeName",
   		"sql" => " and c.closeName=# "
   	  ),
   array(
   		"name" => "closeTime",
   		"sql" => " and c.closeTime=# "
   	  ),
   array(
   		"name" => "closeRegard",
   		"sql" => " and c.closeRegard=# "
   	  ),
   array(
   		"name" => "district",
   		"sql" => " and c.district=# "
   	  ),
   array(
   		"name" => "saleman",
   		"sql" => " and c.saleman=# "
   	  ),
	array(
		"name" => "findInName",//负责人
		"sql"=>" and  ( find_in_set( # , p.User ) > 0 ) "
	),
	array(
		"name" => "workFlow",//流程名称
		"sql"=>" and w.name in (#) "
	),
	array(
		"name" => "workFlowCode",//流程名称
		"sql"=>" and w.code = # "
	),
	array(
		"name" => "isTemp",
		"sql"=>" and c.isTemp = # "
	),
	array(
		"name" => "originalId",
		"sql"=>" and c.originalId = # "
	),
    array(//编号模糊匹配
        "name" => "orderCodeOrTempSearch",
        "sql" => " and (c.orderCode like CONCAT('%',#,'%')  or c.orderTempCode like CONCAT('%',#,'%'))"
    ),
   array(
   		"name" => "state",
   		"sql" => " and c.state=# "
   ),
   array(
       "name" => "objCode",
       "sql" => "and c.objCode like CONCAT('%',#,'%')"
    )
)
?>