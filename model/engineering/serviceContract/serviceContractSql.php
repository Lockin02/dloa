<?php
/**
 * @author Administrator
 * @Date 2011年5月4日 14:43:22
 * @version 1.0
 * @description:服务合同 sql配置文件
 */
$sql_arr = array (
	"select_default"=>"select c.id ,c.sign ,c.orderstate ,c.orderCode ,c.orderTempCode ,c.orderName ,c.parentOrder ,c.parentOrderId ,c.orderPrincipalId,c.customerOrder ," .
			"c.orderDate,c.cusName,c.timeLimit ,c.customerLinkman ,c.linkmanNo ,c.orderPrincipal ,c.sectionPrincipal ,c.sciencePrincipal ,c.cusName,c.orderMoney," .
			"c.orderTempMoney,c.customerType,c.invoiceType ,c.contractPeriod ,c.remark ,c.customerId ,c.customerName ,c.customerType,c.updateTime ," .
			"c.updateName ,c.updateId ,c.createTime ,c.createName ,c.createId ,c.ExaStatus ,c.ExaDT ,c.closeName ,c.closeTime ,c.closeRegard ,c.district ,c.saleman," .
			"c.state,c.transmit,c.isTemp,c.originalId,c.isShipments,c.beginDate,c.endDate,c.areaName,c.areaCode,c.areaPrincipal,c.areaPrincipalId,c.remark,c.isBecome," .
			"c.objCode,c.orderProvince,c.orderProvinceId,c.orderCity,c.orderCityId from oa_sale_service c where isTemp=0 ",
	"select_state"=>"select c.id ,c.orderstate ,c.orderCode ,c.orderTempCode ,c.orderName,c.orderPrincipal ,c.sectionPrincipal ,c.sciencePrincipal ,c.cusName,c.orderMoney,c.customerType,c.invoiceType ,c.contractPeriod ,c.remark ,c.customerId ,c.customerName ,c.customerType ,c.customerProvince ,c.updateTime ,c.updateName ,c.updateId ,c.createTime ,c.createName ,c.createId ,c.ExaStatus ,c.ExaDT ,c.closeName ,c.closeTime ,c.closeRegard ,c.district ,c.saleman,c.state,c.transmit,c.isTemp,c.originalId,c.isShipments,c.beginDate,c.endDate,c.areaName,c.areaCode,c.areaPrincipal,c.areaPrincipalId,c.remark from oa_sale_service c where 1=1 ",
	'select_auditing' => "select w.task,p.ID as id, u.USER_NAME as UserName,
		c.id as contractId,c.orderCode,c.orderTempCode,c.orderName,c.createName,c.ExaStatus,c.createTime,
		c.district,c.saleman,c.cusName,c.orderPrincipal,c.orderPrincipalId,c.sectionPrincipal,c.sciencePrincipal,c.transmit,c.isTemp,c.originalId
		from
		flow_step_partent p
		left join wf_task w on p.wf_task_id = w.task
		left join user u on u.USER_ID = p.User ,
		oa_sale_service c
		where c.isTemp=0 and
		p.Flag='0' and
		w.Pid =c.id and " .
		"w.examines <> 'no'",
	'select_audited' => "select w.task,p.ID as id, u.USER_NAME as UserName,
		c.id as contractId,c.orderCode,c.orderTempCode,c.orderName,c.createName,c.ExaStatus,c.createTime,
		c.district,c.saleman,c.cusName,c.orderPrincipal,c.orderPrincipalId,c.sectionPrincipal,c.sciencePrincipal,c.transmit,c.isTemp,c.originalId
		from
		flow_step_partent p
		left join wf_task w on p.wf_task_id = w.task
		left join user u on u.USER_ID = p.User ,
		oa_sale_service c
		where  c.isTemp=0 and
		p.Flag='1' and
		w.Pid =c.id ",
		/************变更审批 add by suxc******************/
		'change_auditing' => "select w.task,p.ID as id, u.USER_NAME as UserName,
		c.id as contractId,c.orderCode,c.orderTempCode,c.orderName,c.createName,c.ExaStatus,c.createTime,
		c.district,c.saleman,c.cusName,c.orderPrincipal,c.orderPrincipalId,c.sectionPrincipal,c.sciencePrincipal,c.transmit,c.isTemp,c.originalId
		from
		flow_step_partent p
		left join wf_task w on p.wf_task_id = w.task
		left join user u on u.USER_ID = p.User ,
		oa_sale_service c
		where c.isTemp=1 and
		p.Flag='0' and
		w.Pid =c.id and " .
		"w.examines <> 'no'",
		'change_audited' => "select w.task,p.ID as id, u.USER_NAME as UserName,
		c.id as contractId,c.orderCode,c.orderTempCode,c.orderName,c.createName,c.ExaStatus,c.createTime,
		c.district,c.saleman,c.cusName,c.orderPrincipal,c.orderPrincipalId,c.sectionPrincipal,c.sciencePrincipal,c.transmit,c.isTemp,c.originalId
		from
		flow_step_partent p
		left join wf_task w on p.wf_task_id = w.task
		left join user u on u.USER_ID = p.User ,
		oa_sale_service c
		where  c.isTemp=1 and
		p.Flag='1' and
		w.Pid =c.id ",
	"select_process" => "select c.id ,c.orgid ,c.tablename,c.orderCode ,c.orderProvince,c.orderTempCode ,c.orderName ,c.prinvipalName,
			 c.orderMoney,c.orderTempMoney,c.customerName ,c.customerProvince ,
			c.ExaStatus ,c.ExaDT ,c.state,c.areaName,c.areaPrincipal,c.dealStatus,if(p.contractProcess is null ,0,p.contractProcess) as contractProcess,
            c.objCode
		from  serviceorder_oa_asengineering c
			left join
				(select c.rObjCode,round(sum(c.workRate*c.projectProcess/100),2) as contractProcess from oa_esm_project c where 1=1 group by c.rObjCode) p on c.objCode = p.rObjCode where isTemp=0 "
);

