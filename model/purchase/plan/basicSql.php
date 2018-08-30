<?php
$sql_arr = array (
	"plan_list_page" => "select c.id ,c.planNumb ,c.batchNumb ,c.sourceID ,c.sourceNumb ,c.contractName ,c.isPlan ,c.applyReason ,c.projectName ,c.purchType ," .
			"c.sendUserId ,c.sendName ,c.phone ,c.sendTime ,c.dateHope ,c.dateEnd ,c.instruction ,c.remark ,c.state ," .
			"c.department ,c.departId ,c.purchDepart ,c.purchDepartId ,c.ExaStatus ,c.ExaDT ,c.createId ,c.createName ," .
			"c.createTime ,c.updateId ,c.updateName ,c.updateTime ,c.isTemp,c.originalId,c.isRd ,c.projectId ,c.projectCode ," .
			"c.sureStatus ,c.assetUse,c.isChange,c.productSureStatus,closeRemark  from oa_purch_plan_basic c where c.isTemp=0 and 1=1",
	"plan_list_noAsset" => "select c.id ,c.batchNumb,c.planNumb ,c.sourceID ,c.sourceNumb ,c.contractName ,c.isPlan ,c.applyReason ,c.projectName ,c.purchType ," .
			"c.sendUserId ,c.sendName ,c.phone ,c.sendTime ,c.dateHope ,c.dateEnd ,c.instruction ,c.remark ,c.state ," .
			"c.department ,c.departId ,c.purchDepart ,c.purchDepartId ,c.ExaStatus ,c.ExaDT ,c.createId ,c.createName ," .
			"c.createTime ,c.updateId ,c.updateName ,c.updateTime ,c.isTemp,c.originalId,c.isRd ,c.projectId ,c.projectCode ," .
			"c.sureStatus ,c.assetUse,c.isChange,c.productSureStatus   from oa_purch_plan_basic c where c.isTemp=0 and " .
			"c.id in(select basicId from oa_purch_plan_equ where   isAsset is null or isAsset='') and 1=1",
	"plan_page" =>  "select c.id ,c.planNumb ,c.sourceID ,c.sourceNumb ,c.contractName ,c.isPlan ,c.applyReason ," .
			"c.projectName ,c.purchType ,c.sendUserId ,c.sendName ,c.phone ,c.sendTime ,c.dateHope ,c.dateEnd ," .
			"c.instruction ,c.remark ,c.state ,c.department ,c.departId ,c.purchDepart ,c.purchDepartId ,c.ExaStatus ," .
			"c.ExaDT ,c.isTemp,c.originalId ,c.isChange,c.productSureStatus   from oa_purch_plan_basic c where c.isTemp=0 and 1=1",
	"plan_list_change" => "select c.id ,c.planNumb ,c.sourceID ,c.sourceNumb ,c.contractName ,c.isPlan ,c.applyReason ,c.projectName ,c.purchType ,c.sendUserId ,c.sendName ,c.phone ,c.sendTime ,c.dateHope ,c.dateEnd ,c.instruction ,c.remark ,c.state ,c.department ,c.departId ,c.purchDepart ,c.purchDepartId ,c.ExaStatus ,c.ExaDT ,c.createId ,c.createName ,c.createTime ,c.updateId ,c.updateName ,c.updateTime ,c.isTemp,c.originalId  from oa_purch_plan_basic c where c.isTemp=0 and c.isChange=1 and 1=1",
	"plan_export_list" => "select p.id,p.purchType ,p.basicNumb ,p.basicId ,p.productName ,p.productId ,p.productTypeId,p.productTypeName,p.productNumb ,
							p.pattem ,p.unitName ,p.amountAll ,p.amountIssued ,p.dateIssued ,p.dateHope ,p.dateEnd ,p.remark ,p.status ,c.sourceID ,
							c.sourceNumb ,c.contractName from oa_purch_plan_equ p left join oa_purch_plan_basic c on p.basicId=c.id where c.isTemp=0",
	"plan_list_union"=>"select * from purchase_asset_union c",
	"plan_list_union2"=>"select * from purchase_asset_union2 c",
	"plan_list_union_table"=>"SELECT c.id AS id ,c.planNumb AS planNumb , c.sourceNumb AS sourceNumb ,c.department AS department ,c.purchType AS purchType ,c.sendName AS sendName ,c.productSureStatus AS productSureStatus ,c.productSureUserId AS productSureUserId ,c.sendTime AS sendTime ,c.ExaStatus AS ExaStatus ,c.state AS state ,c.dateEnd AS dateEnd ,c.batchNumb AS batchNumb ,d.id AS equid ,e.id AS itemid FROM oa_purch_plan_basic c
		LEFT JOIN (
			SELECT p.id ,p.applyEquId ,p.purchType ,p.basicNumb ,p.basicId ,p.isAsset ,p.isTask ,p.amountAll ,p.amountIssued
			FROM oa_purch_plan_equ p WHERE p.amountAll > 0 AND p.isPurch = 1
		) d ON ( c.id = d.basicId AND (d.isAsset IS NULL OR d.isAsset = _utf8 '') AND d.amountAll != d.amountIssued)
		LEFT JOIN (
			SELECT i.id, i.applyId FROM oa_asset_purchase_apply_item i
			WHERE i.purchDept = _utf8 '1' AND i.isDel = _utf8 '0'
		) e ON c.id = e.applyId
		WHERE(
			(c.isTemp = 0)
			AND c.id IN (
					SELECT oa_purch_plan_equ.basicId AS basicId FROM oa_purch_plan_equ
					WHERE (isnull(oa_purch_plan_equ.isAsset) OR (oa_purch_plan_equ.isAsset = _gbk ''))
				)
			AND ((c.sureStatus = 1) OR isnull(c.sureStatus) OR (c.sureStatus = _gbk ''))
			AND (c.purchType = '')
		)
	UNION ALL
		SELECT c.id AS id ,c.formCode AS planNumb , _utf8 '' AS sourceNumb ,c.applyDetName AS department ,_utf8 'oa_asset_purchase_apply' AS purchType ,c.applicantName AS sendName ,c.productSureStatus AS productSureStatus ,c.productSureUserId AS productSureUserId ,c.applyTime AS sendTime ,
			c.ExaStatus AS ExaStatus ,c.purchState AS state ,c.dateEnd AS dateEnd ,_utf8 '' AS batchNumb ,d.id AS equid ,e.id AS itemid FROM oa_asset_purchase_apply c
		LEFT JOIN oa_purch_plan_equ d ON (
			d.amountAll > 0 AND d.isPurch = 1 AND c.id = d.basicId
			AND (
				d.isAsset IS NULL
				OR d.isAsset = _utf8 ''
			)
			AND d.amountAll != d.amountIssued
		)
		LEFT JOIN (
			SELECT i.id ,i.applyId FROM oa_asset_purchase_apply_item i
			WHERE i.purchDept = _utf8 '1' AND i.isDel = _utf8 '0'
		) e ON c.id = e.applyId
		WHERE((c.ExaStatus = _utf8 '完成')
				AND (c.state = _utf8 '已提交')
				AND (
					(SELECT count(0) AS `count(0)` FROM oa_asset_purchase_apply_item t
						WHERE((t.applyId = c.id) AND (t.purchDept = 1))
					) > 0
				)
			)",
	//补库/生产审批已审批页面
	"select_approvedList"=>"SELECT c.id ,c.pid ,c.type ,c.updateName ,c.sendTime ,c.productNumb ,c.productName ,c.amountAllOld ,c.amountAll ,c.qualityCode ,c.productId ,c.planNumb ,c.appOpinion ,c.isApp ,c.formBelong ,c.formBelongName ,c.businessBelong ,c.businessBelongName
		from
		(
			(
				select b.id,a.id as pid ,a.updateName ,a.sendTime ,b.productNumb ,b.productName ,b.amountAllOld ,b.amountAll ,b.qualityCode ,b.productId ,a.planNumb ,b.appOpinion ,b.isApp ,a.formBelong ,a.formBelongName ,a.businessBelong ,a.businessBelongName ,
				(case a.purchType
				when 'produce' then '1'
				when 'HTLX-XSHT' then '3'
				when 'HTLX-FWHT' then '4'
				when 'HTLX-ZLHT' then '5'
	 			when 'HTLX-YFHT' then '6'
	 			when 'oa_borrow_borrow' then '7' end) as type
				from  oa_purch_plan_basic a
				left join oa_purch_plan_equ b on b.basicId=a.id
				where b.amountAllOld>0
					and a.ExaStatus='完成'
					and b.isClose='0'
					and (a.purchType = 'produce' or a.purchType like 'HTLX-%' or a.purchType = 'oa_borrow_borrow' )
			)
			union(
				select b.id ,a.id as pid ,'2' as type ,a.updateName ,a.createTime as sendTime ,b.sequence as productNumb ,b.productName ,b.amountAllOld ,b.stockNum as amountAll ,b.qualityCode ,b.productId ,a.fillupCode as planNumb ,b.appOpinion ,b.isApp ,a.formBelong ,a.formBelongName ,a.businessBelong ,a.businessBelongName
				from  oa_stock_fillup a
				left join oa_stock_fillup_detail b on b.fillUpId=a.id
				where a.ExaStatus='完成'
			)
		) c where 1=1 ",
	/*****************************************工作流部分***********************************/
	"sql_examine" => "select " .
	"w.task as taskId,p.ID as spid ,c.id ,c.planNumb ,c.sourceID ,c.sourceNumb ,c.contractName ,c.isPlan ,c.applyReason ,c.projectName ," .
	"c.purchType ,c.sendUserId ,c.sendName ,c.phone ,c.sendTime ,c.dateHope ,c.dateEnd ,c.instruction ,c.remark ,c.state ,c.department ,c.departId ,c.purchDepart ,c.purchDepartId ,c.ExaStatus ,c.ExaDT ,c.createId ,c.createName ," .
	"c.createTime ,c.updateId ,c.updateName ,c.updateTime ,c.isTemp,c.originalId " .
	" from flow_step_partent p left join wf_task w on p.wf_task_id = w.task left join user u on u.USER_ID = p.User ,oa_purch_plan_basic c " .
	" where w.Pid =c.id and w.examines <> 'no' ",
	"sql_audited"=> "select " .
	"w.task as taskId,p.ID as spid ,c.id ,c.planNumb ,c.sourceID ,c.sourceNumb ,c.contractName ,c.isPlan ,c.applyReason ,c.projectName ," .
	"c.purchType ,c.sendUserId ,c.sendName ,c.phone ,c.sendTime ,c.dateHope ,c.dateEnd ,c.instruction ,c.remark ,c.state ,c.department ,c.departId ,c.purchDepart ,c.purchDepartId ,c.ExaStatus ,c.ExaDT ,c.createId ,c.createName ," .
	"c.createTime ,c.updateId ,c.updateName ,c.updateTime ,c.isTemp,c.originalId " .
	" from flow_step_partent p left join wf_task w on p.wf_task_id = w.task left join user u on u.USER_ID = p.User ,oa_purch_plan_basic c " .
	" where p.Flag='1' and w.Pid =c.id ",
	"select_batchnumb"=>"select c.id,c.batchNumb  from oa_purch_plan_basic c where c.isTemp=0 and c.batchNumb!=''",
	"select_appList"=>"select c.id,c.batchNumb  from oa_purch_plan_basic c where c.isTemp=0 and c.batchNumb!=''",
	"select_stockNumbTotal"=>"select p.id,c.hwapplyNumb,p.amountIssued from oa_purch_apply_equ p left join oa_purch_apply_basic c on (p.basicId = c.id)
							left join (select b.id,b.planEquId,d.sendUserId from oa_purch_task_equ b left join oa_purch_task_basic d on d.id = b.basicId where
							b.amountAll > 0) s on s.id = p.taskEquId where c.isTemp = 0 and p.amountAll > 0 and (
							(c.state in (4, 7) and c.ExaStatus = '完成')	or (c.state in(5, 6, 8))) "
);
$condition_arr = array (
	array (
		"name" => "seachPlanNumb",
		"sql" => " and c.planNumb like CONCAT('%',#,'%')"
	),
	array (
		"name" => "batchNumb",
		"sql" => " and c.batchNumb like CONCAT('%',#,'%')"
	),
	array (
		"name" => "batchNumbUnion",
		"sql" => " and batchNumb like CONCAT('%',#,'%')"
	),
	array (
		"name" => "department",
		"sql" => " and c.department like CONCAT('%',#,'%')"
	),
	array (
		"name" => "departmentUnion",
		"sql" => " and department like CONCAT('%',#,'%')"
	),
	array (
		"name" => "isTemp",
		"sql" => " and c.isTemp =#"
	),
	array (
		"name" => "sendName",
		"sql" => " and c.sendName like CONCAT('%',#,'%')"
	),
	array (
		"name" => "sendNameUnion",
		"sql" => " and sendName like CONCAT('%',#,'%')"
	),
	array (
		"name" => "createName",
		"sql" => " and c.createName like CONCAT('%',#,'%')"
	),
	array (
		"name" => "sourceNumb",
		"sql" => " and c.sourceNumb like CONCAT('%',#,'%')"
	),
	array (
		"name" => "sourceNumbUnion",
		"sql" => " and sourceNumb like CONCAT('%',#,'%')"
	),
	array (
		"name" => "planNumb",
		"sql" => " and c.planNumb =# "
	),
	array (
		"name" => "planNumbUnion",
		"sql" => " and planNumb like CONCAT('%',#,'%') "
	),
	array(
		"name" => "selectEqu",
		"sql" => " and c.id in(arr)",
	),
	array(
		"name" => "isUse",
		"sql" => " and c.isUse=# "
	),
	array(
		"name" => "state",
		"sql" => " and c.state=# "
	),
	array(
		"name" => "stateInArr",
		"sql" => " and c.state in(arr) "
	),
	array(
		"name" => "purchTypeArr",
		"sql" => " and c.purchType in(arr) "
	),
	array(
		"name" => "purchTypeArrUnion",
		"sql" => " and purchType in(arr) "
	),
	array(
		"name" => "id",
		"sql" => " and c.id=# "
	),
	array(
		"name" => "updateIds",
		"sql" => " and c.id in(arr) "
	),
	array(
		"name" => "createId",
		"sql" => " and c.createId=# "
	),
	array(
		"name" => "sourceID",
		"sql" => " and c.sourceID=# "
	),
	array(
		"name" => "purchType",
		"sql" => " and c.purchType=# "
	),
	array(
		"name" => "ExaStatus",
		"sql" => " and c.ExaStatus=# "
	),
	array(
		"name" => "ExaStatusArr",
		"sql" => " and c.ExaStatus in(arr) "
	),
	array(
		"name" => "stateUnionArr",
		"sql" => " and state in(arr) "
	),
	array(
		"name" => "ExaStatusUnionArr",
		"sql" => " and ExaStatus in(arr) "
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
	array(
		"name" => "productNumb",
		"sql" => "and c.id in(select basicId from oa_purch_plan_equ where productNumb like CONCAT('%',#,'%'))"
	),
	array(
		"name" => "productNumbUnion",
		"sql" => "and (id in(select basicId from oa_purch_plan_equ where oa_purch_plan_equ.productNumb like CONCAT('%',#,'%')) or id in(select applyId from oa_asset_purchase_apply_item where oa_asset_purchase_apply_item.productCode like CONCAT('%',#,'%')))"
	),
	array(
		"name" => "productName",
		"sql" => "and c.id in(select basicId from oa_purch_plan_equ where productName like CONCAT('%',#,'%'))"
	),
	array(
		"name" => "productNameUnion",
		"sql" => "and (id in(select basicId from oa_purch_plan_equ where oa_purch_plan_equ.productName like CONCAT('%',#,'%')) or id in(select applyId from oa_asset_purchase_apply_item where oa_asset_purchase_apply_item.productName like CONCAT('%',#,'%')))"
	),
	array(
		"name" => "preDateHope",
		"sql" => " and c.sendTime >= #"
	),
	array(
		"name" => "afterDateHope",
		"sql" => " and c.sendTime <= #"
	),
	array(
		"name" => "sureStatusNo",
		"sql" => " and (c.sureStatus = 1 or c.sureStatus is null or c.sureStatus='')"
	),
	array (
			"name" => "productSureUserId",
			"sql" => " and c.productSureUserId= # "
	),
	array (
			"name" => "productSureUserIdUnion",
			"sql" => " and productSureUserId= # "
	),
	array (
			"name" => "productSureStatus",
			"sql" => " and c.productSureStatus= # "
	),
	array (
			"name" => "productSureStatusArr",
			"sql" => " and productSureStatus in(arr) "
	),
	array (
			"name" => "planEquId",
			"sql" => " and s.planEquId= # "
	),
	array (
			"name" => "type",
			"sql" => " and c.type= # "
	),
	array (
			"name" => "planNumbS",
			"sql" => " and c.planNumb like CONCAT('%',#,'%') "
	),
	array (
			"name" => "productNumbS",
			"sql" => " and c.productNumb like CONCAT('%',#,'%') "
	),
	array (
			"name" => "productNameS",
			"sql" => " and c.productName like CONCAT('%',#,'%') "
	),
	array (
			"name" => "updateNameS",
			"sql" => " and c.updateName like CONCAT('%',#,'%') "
	),
	array (
			"name" => "allS",
			"sql" => " and (c.planNumb like CONCAT('%',#,'%') or c.productNumb like CONCAT('%',#,'%') or c.productName like CONCAT('%',#,'%') or c.updateName like CONCAT('%',#,'%')) "
	),
	array (
			"name" => "isAppS",
			"sql" => " and (c.isApp= # or (c.amountAllOld-c.amountAll)>=0) "
	),
	array (
			"name" => "ids",
			"sql" => " and id in(arr) "
	)
);
?>
