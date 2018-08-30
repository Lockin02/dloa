<?php
/**
 * @author Administrator
 * @Date 2011年9月13日 14:34:44
 * @version 1.0
 * @description:赠送申请 sql配置文件
 */
$sql_arr = array (
         "select_default"=>"select c.areaName,c.areaCode,c.areaPrincipal,c.areaPrincipalId,c.id ,c.chanceId,c.orderId ,c.orderCode ,c.orderName ,c.Code ,c.Type ,c.customerName ,c.customerNameId ,c.limits ,c.salesName ,c.salesNameId ,c.reason ,c.remark ,c.updateTime ,c.updateName ,c.updateId ,c.createTime ,c.createName ,c.createId ,c.ExaStatus ,c.ExaDT ,c.closeName ,c.closeTime,c.closeRegard ,c.DeliveryStatus ,c.dealStatus,c.reserve1 ,c.reserve2 ,c.reserve3 ,c.reserve4 ,c.reserve5,c.deliveryDate,c.objCode,c.rObjCode,c.SingleType,c.feeMan,c.feeManId  from oa_present_present c where 1=1 and isTemp = 0 "

		,"select_shipments"=>"select c.changeTips,c.id ,c.rObjCode ,c.objCode ,c.orderId ,c.orderCode ,c.orderName ,c.Code ,
				c.Type ,c.customerName ,c.customerNameId ,c.limits ,c.salesName ,c.salesNameId ,c.standardDate ,
				c.reason ,c.remark ,c.updateTime ,c.updateName ,c.updateId ,c.createTime ,c.createName ,
				c.createId ,c.ExaStatus ,c.ExaDT ,c.closeName ,c.closeTime ,c.closeRegard ,c.DeliveryStatus ,
				c.customTypeId,c.customTypeName,c.warnDate,c.isDel ,c.isTemp ,c.originalId ,c.changeEquDate ,c.changeEquName ,c.changeEquNameId ,
				c.makeStatus ,c.dealStatus ,c.reserve1 ,c.reserve2 ,c.reserve3 ,c.reserve4 ,c.reserve5,
				l.id as lid ,l.ExaStatus as lExaStatus,l.ExaDTOne as lExaDTOne, l.id as linkId
				from oa_present_present c left join oa_present_equ_link l on
						(c.id=l.presentId and l.presentType='oa_present_present' and l.isTemp=0)
				 where (select count(*) from oa_present_equ e where  e.isTemp=0 and e.isDel=0 and e.presentId=c.id)>0 and
				 c.isTemp=0 "

		,"select_assignment"=>"select c.changeTips,c.id ,c.rObjCode ,c.objCode ,c.orderId ,c.orderCode ,c.orderName ,c.Code ,
				c.Type ,c.customerName ,c.customerNameId ,c.limits ,c.salesName ,c.salesNameId ,c.standardDate ,
				c.reason ,c.remark ,c.updateTime ,c.updateName ,c.updateId ,c.createTime ,c.createName ,
				c.createId ,c.ExaStatus ,c.ExaDT ,c.closeName ,c.closeTime ,c.closeRegard ,c.DeliveryStatus ,
				c.customTypeId,c.customTypeName,c.warnDate,c.isDel ,c.isTemp ,c.originalId ,c.changeEquDate ,c.changeEquName ,c.changeEquNameId ,
				c.makeStatus ,c.dealStatus ,c.reserve1 ,c.reserve2 ,c.reserve3 ,c.reserve4 ,c.reserve5,
				l.id as lid ,l.ExaStatus as lExaStatus,l.ExaDTOne as lExaDTOne, l.id as linkId
				from oa_present_present c left join oa_present_equ_link l on
						(c.id=l.presentId and l.presentType='oa_present_present' and l.isTemp=0) where c.isTemp=0 "
		,"select_equ" => "SELECT * FROM ( SELECT
								ce.productId AS id,
								ce.productId,
								ce.productNo,
								ce.productNo AS productCode,
								ce.productName,
								SUM(ce.number) AS number,
								SUM(ce.executedNum) AS executedNum,
								SUM(ce.onWayNum) AS onWayNum
							FROM
								oa_present_equ ce
							RIGHT JOIN oa_present_present c ON (c.id = ce.presentId)
							WHERE
								1 = 1
							AND c.ExaStatus = '完成' AND c.dealStatus IN(1,3) AND c.isTemp=0
							AND c.DeliveryStatus != 'TZFH'
							AND ce.isTemp = 0
							AND ce.isDel = 0
							GROUP BY productId ) ce WHERE 1 = 1 AND ce.number-ce.executedNum>0 "
	,"select_cont" => "SELECT
						c.id,
						c.code,
						c.type,
						ce.number,
						ce.onWayNum,
						ce.executedNum
					FROM
						oa_present_present c LEFT JOIN oa_present_equ ce
							ON ( c.id=ce.presentId )
					WHERE
						1 = 1
					AND (ce.number-ce.executedNum>0) and c.dealStatus in(1,3) and c.isTemp=0
					AND c.ExaStatus = '完成' AND c.DeliveryStatus != 'TZFH' AND ce.isTemp=0 AND ce.isDel=0 "


        ,'select_auditing' => "select w.task,p.ID as id, u.USER_NAME as UserName,
		c.id as presentId,c.orderId ,c.orderCode ,c.orderName ,c.Code ,c.Type ,c.customerName ,c.customerNameId ,c.limits ,c.salesName ,c.salesNameId ,c.reason ,c.remark ,c.updateTime ,c.updateName ,c.updateId ,c.createTime ,c.createName ,c.createId ,c.ExaStatus ,c.ExaDT ,c.closeName ,c.closeTime,c.closeRegard ,c.DeliveryStatus ,c.reserve1 ,c.reserve2 ,c.reserve3 ,c.reserve4 ,c.reserve5,c.deliveryDate
		from
		flow_step_partent p
		left join wf_task w on p.wf_task_id = w.task
		left join user u on u.USER_ID = p.User ,
		oa_present_present c
		where
		p.Flag='0' and
		w.Pid =c.id and " .
		"w.examines <> 'no'",
	'select_audited' => "select w.task,p.ID as id, u.USER_NAME as UserName,
		c.id as presentId ,c.orderId ,c.orderCode ,c.orderName ,c.Code ,c.Type ,c.customerName ,c.customerNameId ,c.limits ,c.salesName ,c.salesNameId ,c.reason ,c.remark ,c.updateTime ,c.updateName ,c.updateId ,c.createTime ,c.createName ,c.createId ,c.ExaStatus ,c.ExaDT ,c.closeName ,c.closeTime,c.closeRegard ,c.DeliveryStatus ,c.reserve1 ,c.reserve2 ,c.reserve3 ,c.reserve4 ,c.reserve5,c.deliveryDate
		from
		flow_step_partent p
		left join wf_task w on p.wf_task_id = w.task
		left join user u on u.USER_ID = p.User ,
		oa_present_present c where
		p.Flag='1' and
		w.Pid =c.id "
);

