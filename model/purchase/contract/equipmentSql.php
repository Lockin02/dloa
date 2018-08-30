<?php
header("Content-type: text/html; charset=gb2312");
$sql_arr = array (
	"equipment_list" => "select p.id ,p.objCode ,p.taskEquId ,p.inquiryEquId ,p.basicNumb ,p.basicId ,p.purchType ,p.productName ," .
			"p.productId ,p.productNumb ,p.pattem ,p.units ,p.price ,p.moneyAll ,p.taxRate,cast(p.amountAll as decimal(10,0)) as amountAll ,p.amountIssued ,p.dateIssued ,p.dateHope ," .
			"p.dateEnd ,p.remark ,p.applyPrice ,p.applyFactPrice ,p.status,p.applyDeptId,p.applyDeptName,p.sourceID,p.sourceNumb,p.isTemp ,p.originalId,p.rObjCode,p.applyId,p.applyEquId,p.batchNumb,p.payMoney,p.qualityCode,p.qualityName   from oa_purch_apply_equ p where p.amountAll>0 and 1=1 ",
	"equipment_listNew" => "select p.id ,p.objCode ,p.taskEquId ,p.inquiryEquId ,p.basicNumb ,p.basicId ,p.purchType ,p.productName ," .
			"p.productId ,p.productNumb ,p.pattem ,p.units ,p.price ,p.moneyAll ,p.taxRate,cast(p.amountAll as decimal(10,0)) as amountAll ,p.amountIssued ,p.dateIssued ,p.dateHope ," .
			"p.dateEnd ,p.remark ,p.applyPrice ,p.applyFactPrice ,p.status,p.applyDeptId,p.applyDeptName,p.sourceID,p.sourceNumb,p.isTemp ,p.originalId,p.rObjCode,p.applyId,p.applyEquId,p.batchNumb,p.payMoney,p.qualityCode,p.qualityName   from oa_purch_apply_equ p where 1=1 ",
	"select_type" => "select " .
				"p.id,p.objCode,p.inquiryEquId,p.basicNumb,p.basicId,p.taskEquId,p.productName,p.productId,p.productNumb ,p.pattem ,p.units,p.amountAll,p.amountIssued, " .
				"(p.amountAll-p.amountIssued) as amountUnArrival".
				",p.dateIssued,p.dateHope,p.dateEnd,p.remark,p.applyPrice,p.applyFactPrice,p.status," .
				"o.planAssType as oPurchType,p.isTemp ,p.originalId  " .
			"from oa_purch_apply_equ p left join oa_purch_objass o on p.id=o.applyEquId where p.amountAll>0 and  1=1",
	"contract_planequ_list"=>"select ae.id,ae.basicId,ae.taskEquId, te.id as teId,te.planId,te.planEquId ,pe.id as peId,pe.basicId,pe.basicNumb,pe.productName,pe.productNumb,pe.amountAll,pe.dateHope,pe.remark,
								p.id as PId,p.purchType,p.sourceNumb,p.sourceID,p.department,p.sendName,p.rObjCode
									from  oa_purch_task_equ te
										left join oa_purch_apply_equ ae on (ae.taskEquId=te.id )
											left join oa_purch_plan_equ pe on (pe.id=te.planEquId)
												left join oa_purch_plan_basic p on(p.id=pe.basicId)" .
														"where 1=1",
	//获取固定资产采购申请信息
	"contract_assetequ_list"=>"select pe.pattem,p.address,ae.id,ae.basicId,ae.taskEquId, te.id as teId,te.planId,te.planEquId ,pe.id as peId,pe.applyId,pe.applyCode as basicNumb,pe.productName,pe.productCode as productNumb,pe.applyAmount as amountAll,pe.dateHope,pe.remark,
								p.id as PId,te.purchType,p.applyDetName as department,p.createName as sendName, p.createId AS sendId,ae.rObjCode
									from  oa_purch_task_equ te
										left join oa_purch_apply_equ ae on (ae.taskEquId=te.id )
											left join oa_asset_purchase_apply_item pe on (pe.id=te.applyEquId)
												left join oa_asset_purchase_apply p on(p.id=pe.applyId) where 1=1",
	"contract_inquiryequ_list"=>"select ae.id,ae.basicId,ae.inquiryEquId,ie.id as ieId,ie.parentId,ie.productNumb,ie.productName,i.inquiryCode
								from oa_purch_inquiry_equ ie
									left join oa_purch_apply_equ ae on(ie.id=ae.inquiryEquId)
										left join oa_purch_inquiry i on(i.id=ie.parentId)" .
											"where 1=1",
	"inquiry_suppequ_list"=>"select ise.id,ise.productName,ise.parentId,ise.price,ise.inquiryEquId,ise.taxRate,ie.id as ieId,s.suppName
								from oa_purch_inquiry_suppequ ise
									left join oa_purch_inquiry_equ ie on(ie.id=ise.inquiryEquId)
										left join oa_purch_inquiry_supp s on(s.id=ise.parentId)" .
											"where 1=1",
	"history_price"=>"select p.id, p.productId,p.productNumb,p.productName,p.basicId,c.hwapplyNumb,cast(p.amountAll as decimal(10,0)) as amountAll,p.taxRate,p.price,p.applyPrice ,c.suppId,c.suppName,c.createTime,date_format(c.createTime,'%Y-%m-%d') as orderTime,c.paymentConditionName,c.payRatio
						from oa_purch_apply_equ p
						 left join oa_purch_apply_basic c on (p.basicId=c.id) where c.isTemp=0 and p.amountAll>0 and c.ExaStatus='完成'  ",
	"equ_execute"=>"select p.id, p.productId,p.productNumb,p.productName,p.basicId,c.hwapplyNumb,p.dateIssued ,p.dateHope,cast(p.amountAll as decimal(10,0)) as amountAll ,p.amountIssued
						from oa_purch_apply_equ p
						 left join oa_purch_apply_basic c on (p.basicId=c.id) where c.isTemp=0 and p.amountAll>0 and ((c.state in (4, 7) and c.ExaStatus = '完成') or (c.state in (5, 6, 8))) ",
	"equ_progress"=>"select p.id,p.basicId,c.hwapplyNumb, c.ExaStatus,c.state,p.dateIssued ,p.productId,p.taskEquId ,p.inquiryEquId ,p.dateHope,cast(p.amountAll as decimal(10,0)) as amountAll,p.amountIssued,p.moneyAll
						from oa_purch_apply_equ p
						 left join oa_purch_apply_basic c on (p.basicId=c.id) where c.isTemp=0 and p.amountAll>0 ",
	"equ_onway"=>"select cast(if(SUM(p.amountAll-p.amountIssued) is null,0,SUM(p.amountAll-p.amountIssued)) as decimal (10, 0)) as onWayAmount
						from oa_purch_apply_equ p left join oa_purch_apply_basic c on c.id=p.basicId
						where c.isTemp=0 and c.state=7 and c.ExaStatus='完成'"
);

