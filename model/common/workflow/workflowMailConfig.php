<?php
/*
 * Created on 2012-4-12
 * Created by kuangzw
 * 工作流审批完成配置文件
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

//要查看效果的,把isEdit和付款申请里面的detailSet 改为1,做完别忘记改回来,否则要请吃饭
//测试模式,1的时候不提交工作流
$isEdit = 0;

/**
 * 邮件信息配置说明
 * 1.二维数组中的key值一定是需要查询的表名,不是表名的话我也不知道拿什么给你对应了
 *
 * @param1 thisCode是需要查询的字段，一定是c.开头，报错不要找我
 * @param2 thisInfo是需要扩展到邮件里面的内容，跟sql查询出来的字段对应，没有对应结果估计得报错，想要换行用<br/>
 * @param3 detailSet 是否需要做详细设置
 * @param4 actFunc 详细设置的方法在workflowMailInit.php 这个文件里
 */


$workflowMailConfig = array(
	'oa_finance_invoiceapply' => array(//开票申请审批添加扩展字段
		'thisCode' => 'c.applyNo,c.customerName,c.invoiceMoney',
		'thisInfo' => '申请单号: $applyNo , 开票单位 : $customerName , 开票金额 : $invoiceMoney '
	),

	'oa_finance_payablesapply' => array(//付款申请审批添加扩展字段
		'thisCode' => 'c.id,c.formNo,c.formDate,c.supplierName,c.payMoney',
		'thisInfo' => '付款请单据id : $id , 申请单号: $formNo , 单据日期 : $formDate , 付款单位 : $supplierName , ' .
			'源单编号：$sourceCode ，申请金额 ：$payMoney ' .
			'<br>&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:red">单据已完成审批，如单据不是委托付款，请将付款申请提交到财务进行支付。</span>',
		'detailSet' => 1,
		'selectSql' => 'select c.id,c.formNo,c.formDate,c.supplierName,c.payMoney,d.objCode,d.objType,d.money,d.productNo,d.productName
			from oa_finance_payablesapply c inner join oa_finance_payablesapply_detail d on c.id = d.payapplyId where 1=1 ',
		'actFunc' => 'payablesapplyFun'
	),
	'oa_sale_stampapply' => array(//盖章申请审批添加扩展字段
		'thisCode' =>'c.fileName,c.signCompanyName,c.stampType,c.stampExecutionName,c.useMatters,c.remark',
		'thisInfo' => '盖章文件名: $fileName , <br/>&nbsp;&nbsp;&nbsp;&nbsp;文件发送单位: $signCompanyName , <br/>&nbsp;&nbsp;&nbsp;&nbsp;盖章类型:$stampType ,<br/> &nbsp;&nbsp;&nbsp;&nbsp;盖章性质:$stampExecutionName , <br/>&nbsp;&nbsp;&nbsp;&nbsp;使用事项: $useMatters , <br/>&nbsp;&nbsp;&nbsp;&nbsp;说明: $remark'
	),
	'oa_sale_outsourcing' => array(//外包合同审批添加扩展字段
		'thisCode' => 'c.orderCode,c.orderName,c.signCompanyName,c.orderMoney',
		'thisInfo' => '合同号: $orderCode , 合同名称 : $orderName , 签约公司 : $signCompanyName , 合同金额 : $orderMoney '
	),
	'oa_sale_other' => array(//合同审批添加扩展字段
		'thisCode' => 'c.orderCode,c.orderName,c.signCompanyName,c.orderMoney',
		'thisInfo' => '合同号: $orderCode , 合同名称 : $orderName , 签约公司 : $signCompanyName , 合同金额 : $orderMoney '
	),
	'oa_purch_inquiry' => array(//采购询价添加扩展字段
		'thisCode' => 'c.inquiryCode',
		'thisInfo' => '询价单号: $inquiryCode ',
		'detailSet' =>1,
		'selectSql' => 'select p.parentId,c.id,c.inquiryCode,p.productNumb,p.productName,p.amount
			 			from oa_purch_inquiry_equ p left join oa_purch_inquiry c on c.id = p.parentId where 1=1 ',
		'actFunc' => 'inquiryFun'
	),
	'oa_purch_apply_basic' => array(//采购订单添加扩展字段
		'thisCode' => 'c.hwapplyNumb',
		'thisInfo' => '订单编号: $hwapplyNumb ',
		'detailSet' =>1,
		'selectSql' => 'select p.basicId,c.id,c.hwapplyNumb,p.productNumb,p.productName,p.pattem,p.units,p.amountAll,p.dateIssued,p.dateHope,p.applyDeptName,p.sourceNumb,p.purchType
			 			from oa_purch_apply_equ p left join oa_purch_apply_basic c on c.id = p.basicId where 1=1 ',
		'actFunc' => 'purchaseontractFun'
	),
	'oa_hr_recommend_bonus' => array(//内部推荐奖励添加扩展字段
		'thisCode' => 'c.formCode',
		'thisInfo' => '内部推荐奖励: $formCode ',
		'detailSet' =>1,
		'selectSql' => 'select c.id ,c.formCode ,c.formDate ,c.formManId ,c.formManName ,c.isRecommendName ,c.resumeId ,c.resumeCode ,c.positionId ,c.positionName ,c.developPositionId
		 ,c.developPositionName ,c.job ,c.jobName ,c.entryDate ,c.becomeDate ,c.beBecomDate ,c.recommendName ,c.recommendId ,c.recommendReason ,c.state ,c.isBonus ,c.bonus ,c.bonusProprotion
		 ,c.firstGrantDate ,c.firstGrantBonus ,c.secondGrantDate ,c.secondGrantBonus ,c.remark
		 from oa_hr_recommend_bonus c where 1=1 ',
		'actFunc' => 'recommendbonusFun'
	),
	'oa_hr_personnel_transfer' => array(//员工调岗添加扩展字段
		'thisCode' => 'c.formCode,c.applyDate,c.userName,c.preDeptNameS,c.afterDeptNameS,c.preDeptNameT,c.afterDeptNameT,c.preJobName,c.afterJobName',
		'thisInfo' => '&nbsp;&nbsp;申请单号: $formCode, <br/> &nbsp;&nbsp;申请日期 ：$applyDate, <br/> &nbsp;&nbsp;调岗人员姓名 :$userName ,<br/> &nbsp;&nbsp;调动前二级部门:$preDeptNameS , <br/> &nbsp;&nbsp;调动后二级部门: $afterDeptNameS ,<br/> &nbsp;&nbsp;调动前三级部门:$preDeptNameT , <br/> &nbsp;&nbsp;调动后三级部门: $afterDeptNameT , <br/> &nbsp;&nbsp;调动前职位 :$preJobName, <br/> &nbsp;&nbsp;调动后职位 :$afterJobName,'

	),
	'oa_hr_personnel_certifyapply' => array(//任职资格申请
		'thisCode' => 'c.applyDate,c.careerDirectionName,c.baseLevelName,c.baseGradeName',
		'thisInfo' => '&nbsp;&nbsp;申请日期：$applyDate, 申请职业发展通道: $careerDirectionName, 申请级别 ：$baseLevelName, 申请级等 :$baseGradeName '
	),
	'oa_hr_certifyapplyassess' => array(//任职资格申请
		'thisCode' => 'c.careerDirectionName,c.baseLevelName,c.baseGradeName',
		'thisInfo' => '&nbsp;&nbsp;申请职业发展通道: $careerDirectionName, 申请级别 ：$baseLevelName, 申请级等 :$baseGradeName <br><br>&nbsp;&nbsp;&nbsp;&nbsp;<font color="red">tips:如果单据审批不通过，请重新修改后提交审批</font>'
	),
	'oa_contract_contract' => array(//合同审批
		'thisCode' => 'c.contractCode,c.contractName,c.prinvipalName',
		'thisInfo' => '&nbsp;&nbsp;合同编号: $contractCode, 合同名称 ：$contractName, 合同负责人 :$prinvipalName <br><br>&nbsp;&nbsp;&nbsp;&nbsp;<font color="red">tips:如果单据审批不通过，请重新修改后提交审批</font>'
	),
	'oa_asset_allocation' => array(//资产调拨审批
		'thisCode' => 'c.billNo',
		'thisInfo' => '&nbsp;&nbsp;单据编号: $billNo',
		'detailSet' =>0,
	),
	'oa_hr_recruitment_apply'=>array(   //增员申请审批
		'thisCode' => 'c.formCode,c.formManName,c.deptName,c.positionName',
		'thisInfo' => '&nbsp;&nbsp;单据编号: $formCode, 填表人 ：$formManName, 需求部门 :$deptName ,需求职位 : $positionName<br><br>&nbsp;&nbsp;&nbsp;&nbsp;<font color="red">tips:如果单据审批不通过，请重新修改后提交审批</font>'
	),
	'oa_flights_require'=>array(//机票订票需求审批
		'detailSet' =>1,
		'selectSql' => 'SELECT c.requireName,c.requireTime,c.startPlace,c.endPlace,c.startDate,s.airName FROM oa_flights_require c 
		LEFT JOIN (SELECT GROUP_CONCAT(DISTINCT(airName)) AS airName,mainId FROM oa_flights_require_suite GROUP BY mainId) s ON s.mainId = c.id
		WHERE 1 = 1 ',
		'actFunc' => 'flightsRequireFun'
	),
	'oa_asset_requirement'=>array(//固定资产需求申请审批
		'thisCode' => 'c.requireCode',
		'thisInfo' => '&nbsp;&nbsp;需求单编号: $requireCode<br><br>&nbsp;&nbsp;&nbsp;&nbsp;'
	),
	'oa_stockup_apply' => array(//内部推荐奖励添加扩展字段
		'thisCode' => 'c.listNo,c.projectName,c.appUserName,c.description',
		'thisInfo' => '&nbsp;&nbsp;申请编号: $listNo, 项目名称 ：$projectName, 申请人 :$appUserName ,说明 : $description',
		'detailSet' =>1,
		'selectSql' => 'select c.id ,c.listNo,c.projectName,c.appUserName,c.description,d.productName ,d.productNum ,d.productConfig ,d.exDeliveryDate
		 from oa_stockup_apply_products d left join oa_stockup_apply c on c.id = d.appId where 1=1 ',
		'actFunc' => 'stockUpProductsFun'
	),
	'oa_hr_worktime_apply'=>array( //节假日加班申请审批
		'thisCode' => 'c.applyCode,c.userNo,c.userName,c.deptName,c.jobName',
		'thisInfo' => '单据编号 ：$applyCode, 员工编号 :$userNo, 员工姓名 :$userName, 部门 :$deptName, 职位 :$jobName',
		'objArr' => array(
			'objCode' => 'applyCode'
		)
	),
	'oa_asset_scrap'=>array(//固定资产报废申请审批
		'thisCode' => 'c.billNo',
		'thisInfo' => '&nbsp;&nbsp;报废单编号:$billNo<br><br>&nbsp;&nbsp;&nbsp;&nbsp;'
	)
);