$condition_arr = array (
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
        "name" => "createTime",
        "sql" => "and c.createTime = #"
   ),
   array(
       "name" => "orderPrincipalId",
       "sql" => "and c.orderPrincipalId = #"
   ),
   array(
   		"name" => "sign",
   		"sql" => " and c.sign=# "
   	  ),
   array(
        "name" => "states",
        "sql" => "and c.state in(arr)"
      ),
   array(
        "name" => "state",
        "sql" => "and c.state = #"
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
   		"name" => "orderTempCode",
   		"sql" => " and c.orderTempCode=# "
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
   		"name" => "OrajaxOrderTempCode",
   		"sql" => " or c.orderTempCode=# "
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
   		"name" => "invoiceType",
   		"sql" => " and c.invoiceType=# "
   	  ),
   array(
   		"name" => "contractPeriod",
   		"sql" => " and c.contractPeriod=# "
   	  ),
   array(
   		"name" => "cusNameId",
   		"sql" => " and c.cusNameId=# "
   	  ),
   array(
   		"name" => "customerName",
   		"sql" => " and c.customerName=# "
   	  ),
   array(
   		"name" => "updateId",
   		"sql" => " and c.updateId=# "
   	  ),
   array(
   		"name" => "createId",
   		"sql" => " and c.createId=# "
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
   		"name" => "closeName",
   		"sql" => " and c.closeName=# "
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
        "name" => 'ajaxCodeChecking',
        "sql" => "$"
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
	    "name" => "isShipments",
	    "sql" => "and c.isShipments = #"
	),
    array(//编号模糊匹配
        "name" => "orderCodeOrTempSearch",
        "sql" => " and (c.orderCode like CONCAT('%',#,'%')  or c.orderTempCode like CONCAT('%',#,'%'))"
    ),
    array(
   		"name" => "orderName",
   		"sql" => " and c.orderName like CONCAT('%',#,'%') "
    ),
    array(
       "name" => "objCode",
       "sql" => "and c.objCode like CONCAT('%',#,'%')"
    ),
    array(
       "name" => "isTemp",
       "sql" => "and c.isTemp =#"
    ),
    array(
       "name" => "dealStatus",
       "sql" => "and c.dealStatus =#"
    ),
    array(
    	"name" => "orderProvinces",
    	"sql" => "and c.orderProvince in(arr)"
    )
)
?>