<?php
$sql_arr = array (
	"equipment_list" => "select p.id ,p.planId ,p.purchType ,p.basicNumb ,p.planEquId ,p.basicId ,p.productName ,p.productId ,p.productNumb ,p.pattem ,p.unitName ,p.amountAll ,p.amountIssued ,p.contractAmount ,p.dateIssued ,p.dateHope ,p.dateEnd ,p.remark ,p.status,p.batchNumb,p.applyId,p.applyEquId,p.qualityCode,p.qualityName  from oa_purch_task_equ p where p.amountAll>0 and 1=1",
	"equipment_list_shot" => "select p.id ,p.applyId ,p.purchType ,p.basicNumb ,p.basicId ,p.productName ,p.productId ,p.productNumb ,p.pattem ,p.unitName ,p.amountAll ,p.amountIssued ,p.contractAmount ,p.dateIssued ,p.dateHope ,p.dateEnd ,p.remark ,p.status  " .
			"from " .
				"oa_purch_task_equ p  where  p.amountAll>0 and 1=1",
	"equipment_list_long" => "select " .
				" p.id ,p.planId ,p.purchType ,p.basicNumb ,p.planEquId ,p.basicId ,p.productName ,p.productId ,p.productNumb ,p.pattem ,p.unitName ,p.amountAll ,p.amountIssued ,p.contractAmount ,p.dateIssued ,p.dateHope ,p.dateEnd ,p.remark ,p.status " .
			"from " .
				"oa_purch_task_equ p  where  p.amountAll>0 and 1=1",
	"equs_planTotask" => "select  p.id ,p.planId ,p.purchType ,p.basicNumb ,p.planEquId ,p.basicId ,p.productName ,p.productId ,p.productNumb ,p.pattem ,p.unitName ,p.amountAll ,p.amountIssued ,p.contractAmount ,p.dateIssued ,p.dateHope ,p.dateEnd ,p.remark ,p.status " .
			"from oa_purch_task_equ p where  p.amountAll>0 and  1=1",
	"equipment_list_plan" => "select " .
				" p.id ,p.planId ,p.purchType ,p.basicNumb ,p.planEquId,p.basicId ,p.productName ,p.productId ,p.productNumb ,p.pattem ,p.unitName ,p.amountAll ,p.amountIssued ,p.contractAmount ,p.dateIssued ,p.dateHope ,p.dateEnd ,p.remark ,p.status ".
			"from oa_purch_task_equ p   where  p.amountAll>0 and 1=1",
	"equpment_basic" => "select p.id ,p.planId ,p.purchType ,p.basicNumb ,p.planEquId ,p.basicId ,p.productName ,p.productId ,p.productNumb ,p.pattem ,p.unitName ,p.amountAll ,p.amountIssued ,p.contractAmount ,p.dateIssued ,p.dateHope ,p.dateEnd,c.sendUserId from oa_purch_task_equ p " .
						" left join oa_purch_task_basic c on c.id=p.basicId	where p.amountAll>0 and c.state=1",
	"equpment_basic_progress" => "select p.id ,p.planId ,p.purchType ,p.basicNumb ,p.planEquId ,p.basicId ,p.productName ,p.productId ,p.productNumb ,p.amountAll ,p.amountIssued,p.contractAmount ,p.dateIssued ,c.sendUserId,c.sendName,c.sendTime,c.state," .
							"c.dateReceive,c.dateFact,c.closeRemark,pc.planNumb,pc.sendName as planSendName,pc.department,p.applyId,p.applyEquId  from oa_purch_task_equ p " .
						" left join oa_purch_task_basic c on c.id=p.basicId	" .
						" left join oa_purch_plan_basic pc on pc.id=p.planId where p.amountAll>0",
	"equpment_execute" => "select p.id ,p.planEquId ,p.amountAll ,p.amountIssued ,p.contractAmount  ,p.dateHope ,p.dateEnd,c.sendUserId,c.sendName from oa_purch_task_equ p " .
						" left join oa_purch_task_basic c on c.id=p.basicId	where p.amountAll>0",
	"select_arrivalNum"=>"select a.id,a.arrivalNum from oa_purchase_arrival_equ a
						left join oa_purch_apply_equ b on b.id = a.contractId
						left join oa_purch_task_equ c on c.id = b.taskEquId
						left join oa_purch_plan_equ d on d.id = c.planEquId where 1=1"
);