$condition_arr = array (
    array (
		"name" => "ids",
		"sql" => " and c.id in(arr) "
	),
    array (
        "name" => "chanceId",
        "sql" => " and c.chanceId=# "
    ),
	array (
		"name" => "lExaStatus",
		"sql" => " and l.ExaStatus like CONCAT('%',#,'%') "
	),
	array (
		"name" => "ExaStatusArr",
		"sql" => " and c.ExaStatus in(arr) "
	),
	array (
		"name" => "lExaStatusArr",
		"sql" => " and l.ExaStatus in(arr) "
	),
   array(
        "name" => "dealStatusArr",
        "sql" => "and c.dealStatus in(arr) "
    ),
	array (
		"name" => "dealStatus",
		"sql" => " and c.dealStatus=# "
	),
	array (
		"name" => "customTypeId",
		"sql" => " and c.customTypeId=# "
	),
	array (
		"name" => "customTypeName",
		"sql" => " and c.customTypeName=# "
	),
	array (
		"name" => "warnDate",
		"sql" => " and c.warnDate=# "
	),
	array(
   		"name" => "id",
   		"sql" => " and c.Id=# "
        ),
   array(
   		"name" => "orderId",
   		"sql" => " and c.orderId=# "
   	  ),
   array(
   		"name" => "orderCode",
   		"sql" => " and c.orderCode like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "orderName",
   		"sql" => " and c.orderName=# "
   	  ),
   array(
   		"name" => "Code",
   		"sql" => " and c.Code like CONCAT('%',#,'%')"
   	  ),
   	array(
   		"name" => "objCode",
   		"sql" => " and c.objCode like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "Type",
   		"sql" => " and c.Type=# "
   	  ),
   array(
   		"name" => "customerName",
   		"sql" => " and c.customerName like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "customerNameId",
   		"sql" => " and c.customerNameId=# "
   	  ),
   array(
   		"name" => "limits",
   		"sql" => " and c.limits=# "
   	  ),
   array(
   		"name" => "salesName",
   		"sql" => " and c.salesName like CONCAT('%',#,'%') "
   	  ),
   array(
   		"name" => "salesNameId",
   		"sql" => " and c.salesNameId=# "
   	  ),
   array(
   		"name" => "reason",
   		"sql" => " and c.reason=# "
   	  ),
   array(
   		"name" => "remark",
   		"sql" => " and c.remark=# "
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
   		"name" => "closeName",
   		"sql" => " and c.closeName=# "
   	  ),
   array(
   		"name" => "closeTime2",
   		"sql" => " and c.closeTime2=# "
   	  ),
   array(
   		"name" => "closeRegard",
   		"sql" => " and c.closeRegard=# "
   	  ),
   array(
   		"name" => "DeliveryStatus",
   		"sql" => " and c.DeliveryStatus=# "
   	  ),
   array(
   		"name" => "DeliveryStatus1",//未完成的发货状态
   		"sql" => " and c.DeliveryStatus in(arr)"
   	  ),
   array(
   		"name" => "DeliveryStatus2",//已完成的发货状态
   		"sql" => " and c.DeliveryStatus in(arr) "
   	  ),
   array(
   		"name" => "reserve1",
   		"sql" => " and c.reserve1=# "
   	  ),
   array(
   		"name" => "reserve2",
   		"sql" => " and c.reserve2=# "
   	  ),
   array(
   		"name" => "reserve3",
   		"sql" => " and c.reserve3=# "
   	  ),
   array(
   		"name" => "reserve4",
   		"sql" => " and c.reserve4=# "
   	  ),
   array(
   		"name" => "reserve5",
   		"sql" => " and c.reserve5=# "
   	  ),
   //审核工作流
	array(
		"name" => "findInName",//审批人ID
		"sql"=>" and  ( find_in_set( # , p.User ) > 0 ) "
	),
	array(
		"name" => "workFlowCode",//业务表
		"sql"=>" and w.code =# "
	),
	array(
		"name" => "Flag",//业务表
		"sql"=>" and p.Flag= # "
	),
    array(
        "name" => "createIdOr",
        "sql" => " or c.createId=# )"
	),
	array(
		"name" => "createSections",
		"sql"=>" and ( c.createSectionId in(arr) "
	),
	array(
	   "name" => "isTemp",
	   "sql" => "and c.isTemp"
	)
	/**********设备汇总表**********/
	,array (
		"name" => "productId",
		"sql" => " and ce.productId=# "
	),array (
		"name" => "productNo",
		"sql" => " and ce.productNo like CONCAT('%',#,'%')  "
	),array (
		"name" => "productName",
		"sql" => " and ce.productName like CONCAT('%',#,'%')  "
	),
	array (
	    "name" => "areaCodeSql",
	    "sql" => "$"
	)
)
?>