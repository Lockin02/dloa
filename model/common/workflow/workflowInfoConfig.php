<?php
/*
 * Created on 2012-4-12
 * Created by kuangzw
 * 工作流扩展信息配置
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

/**
 * 邮件信息配置说明
 * 1.二维数组中的key值一定是需要查询的表名,不是表名的话我也不知道拿什么给你对应了
 *
 * @param1 thisCode是需要查询的字段，还是c.开头吧
 * @param2 thisInfo是需要扩展到邮件里面的内容，跟sql查询出来的字段对应，没有对应结果估计得报错，想要换行用<br/>
 *
 * @param3 objArr 选择配属，说明如下
 * 选配字段：-- 准确的来说就是将业务表中对应的字段设置到工作流表内
 * objCode 业务编号
 * objName 业务名称
 * objCustomer 业务客户
 * objAmount 业务金额
 * 注：
 * 1.设置字段时，不要带前缀，否则拿不到
 * 2.以上字段，需要在thisCode中存在
 */
$workflowInfoConfig = array(
	'oa_finance_invoiceapply' => array(//开票申请审批添加扩展字段
		'thisCode' => 'c.applyNo,c.customerName,c.invoiceMoney',
		'thisInfo' => '申请单号: $applyNo , 开票单位 : $customerName , 开票金额 : $invoiceMoney ',
		'objArr' => array(
			'objCode' => 'applyNo',
			'objCustomer' => 'customerName',
			'objAmount' => 'invoiceMoney'
		)
	),
	'oa_finance_payablesapply' => array(//付款申请审批添加扩展字段
		'thisCode' => 'c.id,c.formNo,c.remark,c.supplierName,c.payMoney',
		//ID2209在摘要中添加联表字段解决方法 2016-11-24 haojin
		'joinTable' => array(
			'joinSql' => 'SELECT p.id,p.payapplyId,p.expand2 AS purchType,a.id AS mainId from oa_finance_payablesapply_detail p LEFT JOIN oa_purch_apply_basic a ON p.objCode = a.hwapplyNumb ',
			'joinSqlForProjectNo' => 'select p.projectCode,p.projectName,money as objAmount from oa_finance_payablesapply_detail d left join oa_esm_project p on d.expand3 = p.id where expand1 = "工程项目" and payapplyid = ',
			'action' => 'searchForPurchType'
		),
		'thisInfo' => '付款请单据id : $id , 申请单号: $formNo , 款项用途 : $remark , 付款单位 : $supplierName , 申请金额 ：$payMoney',
		'objArr' => array(
			'objCode' => 'formNo',
			'objCustomer' => 'supplierName',
			'objAmount' => 'payMoney'
		)
	),
	'oa_sale_outsourcing' => array(//外包合同审批添加扩展字段
		'thisCode' => 'c.orderCode,c.orderName,c.signCompanyName,c.orderMoney',
		'thisInfo' => '合同号: $orderCode , 合同名称 : $orderName , 签约公司 : $signCompanyName , 合同金额 : $orderMoney ',
		'joinTable' => array(
			'joinSqlForProjectNo' => 'SELECT if(projectCode is null,"",projectCode) as projectCode,if(projectName is null,"",projectName) as projectName,orderMoney as objAmount FROM oa_sale_outsourcing WHERE ID = ',
			'action' => 'oa_sale_outsourcing'
		),
		'objArr' => array(
			'objCode' => 'orderCode',
			'objName' => 'orderName',
			'objCustomer' => 'signCompanyName',
			'objAmount' => 'orderMoney'
		)
	),
	'oa_sale_other' => array(//合同审批添加扩展字段
		'thisCode' => 'c.orderCode,c.orderName,c.signCompanyName,c.orderMoney',
		'thisInfo' => '合同号: $orderCode , 合同名称 : $orderName , 签约公司 : $signCompanyName , 合同金额 : $orderMoney ',
		'objArr' => array(
			'objCode' => 'orderCode',
			'objName' => 'orderName',
			'objCustomer' => 'signCompanyName',
			'objAmount' => 'orderMoney'
		)
	),
	'oa_hr_trialdeptsuggest' => array(//部门建议
		'thisCode' => 'c.userName,c.deptName,c.deptSuggestName,c.suggestion',
		'thisInfo' => '员工姓名: $userName , 所属部门 : $deptName , 部门建议 : $deptSuggestName , 建议描述 : $suggestion '
	),
	'cost_summary_list' => array(//报销审批
		'thisCode' => 'c.BillNo,c.Amount,c.CostManName,c.CostMan,if((c.projectNo is not null and c.projectNo <> ""),concat("/",c.projectNo),"") as projectNo
		,if((c.projectName is not null and c.projectName <> ""),concat("/",c.projectName),"") as projectName,case when c.DetailType = 1 then "部门费用"  when c.DetailType = 2 then "合同项目费用"  when c.DetailType = 3 then "研发费用"  when c.DetailType = 4 then "售前费用" when c.DetailType = 5 then "售后费用"  ELSE "工程报销" END as DetailTypeName',
		'thisInfo' => '$DetailTypeName$projectNo$projectName/$BillNo/$Amount元',//'报销单号: $BillNo , 报销金额 : $Amount , 报销人 : $CostManName ',
		'joinTable' => array(
			'joinSql' => 'select id,ProjectNo,projectName from cost_summary_list where DetailType in (2,3) and id = ',
			'joinSqlForProjectNo' => 'select id,ProjectNo,projectName,proProvince from cost_summary_list where id = ',
			'action' => 'searchForCostInfo'
		),
		'objArr' => array(
			'objCode' => 'BillNo',
			'objAmount' => 'Amount',
			'objUser'=>'CostMan',
			'objUserName'=>'CostManName'
		)
	),
	'oa_esm_project' => array(//工程项目
		'thisCode' => 'c.projectCode,c.projectName,c.managerName',
		'thisInfo' => '项目编号: $projectCode , 项目名称 : $projectName ,项目经理：$managerName ',
		'joinTable' => array(
			'joinSqlForProjectNo' => 'select projectCode,projectName,"" as objAmount from oa_esm_project where id = ',
			'action' => 'oa_esm_project'
		),
		'objArr' => array(
			'objCode' => 'projectCode',
			'objName' => 'projectName'
		)
	),
	'oa_esm_change_baseinfo' => array(//工程项目变更
		'thisCode' => 'c.projectCode,c.projectName,c.changeDescription',
		'thisInfo' => '项目编号: $projectCode , 项目名称 : $projectName ,变更说明：$changeDescription ',
		'joinTable' => array(
			'joinSqlForProjectNo' => 'select projectCode,projectName,"" as objAmount from oa_esm_change_baseinfo where id = ',
			'action' => 'oa_esm_change_baseinfo'
		),
		'objArr' => array(
			'objCode' => 'projectCode',
			'objName' => 'projectName'
		)
	),
	'oa_esm_project_close' => array(//工程项目关闭
		'thisCode' => '*',
		'joinTable' => array(
			'joinSqlForProjectNo' => 'select projectCode,projectName,"" as objAmount from oa_esm_project_close where id = ',
			'action' => 'oa_esm_project_close'
		)
	),
	'oa_esm_project_statusreport' => array(//工程项目周报
		'thisCode' => '*',
		'joinTable' => array(
			'joinSqlForProjectNo' => 'select projectCode,projectName,"" as objAmount from oa_esm_project_statusreport where id = ',
			'action' => 'oa_esm_project_statusreport'
		)
	),
	'oa_contract_contract' => array(//合同审批
		'thisCode' => 'c.contractCode,c.contractName,c.customerName,c.contractMoney',
		'thisInfo' => '合同编号: $contractCode , 合同名称 : $contractName',
		'objArr' => array(
			'objCode' => 'contractCode',
			'objName' => 'contractName',
			'objCustomer' => 'customerName',
			'objAmount' => 'contractMoney'
		)
	),
	'oa_borrow_borrow' => array(//合同审批
		'thisCode' => 'c.Code , c.createName',
		'thisInfo' => '借用单号: $Code , 借用人 : $createName',
		'objArr' => array(
			'objCode' => 'Code'
		)
	),
	'oa_flights_require'=>array(//机票订票需求审批
		'thisCode' => 'c.requireName,c.requireTime,c.startPlace,c.endPlace,c.startDate,c.airName',
		'thisInfo' => '&nbsp;&nbsp;申请人 ：$requireName, 申请日期 ：$requireTime, 出发日期 ：$startDate ,出发城市 ：$startPlace ,到达城市 ：$endPlace<br><br>&nbsp;&nbsp;&nbsp;&nbsp;'
	),
	'oa_finance_compensate'=>array(//赔偿单审批
		'thisCode' => 'c.formCode,c.formDate',
		'thisInfo' => '单据号 ：$formCode, 单据日期:$formDate',
		'objArr' => array(
			'objCode' => 'formCode'
		)
	),
	'oa_general_specialapply'=>array(//特别事项申请
		'thisCode' => 'c.applyUserName,c.applyDate,c.formNo',
		'thisInfo' => '申请人 ：$applyUserName, 申请日期:$applyDate, 单号:$formNo ',
		'joinTable' => array(
			'joinSql' => 'select id,projectCode,projectName from oa_general_specialcostbelong where detailType in (2,3) and mainId = ',
			'joinSqlForProjectNo' => 'select if(projectCode is null,"",projectCode) as projectCode, if(projectName is null,"",projectName) as projectName,"" as objAmount from oa_general_specialcostbelong where mainId = ',
			'action' => 'searchForSpecialapplyInfo'
		),
		'objArr' => array(
			'objCode' => 'formNo'
		)
	),
	'oa_hr_leave'=>array( //离职申请审批
		'thisCode' => 'c.leaveCode,c.userNo,c.userName,c.deptName,c.jobName',
		'thisInfo' => '单据编号 ：$leaveCode, 员工编号 :$userNo, 员工姓名 :$userName, 部门 :$deptName, 职位 :$jobName',
		'objArr' => array(
			'objCode' => 'leaveCode'
		)
	),
	'oa_hr_worktime_apply'=>array( //节假日加班申请审批
		'thisCode' => 'c.applyCode,c.userNo,c.userName,c.deptName,c.jobName',
		'thisInfo' => '单据编号 ：$applyCode, 员工编号 :$userNo, 员工姓名 :$userName, 部门 :$deptName, 职位 :$jobName',
		'objArr' => array(
			'objCode' => 'applyCode'
		)
	),
	'oa_outsourcing_allregister'=>array( //租车登记审批
		'thisCode' => '*',
		'joinTable' => array(
			'joinSqlForProjectNo' => 'select if(projectCode is null,"",projectCode) as projectCode, if(projectName is null,"",projectName) as projectName,"" as objAmount from oa_outsourcing_allregister where id = ',
			'action' => 'oa_outsourcing_allregister'
		),
	),
	'oa_outsourcing_rentalcar'=>array( // 租车申请审批
		'thisCode' => '*',
		'joinTable' => array(
			'joinSqlForProjectNo' => 'select if(projectCode is null,"",projectCode) as projectCode, if(projectName is null,"",projectName) as projectName,"" as objAmount from oa_outsourcing_rentalcar where id = ',
			'action' => 'oa_outsourcing_rentalcar'
		),
	)
);

?>