$condition_arr = array (

	//主业务对象Ids
	array(
		"name" => "basicIds",
		"sql" => " and p.basicId in(arr) "
	),
	array(
		"name" => "planId",
		"sql" => " and p.planId =# "
	),

	//设备对象编号
	array(
		"name" => "deviceNumb",
		"sql" => "and p.deviceNumb=#"
	),
	//是否在使用
//	array(
//		"name" => "deviceIsUse",
//		"sql" => " and p.deviceIsUse=# "
//	),
	//采购任务Id
	array(
		"name" => "basicId",
		"sql" => " and p.basicId=#"
	),
	//主业务对象编号
	array(
		"name" => "basicNumb",
		"sql" => " and p.basicNumb=# "
	),
	//状态
	array(
		"name" => "status",
		"sql" => " and p.status=# "
	),
	//通过采购计划编号查询
	array(
		"name" => "planNumb",
		"sql" => " and p.planNumb=# "
	),
	//通过采购计划Id查询
	array(
		"name" => "planId",
		"sql" => " and p.planId=# "
	),
	//id集合
	array(
		"name" => "arrayIds",
		"sql" => " and p.id in(arr) "
	),
	//设备Id
//	array(
//		"name" => "productId",
//		"sql" => " and p.productId=# "
//	),
	array(
		"name" => "productId",
		"sql" => " and p.productId in(arr) "
	),
	array(
		"name" => "purchTypeArr",
		"sql" => " and p.purchType in(arr) "
	),
	//设备编号
	array(
		"name" => "productNumb",
		"sql" => " and p.productNumb=# "
	),
	//设备编号
	array(
		"name" => "productNumbArr",
		"sql" => " and p.productNumb in(arr) "
	),
	//设备编号
	array(
		"name" => "productName",
		"sql" => " and p.productName=# "
	)
//	),array(
//		"name" => "productNumb",
//		"sql" => " and p.proListId like CONCAT('%',#,'%')"
//	)
	,array(
		"name" => "productNameSear",
		"sql" => " and p.productName like CONCAT('%',#,'%')"
	)
	,array(
		"name" => "productNumbSear",
		"sql" => " and p.productNumb like CONCAT('%',#,'%')"
	)
	,array(
		"name" => "sendTimeSear",
		"sql" => " and c.sendTime LIKE BINARY  CONCAT('%',#,'%')"
	)
	,array(
		"name" => "sendNameSear",
		"sql" => " and c.sendName like CONCAT('%',#,'%')"
	)
	,array(
		"name" => "dateReceiveSear",
		"sql" => " and c.dateReceive LIKE BINARY  CONCAT('%',#,'%')"
	)
	,array(
		"name" => "planNumbSear",
		"sql" => " and pc.planNumb like CONCAT('%',#,'%')"
	)


	,array(
		"name" => "planEquId",
		"sql" => " and p.planEquId in(arr) "
	),
	array(
		"name" => "searchPlanEquId",
		"sql" => " and p.planEquId=# "
	),
	// 采购类型
	array(
		"name" => "purchType",
		"sql" => " and c.purchType=# "
	),
	array(
		"name" => "sendUserId",
		"sql" => " and c.sendUserId=# "
	),
	array(
		"name" => "sendName",
		"sql" => " and c.sendName=# "
	),
	array(
		"name" => "sendTime",
		"sql" => " and c.sendTime=# "
	),
	array(
		"name" => "sendBeginTime",
		"sql" => "and date_format(c.sendTime,'%Y-%m-%d') >=#"
	),
	array(
		"name" => "sendEndTime",
		"sql" => "and date_format(c.sendTime,'%Y-%m-%d')<=#"
	),
	array(
		"name" => "dateReceive",
		"sql" => " and c.dateReceive=# "
	),
	array(
		"name" => "equSearch",
		"sql" => " and p.productName like CONCAT('%',#,'%') "
	),
	array(
		"name" => "state",
		"sql" => " and c.state=# "
	),
	array(
		"name" => "taskEquId",
		"sql" => " and b.taskEquId=# "
	),
	array(
		"name" => "getPlanEquId",
		"sql" => " and d.id=# "
	),
    array(
        "name" => "businessBelongPc",
        "sql" => " and pc.businessBelong in(arr)"
    )


);
?>