$condition_arr = array (
	//根据申请单Id
	array(
		"name" => "basicId",
		"sql" => " and p.basicId=# "
	),
	array(
		"name" => "equNotIn",
		"sql" => " and p.id not in(arr) "
	),
	array(
		"name" => "orderId",
		"sql" => " and ae.basicId=# "
	),
	array(
		"name" => "ieId",
		"sql" => " and ie.id=# "
	),
	//主业务对象编号
	array(
		"name" => "basicNumb",
		"sql" => " and p.basicNumb=# "
	),
	array(
		"name" => "isTemp",
		"sql" => " and p.isTemp=# "
	),
	array(
		"name" => "purchType",
		"sql" => " and p.purchType=# "
	),
	array(
		"name" => "purchTypeArr",
		"sql" => " and p.purchType in(arr) "
	),
	array(
		"name" => "productNumb",
		"sql" => " and p.productNumb=# "
	),
	array(
		"name" => "productName",
		"sql" => " and p.productName=# "
	),
	array(
		"name" => "orderDate",
		"sql" => " and c.createTime<# "
	),
	array(
		"name" => "taskEquId",
		"sql" => " and p.taskEquId=# "
	),
	array(
		"name" => "productId",
		"sql" => " and p.productId=# "
	),
	array(
		"name" => "inquiryEquId",
		"sql" => " and p.inquiryEquId=# "
	)
);
?>