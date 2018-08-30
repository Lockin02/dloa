<?php
/**
 * @author Administrator
 * @Date 2011年5月5日 14:02:56
 * @version 1.0
 * @description:租赁借试用合同 sql配置文件
 */
$sql_arr = array (
     "select_default"=>"select c.id ,c.sign ,c.orderstate ,c.orderCode ,c.orderTempCode ,c.orderName ,c.orderMoney,c.orderTempMoney,c.tenant,c.closeName,c.closeType,c.closeDate,c.closeRegard,c.customerType,c.parentOrder ,c.parentOrderId ,c.orderType,c.hires ,c.tenantId ,c.tenantName ,c.hiresId ,c.hiresName ,c.timeLimit ,c.scienceMan ,c.beginTime ,c.closeTime ,c.paymentType ,c.paymentPrecept ,c.accountPaid ,c.accountPaidMoney ,c.needPayMoney ,c.rentMoney ,c.details ,c.remark ,c.ExaStatus ,c.ExaDT ,c.createId ,c.createName ,c.editDate ,c.editId ,c.editName ,c.district ,c.saleman,c.state,c.DeliveryStatus ,c.isTemp ,c.originalId,c.tenantNameId,c.areaName,c.areaCode,c.areaPrincipal,c.areaPrincipalId,c.remark,c.rate,c.currency,c.orderTempMoneyCur,c.orderMoneyCur,c.isBecome,c.objCode  from oa_sale_lease c where c.isTemp=0  ",
     'select_auditing' => "select w.task,p.ID as id, u.USER_NAME as UserName,
		c.id as contractId,c.orderCode,c.orderTempCode,c.orderName,c.createName,c.ExaStatus,c.createTime,c.closeName,c.closeType,c.closeDate,c.closeRegard,
		c.district,c.saleman,c.isTemp ,c.originalId
		from
		flow_step_partent p
		left join wf_task w on p.wf_task_id = w.task
		left join user u on u.USER_ID = p.User ,
		oa_sale_lease c
		where  c.isTemp=0 and
		p.Flag='0' and
		w.Pid =c.id and " .
		"w.examines <> 'no'",
	'select_audited' => "select w.task,p.ID as id, u.USER_NAME as UserName,
		c.id as contractId,c.orderCode,c.orderTempCode,c.orderName,c.createName,c.ExaStatus,c.createTime,c.closeName,c.closeType,c.closeDate,c.closeRegard,
		c.district,c.saleman,c.isTemp ,c.originalId
		from
		flow_step_partent p
		left join wf_task w on p.wf_task_id = w.task
		left join user u on u.USER_ID = p.User ,
		oa_sale_lease c where  c.isTemp=0 and
		p.Flag='1' and
		w.Pid =c.id ",


		/************变更合同审批 add by suxc********************/
		 'change_auditing' => "select w.task,p.ID as id, u.USER_NAME as UserName,
		c.id as contractId,c.orderCode,c.orderTempCode,c.orderName,c.createName,c.ExaStatus,c.createTime,
		c.district,c.saleman,c.isTemp ,c.originalId
		from
		flow_step_partent p
		left join wf_task w on p.wf_task_id = w.task
		left join user u on u.USER_ID = p.User ,
		oa_sale_lease c
		where  c.isTemp=1 and
		p.Flag='0' and
		w.Pid =c.id and " .
		"w.examines <> 'no'",
	'change_audited' => "select w.task,p.ID as id, u.USER_NAME as UserName,
		c.id as contractId,c.orderCode,c.orderTempCode,c.orderName,c.createName,c.ExaStatus,c.createTime,
		c.district,c.saleman,c.isTemp ,c.originalId
		from
		flow_step_partent p
		left join wf_task w on p.wf_task_id = w.task
		left join user u on u.USER_ID = p.User ,
		oa_sale_lease c where  c.isTemp=1 and
		p.Flag='1' and
		w.Pid =c.id "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
    array(
        "name" => "createTime",
        "sql" => " and c.createTime = # "
    ),
    array(
        "name" => "state",
        "sql" => " and c.state=#"
        ),
    array(
        "name" => "states",
        "sql" => " and c.state in(arr)"
        ),
   array(
   		"name" => "sign",
   		"sql" => " and c.sign=# "
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
        "name" => 'ajaxCodeChecking',
        "sql" => "$"
    ),
   array(
   		"name" => "OrajaxOrderCode",
   		"sql" => " or c.orderCode=# "
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
   		"name" => "parentOrder",
   		"sql" => " and c.parentOrder=# "
   	  ),
   array(
   		"name" => "parentOrderId",
   		"sql" => " and c.parentOrderId=# "
   	  ),
   array(
   		"name" => "orderType",
   		"sql" => " and c.orderType=# "
   	  ),
   array(
   		"name" => "tenant",
   		"sql" => " and c.tenant=# "
   	  ),
   array(
   		"name" => "hires",
   		"sql" => " and c.hires=# "
   	  ),
   array(
   		"name" => "tenantId",
   		"sql" => " and c.tenantId=# "
   	  ),
   array(
   		"name" => "tenantName",
   		"sql" => " and c.tenantName=# "
   	  ),
   array(
   		"name" => "hiresId",
   		"sql" => " and c.hiresId=# "
   	  ),
   array(
   		"name" => "hiresName",
   		"sql" => " and c.hiresName=# "
   	  ),
   array(
   		"name" => "timeLimit",
   		"sql" => " and c.timeLimit=# "
   	  ),
   array(
   		"name" => "scienceMan",
   		"sql" => " and c.scienceMan=# "
   	  ),
   array(
   		"name" => "beginTime",
   		"sql" => " and c.beginTime=# "
   	  ),
   array(
   		"name" => "closeTime",
   		"sql" => " and c.closeTime=# "
   	  ),
   array(
   		"name" => "paymentType",
   		"sql" => " and c.paymentType=# "
   	  ),
   array(
   		"name" => "paymentPrecept",
   		"sql" => " and c.paymentPrecept=# "
   	  ),
   array(
   		"name" => "accountPaid",
   		"sql" => " and c.accountPaid=# "
   	  ),
   array(
   		"name" => "accountPaidMoney",
   		"sql" => " and c.accountPaidMoney=# "
   	  ),
   array(
   		"name" => "needPayMoney",
   		"sql" => " and c.needPayMoney=# "
   	  ),
   array(
   		"name" => "rentMoney",
   		"sql" => " and c.rentMoney=# "
   	  ),
   array(
   		"name" => "details",
   		"sql" => " and c.details=# "
   	  ),
   array(
   		"name" => "remark",
   		"sql" => " and c.remark=# "
   	  ),
   array(
   		"name" => "ExaStatus",
   		"sql" => " and c.ExaStatus in(arr) "
   	  ),
   array(
   		"name" => "ExaStatusArr",
   		"sql" => " and c.ExaStatus in(arr) "
   	  ),
   array(
   		"name" => "ExaStatuss",
   		"sql" => " and c.ExaStatus in(arr) "
   	  ),
   array(
   		"name" => "ExaDT",
   		"sql" => " and c.ExaDT=# "
   	  ),
   array(
   		"name" => "createDate",
   		"sql" => " and c.createDate=# "
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
   		"name" => "editDate",
   		"sql" => " and c.editDate=# "
   	  ),
   array(
   		"name" => "editId",
   		"sql" => " and c.editId=# "
   	  ),
   array(
   		"name" => "editName",
   		"sql" => " and c.editName=# "
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
		"name" => "tenantNameId",//流程名称
		"sql"=>" and c.tenantNameId = # "
	),
    array(//编号模糊匹配
        "name" => "orderCodeOrTempSearch",
        "sql" => " and (c.orderCode like CONCAT('%',#,'%')  or c.orderTempCode like CONCAT('%',#,'%'))"
    ),
    array(
       "name" => "objCode",
       "sql" => "and c.objCode like CONCAT('%',#,'%')"
    )
)
?>