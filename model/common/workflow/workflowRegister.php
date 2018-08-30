<?php
/**
 * 普通审批业务及其加密信息
 * url          审批查看页面
 * viewUrl      列表查看页面
 * isSkey     是否加密
 * keyObj      加密对象
 * rtUrl      返回调用路径
 * isChange   是否是变更流程
 * changeCode 对应变更编码
 * allStep    配置每一步审批都回调处理路径
 * search     变更配置使用sql配置码
 * orgCode    实际使用的工作流名称
 */
$urlArr = array(
	/********************************新合同管理审批注册*********************************************************************/
	'合同审批TA' => array( //新合同审批
		'url' => '?model=contract_contract_contract&action=init&actType=audit&perm=view&actType=audit&id=', //审批时页面查看路径
		'viewUrl' => '?model=contract_contract_contract&action=init&perm=view&id=', //列表页面查看路径
		'rtUrl' => '?model=contract_contract_contract&action=configContract&spid='
	),
	'合同审批TB' => array( //新合同审批
		'url' => '?model=contract_contract_contract&action=init&actType=audit&perm=view&actType=audit&id=', //审批时页面查看路径
		'viewUrl' => '?model=contract_contract_contract&action=init&perm=view&id=', //列表页面查看路径
		'rtUrl' => '?model=contract_contract_contract&action=configContract&spid='
	),
	'合同审批A' => array( //新合同审批
		'url' => '?model=contract_contract_contract&action=init&actType=audit&perm=view&actType=audit&id=', //审批时页面查看路径
		'viewUrl' => '?model=contract_contract_contract&action=init&perm=view&id=', //列表页面查看路径
		'rtUrl' => '?model=contract_contract_contract&action=configContract&spid='
	),
	'零配件合同审批' => array( //新合同审批
		'url' => '?model=contract_contract_contract&action=init&actType=audit&perm=view&actType=audit&id=', //审批时页面查看路径
		'viewUrl' => '?model=contract_contract_contract&action=init&perm=view&id=', //列表页面查看路径
		'rtUrl' => '?model=contract_contract_contract&action=configContract&spid='
	),
	'合同审批B' => array( //新合同审批
		'url' => '?model=contract_contract_contract&action=init&actType=audit&perm=view&actType=audit&id=', //审批时页面查看路径
		'viewUrl' => '?model=contract_contract_contract&action=init&perm=view&id=', //列表页面查看路径
		'rtUrl' => '?model=contract_contract_contract&action=configContract&spid='
	),
	'合同变更审批A' => array( //
		'url' => '?model=contract_contract_contract&action=showView&id=', //审批时页面查看路径
		'viewUrl' => '?model=contract_contract_contract&action=init&perm=view&id=', //列表页面查看路径
		'rtUrl' => '?model=contract_contract_contract&action=confirmChangeToApprovalNo&spid='
	),
	'零配件合同变更审批' => array( //
		'url' => '?model=contract_contract_contract&action=showView&id=', //审批时页面查看路径
		'viewUrl' => '?model=contract_contract_contract&action=init&perm=view&id=', //列表页面查看路径
		'rtUrl' => '?model=contract_contract_contract&action=confirmChangeToApprovalNo&spid='
	),
	'合同变更审批B' => array( //
		'url' => '?model=contract_contract_contract&action=showView&id=', //审批时页面查看路径
		'viewUrl' => '?model=contract_contract_contract&action=init&perm=view&id=', //列表页面查看路径
		'rtUrl' => '?model=contract_contract_contract&action=confirmChangeToApprovalNo&spid='
	),
	'合同删除审批' => array( //新合同审批
		'url' => '?model=contract_contract_contract&action=init&actType=audit&perm=view&actType=audit&id=', //审批时页面查看路径
		'viewUrl' => '?model=contract_contract_contract&action=init&perm=view&id=' //列表页面查看路径
	),
	'新合同物料确认审批' => array( //新合同审批
		'url' => '?model=contract_contract_equ&action=toEquView&linkId=', //审批时页面查看路径
		'viewUrl' => '?model=contract_contract_equ&action=toEquView&linkId=', //列表页面查看路径
		'rtUrl' => '?model=contract_contract_contequlink&action=confirmAudit&spid='
	),
	'新合同物料确认变更审批' => array( //新合同审批
		'url' => '?model=contract_contract_equ&action=toEquView&changeView=1&linkId=', //审批时页面查看路径
		'viewUrl' => '?model=contract_contract_equ&action=toEquView&changeView=1&linkId=', //列表页面查看路径
		'rtUrl' => '?model=contract_contract_contequlink&action=confirmChange&spid='
	),
	'借试用物料确认审批' => array( //借试用审批
		'url' => '?model=projectmanagent_borrow_borrowequ&action=toEquView&linkId=', //审批时页面查看路径
		'viewUrl' => '?model=projectmanagent_borrow_borrowequ&action=toEquView&&perm=view&linkId=', //列表页面查看路径
		'rtUrl' => '?model=projectmanagent_borrow_borrowequlink&action=confirmAudit&spid='
	),
	'借试用物料确认变更审批' => array( //借试用审批
		'url' => '?model=projectmanagent_borrow_borrowequ&action=toEquView&changeView=1&linkId=', //审批时页面查看路径
		'viewUrl' => '?model=projectmanagent_borrow_borrowequ&action=toEquView&&perm=view&linkId=', //列表页面查看路径
		'rtUrl' => '?model=projectmanagent_borrow_borrowequlink&action=confirmChange&spid='
	),
	'赠送物料确认审批' => array( //赠送审批
		'url' => '?model=projectmanagent_present_presentequ&action=toEquView&linkId=', //审批时页面查看路径
		'viewUrl' => '?model=projectmanagent_present_presentequ&action=toEquView&&perm=view&linkId=', //列表页面查看路径
		'rtUrl' => '?model=projectmanagent_present_presentequlink&action=confirmAudit&spid='
	),
	'赠送物料确认变更审批' => array( //赠送审批
		'url' => '?model=projectmanagent_present_presentequ&action=toEquView&changeView=1&linkId=', //审批时页面查看路径
		'viewUrl' => '?model=projectmanagent_present_presentequ&action=toEquView&&perm=view&linkId=', //列表页面查看路径
		'rtUrl' => '?model=projectmanagent_present_presentequlink&action=confirmChange&spid='
	),
	'换货物料确认审批' => array( //退货审批
		'url' => '?model=projectmanagent_exchange_exchangeequ&action=toEquView&linkId=', //审批时页面查看路径
		'viewUrl' => '?model=projectmanagent_exchange_exchangeequ&action=toEquView&&perm=view&linkId=', //列表页面查看路径
		'rtUrl' => '?model=projectmanagent_exchange_exchangeequlink&action=confirmAudit&spid='
	),
	'换货物料确认变更审批' => array( //退货审批
		'url' => '?model=projectmanagent_exchange_exchange&action=toEquView&changeView=1&linkId=', //审批时页面查看路径
		'viewUrl' => '?model=projectmanagent_exchange_exchangeequ&action=toEquView&&perm=view&linkId=', //列表页面查看路径
		'rtUrl' => '?model=projectmanagent_exchange_exchangeequlink&action=confirmChange&spid='
	),
	'合同异常关闭' => array( //
		'url' => '?model=contract_contract_contract&action=init&actType=audit&perm=view&closeType=close&id=', //审批时页面查看路径
		'viewUrl' => '?model=contract_contract_contract&action=init&perm=view&id=', //列表页面查看路径
		'rtUrl' => '?model=contract_contract_contract&action=confirmCloseApprovalNo&spid='
	),
	'合同变更审批' => array( //
		'url' => '?model=contract_contract_contract&action=showView&id=', //审批时页面查看路径
		'viewUrl' => '?model=contract_contract_contract&action=init&perm=view&id=', //列表页面查看路径
		'rtUrl' => '?model=contract_contract_contract&action=confirmChangeToApprovalNo&spid='
	),
	'销售退货申请' => array(
		'url' => '?model=projectmanagent_return_return&action=init&perm=view&id=', //审批时页面查看路径
		'viewUrl' => '?model=projectmanagent_return_return&action=init&perm=view&id=', //列表页面查看路径
		'rtUrl' => '?model=projectmanagent_return_return&action=confirmReturn&spid='
	),
	'换货申请审批' => array(
		'url' => '?model=projectmanagent_exchange_exchange&action=init&perm=view&id=', //审批时页面查看路径
		'viewUrl' => '?model=projectmanagent_exchange_exchange&action=init&perm=view&id=', //列表页面查看路径
		'rtUrl' => '?model=projectmanagent_exchange_exchange&action=confirmExchange&spid='
	),
	'扣款申请审批' => array(
		'url' => '?model=contract_deduct_deduct&action=init&perm=view&id=', //审批时页面查看路径
		'viewUrl' => '?model=contract_deduct_deduct&action=init&perm=view&id=', //列表页面查看路径
		'rtUrl' => '?model=contract_deduct_deduct&action=confirmDeduct&spid='
	),
	'试用项目申请' => array(
		'url' => '?model=projectmanagent_trialproject_trialproject&action=init&perm=view&id=', //审批时页面查看路径
		'viewUrl' => '?model=projectmanagent_trialproject_trialproject&action=init&perm=view&id=', //列表页面查看路径
		//		'rtUrl' => '?model=projectmanagent_trialproject_trialproject&action=confirmDeduct&spid='
	),
	'试用项目延期申请' => array(
		'url' => '?model=projectmanagent_trialproject_extension&action=init&perm=view&id=', //审批时页面查看路径
		'viewUrl' => '?model=projectmanagent_trialproject_extension&action=init&perm=view&id=', //列表页面查看路径
		'rtUrl' => '?model=projectmanagent_trialproject_extension&action=confirmExa&spid='
	),
	'售前支持申请' => array(
		'viewUrl' => '?model=projectmanagent_support_support&action=toView&id=',
		'url' => '?model=projectmanagent_support_support&action=appEdit&actType=audit&urlType=1&id=',
		'rtUrl' => '?model=projectmanagent_support_support&action=confirmSupport&urlType=1&spid=',
		'allStep' => '1'
	),
	'盖章申请审批' => array(
		'url' => '?model=contract_stamp_stampapply&action=toAudit&id=', //审批时页面查看路径
		'viewUrl' => '?model=contract_stamp_stampapply&action=toView&id=', //列表页面查看路径
		'rtUrl' => '?model=contract_stamp_stampapply&action=dealAfterAudit&spid='
	),
	'合同盖章申请审批' => array(
		'url' => '?model=contract_stamp_stampapply&action=toAudit&id=', //审批时页面查看路径
		'viewUrl' => '?model=contract_stamp_stampapply&action=toView&id=', //列表页面查看路径
		'rtUrl' => '?model=contract_stamp_stampapply&action=dealAfterAudit&spid='
	),
	'租车申请审批' => array(
		'url' => '?model=outsourcing_vehicle_rentalcar&action=toAudit&id=', //审批时页面查看路径
		'viewUrl' => '?model=outsourcing_vehicle_rentalcar&action=toView&id=', //列表页面查看路径
		'rtUrl' => '?model=outsourcing_vehicle_rentalcar&action=dealAfterAuditPass&spid='
	),
	'租车登记审批' => array(
		'url' => '?model=outsourcing_vehicle_allregister&action=toAudit&hideBtn=true&id=', //审批时页面查看路径
		'viewUrl' => '?model=outsourcing_vehicle_allregister&action=toAudit&id=', //列表页面查看路径
		'rtUrl' => '?model=outsourcing_vehicle_allregister&action=dealAfterAuditPass&spid='
	),
	'租车合同审批' => array(
		'url' => '?model=outsourcing_contract_rentcar&action=toAudit&id=', //审批时页面查看路径
		'viewUrl' => '?model=outsourcing_contract_rentcar&action=toView&id=', //列表页面查看路径
		'rtUrl' => '?model=outsourcing_contract_rentcar&action=dealAfterAudit&spid='
	),
	'租车合同变更审批' => array(
		'url' => '?model=outsourcing_contract_rentcar&action=toChangeTab&id=', //审批时页面查看路径
		'viewUrl' => '?model=outsourcing_contract_rentcar&action=toChangeTab&id=', //列表页面查看路径
		'rtUrl' => '?model=outsourcing_contract_rentcar&action=dealAfterAuditChange&spid='
	),
	'外包合同关闭审批' => array(
		'url' => '?model=contract_outsourcing_outsourcing&action=viewTab&id=', //审批时页面查看路径
		'viewUrl' => '?model=contract_outsourcing_outsourcing&action=viewTab&id=', //列表页面查看路径
		'isSkey' => '1',
		'keyObj' => 'contract_outsourcing_outsourcing'
	),
	'其他合同关闭审批' => array(
		'url' => '?model=contract_other_other&action=viewTab&id=', //审批时页面查看路径
		'viewUrl' => '?model=contract_other_other&action=viewTab&id=', //列表页面查看路径
		'isSkey' => '1',
		'keyObj' => 'contract_other_other'
	),
	'合同审批C' => array( //新合同审批
		'url' => '?model=contract_contract_contract&action=init&actType=audit&perm=view&actType=audit&id=', //审批时页面查看路径
		'viewUrl' => '?model=contract_contract_contract&action=init&perm=view&id=', //列表页面查看路径
		'rtUrl' => '?model=contract_contract_contract&action=configContract&spid='
	),
	'合同变更审批C' => array( //
		'url' => '?model=contract_contract_contract&action=showView&id=', //审批时页面查看路径
		'viewUrl' => '?model=contract_contract_contract&action=init&perm=view&id=', //列表页面查看路径
		'rtUrl' => '?model=contract_contract_contract&action=confirmChangeToApprovalNo&spid='
	),
	/*********************************新合同管理审批注册**END******************************************************************/
	//财务部分
	'采购付款申请' => array( //付款申请
		'url' => '?model=finance_payablesapply_payablesapply&action=initAuditing&id=',
		'viewUrl' => '?model=finance_payablesapply_payablesapply&action=init&perm=view&id=',
		'isSkey' => '1',
		'keyObj' => 'finance_payablesapply_payablesapply',
		'rtUrl' => '?model=finance_payablesapply_payablesapply&action=dealAfterAudit&spid='
	),
	'采购退款申请' => array( //采购退款申请
		'url' => '?model=finance_payablesapply_payablesapply&action=initAuditing&id=',
		'viewUrl' => '?model=finance_payablesapply_payablesapply&action=init&perm=view&id=',
		'isSkey' => '1',
		'keyObj' => 'finance_payablesapply_payablesapply',
		'rtUrl' => '?model=finance_payablesapply_payablesapply&action=dealAfterAudit&spid='
	),
	'付款申请' => array( //付款申请
		'url' => '?model=finance_payablesapply_payablesapply&action=toViewAudit&id=',
		'viewUrl' => '?model=finance_payablesapply_payablesapply&action=toView&id=',
		'isSkey' => '1',
		'keyObj' => 'finance_payablesapply_payablesapply',
		'rtUrl' => '?model=finance_payablesapply_payablesapply&action=dealAfterAudit&spid='
	),
	'开票申请' => array( //开票申请
		'url' => '?model=finance_invoiceapply_invoiceapply&action=initAuditing&id=',
		'viewUrl' => '?model=finance_invoiceapply_invoiceapply&action=init&perm=view&id=',
		'isSkey' => '1',
		'keyObj' => 'finance_invoiceapply_invoiceapply',
		'rtUrl' => '?model=finance_invoiceapply_invoiceapply&action=dealAfterAudit&spid='
	),
	'异地开票申请' => array(
		'url' => '?model=finance_invoiceapply_invoiceapply&action=initAuditing&id=',
		'viewUrl' => '?model=finance_invoiceapply_invoiceapply&action=init&perm=view&id=',
		'isSkey' => '1',
		'keyObj' => 'finance_invoiceapply_invoiceapply'
	),

	'退款申请' => array( //退款申请
		'url' => '?model=finance_payablesapply_payablesapply&action=initBack&id=',
		'viewUrl' => '?model=finance_payablesapply_payablesapply&action=toView&perm=view&id=',
		'isSkey' => '1',
		'keyObj' => 'finance_payablesapply_payablesapply',
		'rtUrl' => '?model=finance_payablesapply_payablesapply&action=dealAfterAudit&spid='
	),
	'赔偿单审批' => array(
		'url' => '?model=finance_compensate_compensate&action=toViewAudit&id=', //审批时页面查看路径
		'viewUrl' => '?model=finance_compensate_compensate&action=toView&id=', //列表页面查看路径
		'rtUrl' => '?model=finance_compensate_compensate&action=dealAfterAudit&spid='
	),

	//采购部分
	'采购申请单' => array( //采购申请
		'url' => '?model=purchase_plan_basic&action=read&actType=audit&id=',
		'viewUrl' => '?model=purchase_plan_basic&action=read&id=',
		'isSkey' => '1',
		'keyObj' => 'purchase_plan_basic',
		'rtUrl' => '?model=purchase_plan_basic&action=emailNotice&urlType=1&spid='
	),
	'采购询价单审批' => array( //采购询价
		'url' => '?model=purchase_inquiry_inquirysheet&action=toAssignSupp&actType=audit&id=',
		'viewUrl' => '?model=purchase_inquiry_inquirysheet&action=toView&id=',
		'isSkey' => '1',
		'keyObj' => 'purchase_inquiry_inquirysheet',
		'rtUrl' => '?model=purchase_inquiry_inquirysheet&action=assignSuppByApproval&urlType=1&spid='
	),
	'采购合同审批' => array( //采购订单
		'url' => '?model=purchase_contract_purchasecontract&action=approViewTab&readType=exam&id=',
		'viewUrl' => '?model=purchase_contract_purchasecontract&action=toReadTab&id=',
		'isSkey' => '1',
		'keyObj' => 'purchase_contract_purchasecontract',
		'rtUrl' => '?model=purchase_contract_purchasecontract&action=updateOnWayNumb&urlType=1&spid='
	),
	'采购订单审批(含付款申请)' => array( //采购订单
		'url' => '?model=purchase_contract_purchasecontract&action=approViewTab&readType=exam&id=',
		'viewUrl' => '?model=purchase_contract_purchasecontract&action=toReadTab&id=',
		'isSkey' => '1',
		'keyObj' => 'purchase_contract_purchasecontract',
		'rtUrl' => '?model=purchase_contract_purchasecontract&action=updateOnWayNumb&urlType=1&spid='
	),
	'采购订单变更审批' => array( //采购订单
		'url' => '?model=purchase_contract_purchasecontract&action=toTabView&readType=exam&id=',
		'viewUrl' => '?model=purchase_contract_purchasecontract&action=init&perm=view&id=',
		'auditedViewUrl' => '?model=purchase_contract_purchasecontract&action=init&perm=view&id=',
		'isSkey' => '1',
		'keyObj' => 'purchase_contract_purchasecontract',
		'isChange' => '1',
		'changeCode' => 'purchasecontract',
		'seachCode' => 'purchasecontractIsChange',
		'orgCode' => '采购合同审批'
	),
	'采购订单中止审批' => array( //采购订单
		'url' => '?model=purchase_contract_purchasecontract&action=toCloseTabRead&id=',
		'viewUrl' => '?model=purchase_contract_purchasecontract&action=toCloseTabRead&id=',
		'isSkey' => '1',
		'keyObj' => 'purchase_contract_purchasecontract',
		'rtUrl' => '?model=purchase_contract_purchasecontract&action=dealClose&urlType=1&spid='
	),
	'采购订单关闭审批' => array( //采购订单
		'url' => '?model=purchase_contract_purchasecontract&action=toCloseOrderTabRead&id=',
		'viewUrl' => '?model=purchase_contract_purchasecontract&action=toCloseOrderTabRead&id=',
		'isSkey' => '1',
		'keyObj' => 'purchase_contract_purchasecontract'
	),
	'采购退料审批' => array( //采购订单
		'url' => '?model=purchase_delivered_delivered&action=init&perm=view&actType=audit&id=',
		'viewUrl' => '?model=purchase_delivered_delivered&action=init&perm=view&id=',
		//		'rtUrl'=>'?model=purchase_delivered_delivered&action=updateApplyPrice&spid=',
		'isSkey' => '1',
		'keyObj' => 'purchase_delivered_delivered'
	),
	'生产采购申请审批' => array(
		'viewUrl' => '?model=purchase_plan_basic&action=read&purchType=produce&id=',
		'url' => '?model=purchase_plan_basic&action=read&purchType=produce&show=1&actType=audit&id=',
		'isSkey' => '1',
		'keyObj' => 'purchase_plan_basic',
		'rtUrl' => '?model=purchase_plan_basic&action=dealApproval&urlType=1&spid=',
		'allStep' => '1'
	),
	'研发采购申请审批' => array(
		'viewUrl' => '?model=purchase_plan_basic&action=read&purchType=rdproject&id=',
		'url' => '?model=purchase_plan_basic&action=read&purchType=rdproject&actType=audit&id=',
		'isSkey' => '1',
		'keyObj' => 'purchase_plan_basic',
		'rtUrl' => "?model=purchase_plan_basic&action=confirmAudit&urlType=1&spid="
	),
	'资产采购申请审批' => array(
		'viewUrl' => '?model=purchase_plan_basic&action=read&purchType=assets&id=',
		'url' => '?model=purchase_plan_basic&action=read&purchType=assets&actType=audit&id=',
		'isSkey' => '1',
		'keyObj' => 'purchase_plan_basic',
		'rtUrl' => '?model=purchase_plan_basic&action=emailNotice&urlType=1&spid='
	),
	'采购申请变更审批' => array(
		'viewUrl' => '?model=purchase_plan_basic&action=read&purchType=assets&actType=audit&id=',
		'url' => '?model=purchase_plan_basic&action=read&purchType=assets&actType=audit&id=',
		'isSkey' => '1',
		'keyObj' => 'purchase_plan_basic',
		'isChange' => '1',
		'seachCode' => 'purchaseplan',
		'orgCode' => '采购申请变更审批',
		'rtUrl' => '?model=purchase_plan_basic&action=dealChange&urlType=1&spid='
	),
	'采购任务关闭审批' => array(
		'viewUrl' => '?model=purchase_task_basic&action=toCloseRead&id=',
		'url' => '?model=purchase_task_basic&action=toCloseRead&actType=audit&id=',
		'isSkey' => '1',
		'keyObj' => 'purchase_task_basic'
	),
    
	'设备赠送' => array(
		'viewUrl' => '?model=projectmanagent_present_present&action=init&perm=view&id=',
		'url' => '?model=projectmanagent_present_present&action=init&perm=view&id=',
		'isSkey' => '1',
		'keyObj' => 'projectmanagent_present_present',
        'rtUrl' => '?model=projectmanagent_present_present&action=dealAfterAudit&spid='
	),

	//销售部分
	'借试用申请' => array( //借试用
		'url' => '?model=projectmanagent_borrow_borrow&action=init&perm=view&actType=audit&id=',
		'viewUrl' => '?model=projectmanagent_borrow_borrow&action=init&perm=view&id=',
		'isSkey' => '1',
		'keyObj' => 'projectmanagent_borrow_borrow',
		'rtUrl' => '?model=projectmanagent_borrow_borrow&action=borrowExa&urlType=1&spid='
	), //销售部分
	'员工借试用' => array( //员工借试用
		'url' => '?model=projectmanagent_borrow_borrow&action=init&perm=view&actType=audit&id=',
		'viewUrl' => '?model=projectmanagent_borrow_borrow&action=init&perm=view&id=',
		'isSkey' => '1',
		'keyObj' => 'projectmanagent_borrow_borrow',
        'rtUrl' => '?model=projectmanagent_borrow_borrow&action=borrowExa&urlType=1&spid='
	),
	'员工续借申请' => array( //员工续借
		'url' => '?model=projectmanagent_borrow_renew&action=view&id=',
		'viewUrl' => '?model=projectmanagent_borrow_renew&action=view&id=',
		'rtUrl' => '?model=projectmanagent_borrow_renew&action=updateBorrow&urlType=1&spid='
	),
	'员工借试用转借申请' => array( //员工借试用转借
		'url' => '?model=projectmanagent_borrow_borrow&action=subtenancyView&id=',
		'viewUrl' => '?model=projectmanagent_borrow_borrow&action=subtenancyView&id=',
		'rtUrl' => '?model=projectmanagent_borrow_borrow&action=updateSubtenancy&urlType=1&spid='
	),
	'借试用变更审批' => array(
		'url' => '?model=projectmanagent_borrow_borrow&action=init&perm=view&actType=audit&id=',
		'viewUrl' => '?model=projectmanagent_borrow_borrow&action=toViewTab&change=1&id=',
		'rtUrl' => '?model=projectmanagent_borrow_borrow&action=confirmChangeToApprovalNo&urlType=1&spid='
	),
	'借试用归还审批' => array(
		'url' => '?model=projectmanagent_borrowreturn_borrowreturn&action=toAudit&id=',
		'viewUrl' => '?model=projectmanagent_borrowreturn_borrowreturn&action=toView&id=',
		'rtUrl' => '?model=projectmanagent_borrowreturn_borrowreturn&action=dealAfterAudit&spid='
	),
	'借试用关闭审批' => array(
		'url' => '?model=projectmanagent_borrow_borrow&action=init&perm=view&actType=audit&id=',
		'viewUrl' => '?model=projectmanagent_borrow_borrow&action=toViewTab&change=1&id=',
		'isSkey' => '1',
		'keyObj' => 'projectmanagent_borrow_borrow'
	),
	'借试用发货物料变更' => array(
		'url' => '?model=projectmanagent_borrow_borrowequ&action=toEquChangeView&linkId=',
		'viewUrl' => '?model=projectmanagent_borrow_borrowequ&action=toEquChangeView&linkId=',
		'isSkey' => '1',
		'keyObj' => 'projectmanagent_borrow_borrow',
		'rtUrl' => '?model=projectmanagent_borrow_borrowequ&action=dealAfterChangeAudit&spid='
	),
	'赠送变更审批' => array(
		'url' => '?model=projectmanagent_present_present&action=auditView&id=',
		'viewUrl' => '?model=projectmanagent_present_present&action=auditView&id=',
		'rtUrl' => '?model=projectmanagent_present_present&action=confirmChangeToApprovalNo&urlType=1&spid='
	),
	//仓存部分
	'补库审批' => array( //补库计划
		'url' => '?model=stock_fillup_fillup&action=init&perm=view&actType=audit&id=',
		'viewUrl' => '?model=stock_fillup_fillup&action=init&perm=view&id=',
		'isSkey' => '1',
		'keyObj' => 'stock_fillup_fillup',
		'rtUrl' => '?model=stock_fillup_fillup&action=dealAfterAudit&spid='
	),

	//供应商部分
	'供应商审核' => array( //供应商
		'url' => '?model=supplierManage_temporary_temporary&action=init&perm=view&id=',
		'viewUrl' => '?model=supplierManage_temporary_temporary&action=init&perm=view&id=',
		'isSkey' => '1',
		'keyObj' => 'supplierManage_temporary_temporary'

	),

	//固定需求申请
	'资产需求申请审批' => array( //资产需求申请
		'url' => '?model=asset_require_requirement&action=toRecognizeView&perm=view&btn=1&id=',
		'viewUrl' => '?model=asset_require_requirement&action=toRecognizeView&perm=view&btn=1&id=',
		'isSkey' => '1',
		'keyObj' => 'asset_require_requirement',
		'rtUrl' => '?model=asset_require_requirement&action=dealAfterAudit&spid='
	),
	//
	'资产需求申请(管理层)' => array( //资产需求申请(管理层)
		'url' => '?model=asset_require_requirement&action=toRecognizeView&perm=view&btn=1&id=',
		'viewUrl' => '?model=asset_require_requirement&action=toRecognizeView&perm=view&btn=1&id=',
		'isSkey' => '1',
		'keyObj' => 'asset_require_requirement',
		'rtUrl' => '?model=asset_require_requirement&action=dealAfterAudit&spid='
	),
	//固定需求申请
	'资产需求金额审批' => array( //资产需求金额
		'url' => '?model=asset_require_requirement&action=toRecognizeView&perm=view&btn=1&id=',
		'viewUrl' => '?model=asset_require_requirement&action=toRecognizeView&perm=view&btn=1&id=',
		'isSkey' => '1',
		'keyObj' => 'asset_require_requirement'
	),
	//固定资产日常管理
	'资产借用审批' => array( //资产借用
		'url' => '?model=asset_daily_borrow&action=init&perm=view&btn=1&id=',
		'viewUrl' => '?model=asset_daily_borrow&action=init&perm=view&btn=1&id=',
		'isSkey' => '1',
		'keyObj' => 'asset_daily_borrow',
		'rtUrl' => '?model=asset_daily_dailyCommon&action=dealAfterAudit&docType=oa_asset_borrow&spid='
	),
	'资产调拨审批' => array( //资产调拨
		'url' => '?model=asset_daily_allocation&action=init&perm=view&btn=1&id=',
		'viewUrl' => '?model=asset_daily_allocation&action=init&perm=view&btn=1&id=',
		'isSkey' => '1',
		'keyObj' => 'asset_daily_allocation',
		'rtUrl' => '?model=asset_daily_dailyCommon&action=dealAfterAudit&docType=oa_asset_allocation&spid='
	),

	'资产租赁审批' => array( //资产租赁
		'url' => '?model=asset_daily_rent&action=init&perm=view&btn=1&id=',
		'viewUrl' => '?model=asset_daily_rent&action=init&perm=view&btn=1&id=',
		'isSkey' => '1',
		'keyObj' => 'asset_daily_rent'
	),


	//固定资产的采购申请
	'固定资产采购申请' => array( //采购申请
		'url' => '?model=asset_purchase_apply_apply&action=init&perm=viewaudit&viewBtn=1&id=',
		'viewUrl' => '?model=asset_purchase_apply_apply&action=init&perm=viewaudit&viewBtn=1&id=',
		'isSkey' => '1',
		'keyObj' => 'asset_purchase_apply_apply',
		'rtUrl' => '?model=asset_purchase_apply_apply&action=auditSendEmail&urlType=1&spid='
	),

	//固定资产的报废申请
	'资产报废申请审批' => array( //报废申请
		'url' => '?model=asset_disposal_scrap&action=init&perm=view&viewBtn=1&id=',
		'viewUrl' => '?model=asset_disposal_scrap&action=init&perm=view&viewBtn=1&id=',
		'isSkey' => '1',
		'keyObj' => 'asset_disposal_scrap',
		'rtUrl' => '?model=asset_disposal_scrap&action=dealAfterAudit&spid='
	),

	//固定资产的出售申请
	'资产出售申请审批' => array( //出售申请
		'url' => '?model=asset_disposal_sell&action=init&perm=view&viewBtn=1&id=',
		'viewUrl' => '?model=asset_disposal_sell&action=init&perm=view&viewBtn=1&id=',
		'isSkey' => '1',
		'keyObj' => 'asset_disposal_sell',
		'rtUrl' => '?model=asset_disposal_sell&action=dealAfterAudit&spid='
	),

	//固定资产的维保申请
	'资产维保申请审批' => array( //维保申请
		'url' => '?model=asset_daily_keep&action=init&perm=view&viewBtn=1&id=',
		'viewUrl' => '?model=asset_daily_keep&action=init&perm=view&viewBtn=1&id=',
		'isSkey' => '1',
		'keyObj' => 'asset_daily_keep',
		'rtUrl' => '?model=asset_daily_dailyCommon&action=dealAfterAudit&docType=oa_asset_keep&spid='
	),
	//固定资产的遗失申请
	'资产遗失申请审批' => array( //遗失申请
		'url' => '?model=asset_daily_lose&action=audit&id=',
		'viewUrl' => '?model=asset_daily_lose&action=audit&id=',
		'allStep' => '1',
		'isSkey' => '1',
		'keyObj' => 'asset_daily_lose',
		'rtUrl' => '?model=asset_daily_dailyCommon&action=dealAfterAudit&docType=oa_asset_lose&spid='
	),
	//固定资产的验收
	'固定资产验收' => array( //资产验收
		'url' => '?model=asset_purchase_receive_receive&action=init&perm=view&viewBtn=1&id=',
		'viewUrl' => '?model=asset_purchase_receive_receive&action=init&perm=view&viewBtn=1&id=',
		'isSkey' => '1',
		'keyObj' => 'asset_purchase_receive_receive'
	),
	//固定资产的领用
	'资产领用申请审批' => array( //资产领用
		'url' => '?model=asset_daily_charge&action=init&perm=view&viewBtn=1&id=',
		'viewUrl' => '?model=asset_daily_charge&action=init&perm=view&viewBtn=1&id=',
		'isSkey' => '1',
		'keyObj' => 'asset_daily_charge',
		'rtUrl' => '?model=asset_daily_dailyCommon&action=dealAfterAudit&docType=oa_asset_charge&spid='
	),
	//固定资产的归还
	'资产归还申请审批' => array( //资产归还
		'url' => '?model=asset_daily_return&action=init&perm=view&viewBtn=1&id=',
		'viewUrl' => '?model=asset_daily_return&action=init&perm=view&viewBtn=1&id=',
		'isSkey' => '1',
		'keyObj' => 'asset_daily_return',
		'rtUrl' => '?model=asset_daily_dailyCommon&action=dealAfterAudit&docType=oa_asset_return&spid='
	),
	//固定资产的清理记录
	'资产清理申请审批' => array(
		'url' => '?model=asset_assetcard_clean&action=init&perm=view&viewBtn=1&id=',
		'viewUrl' => '?model=asset_assetcard_clean&action=init&perm=view&viewBtn=1&id=',
		'isSkey' => '1',
		'keyObj' => 'asset_assetcard_clean',
		'rtUrl' => '?model=asset_assetcard_clean&action=dealAfterAudit&spid='
	),
	//物料转资产申请审批
	'物料转资产申请审批' => array(
		'url' => '?model=asset_require_requirein&action=toView&viewBtn=1&id=',
		'viewUrl' => '?model=asset_require_requirein&action=toView&viewBtn=1&id=',
		'isSkey' => '1',
		'keyObj' => 'asset_require_requirein',
		'rtUrl' => '?model=asset_require_requirein&action=dealAfterAudit&spid='
	),
	//资产转物料申请审批
	'资产转物料申请审批' => array(
		'url' => '?model=asset_require_requireout&action=toView&viewBtn=1&id=',
		'viewUrl' => '?model=asset_require_requireout&action=toView&viewBtn=1&id=',
		'isSkey' => '1',
		'keyObj' => 'asset_require_requireout',
		'rtUrl' => '?model=asset_require_requireout&action=dealAfterAudit&spid='
	),
	'工程项目审批' => array(
		'url' => '?model=engineering_project_esmproject&action=auditNewTab&id=',
		'viewUrl' => '?model=engineering_project_esmproject&action=auditNewTab&id=',
		'isSkey' => '1',
		'keyObj' => 'engineering_project_esmproject',
		'rtUrl' => '?model=engineering_project_esmproject&action=dealAfterAudit&spid='
	),
    '工程项目完工审批' => array(
        'url' => '?model=engineering_project_esmproject&action=auditNewTab&id=',
        'viewUrl' => '?model=engineering_project_esmproject&action=auditNewTab&id=',
        'isSkey' => '1',
        'keyObj' => 'engineering_project_esmproject',
        'rtUrl' => '?model=engineering_project_esmproject&action=dealAfterCompleteAudit&spid='
    ),
	'工程项目变更' => array(
		'url' => '?model=engineering_change_esmchange&action=viewAudit&id=',
		'viewUrl' => '?model=engineering_change_esmchange&action=init&perm=view&id=',
		'isSkey' => '1',
		'keyObj' => 'engineering_change_esmchange',
		'rtUrl' => '?model=engineering_change_esmchange&action=dealAfterAudit&spid='
	),
	'工程项目关闭审批' => array(
		'url' => '?model=engineering_close_esmclose&action=toAudit&id=',
		'viewUrl' => '?model=engineering_close_esmclose&action=toView&id=',
		'isSkey' => '1',
		'keyObj' => 'engineering_close_esmclose',
		'rtUrl' => '?model=engineering_close_esmclose&action=dealAfterAudit&spid='
	),
	//工程项目周报审批
	'工程项目周报审批' => array(
		'url' => '?model=engineering_project_statusreport&action=toAudit&id=',
		'viewUrl' => '?model=engineering_project_statusreport&action=toView&id=',
		'isSkey' => '1',
		'allStep' => 1,
		'keyObj' => 'engineering_project_statusreport',
		'rtUrl' => '?model=engineering_project_statusreport&action=dealAfterAudit&spid='
	),
	//项目设备申请
	'项目设备申请' => array(
		'url' => '?model=engineering_resources_resourceapply&action=toAudit&id=',
		'viewUrl' => '?model=engineering_resources_resourceapply&action=toView&id=',
		'rtUrl' => '?model=engineering_resources_resourceapply&action=dealAfterAudit&spid='
	),
	//工程项目设备申请
	'设备申请(项目)' => array(
		'url' => '?model=engineering_resources_resourceapply&action=toAudit&id=',
		'viewUrl' => '?model=engineering_resources_resourceapply&action=toView&id=',
		'rtUrl' => '?model=engineering_resources_resourceapply&action=dealAfterAudit&spid='
	),
	//项目设备申请
	'设备申请(个人)' => array(
		'url' => '?model=engineering_resources_resourceapply&action=toAudit&id=',
		'viewUrl' => '?model=engineering_resources_resourceapply&action=toView&id=',
		'rtUrl' => '?model=engineering_resources_resourceapply&action=dealAfterAudit&spid='
	),
	//零配件订单审批
	'零配件订单审批' => array(
		'url' => '?model=service_accessorder_accessorder&action=toView&viewBtn=1&id=',
		'viewUrl' => '?model=service_accessorder_accessorder&action=toView&viewBtn=1&id=',
		'isSkey' => '1',
		'keyObj' => 'service_accessorder_accessorder'
	),
	//费用减免申请单审批
	'费用减免申请单审批' => array(
		'url' => '?model=service_reduce_reduceapply&action=toView&viewBtn=1&id=',
		'viewUrl' => '?model=service_reduce_reduceapply&action=toView&viewBtn=1&id=',
		'isSkey' => '1',
		'keyObj' => 'service_reduce_reduceapply',
		'rtUrl' => '?model=service_reduce_reduceapply&action=dealAfterAudit&spid='
	),
	//设备更换申请单审批
	'设备更换申请单审批' => array(
		'url' => '?model=service_change_changeapply&action=toView&viewBtn=1&id=',
		'viewUrl' => '?model=service_change_changeapply&action=toView&viewBtn=1&id=',
		'isSkey' => '1',
		'keyObj' => 'service_change_changeapply'
	),
	//维修报价申报单审批
	'维修报价审批' => array(
		'url' => '?model=service_repair_repairquote&action=toView&viewBtn=1&id=',
		'viewUrl' => '?model=service_repair_repairquote&action=toView&viewBtn=1&id=',
		'isSkey' => '1',
		'keyObj' => 'service_repair_repairquote'
	),
	'外包合同审批' => array(
		'url' => '?model=contract_outsourcing_outsourcing&action=viewAccraditation&perm=view&viewBtn=1&id=',
		'viewUrl' => '?model=contract_outsourcing_outsourcing&action=init&perm=view&id=',
		'isSkey' => '1',
		'keyObj' => 'contract_outsourcing_outsourcing',
		'rtUrl' => '?model=contract_outsourcing_outsourcing&action=dealAfterAudit&spid='
	),
	'外包合同变更审批' => array(
		'url' => '?model=contract_outsourcing_outsourcing&action=changeTab&id=',
		'viewUrl' => '?model=contract_outsourcing_outsourcing&action=changeTab&id=',
		'isSkey' => '1',
		'keyObj' => 'contract_outsourcing_outsourcing',
		'rtUrl' => '?model=contract_outsourcing_outsourcing&action=dealAfterAuditChange&spid='
	),
	'外包合同立项付款申请' => array(
		'url' => '?model=contract_outsourcing_outsourcing&action=viewAlong&showBtn=0&id=',
		'viewUrl' => '?model=contract_outsourcing_outsourcing&action=viewAlong&id=',
		'isSkey' => '1',
		'keyObj' => 'contract_outsourcing_outsourcing',
		'rtUrl' => '?model=contract_outsourcing_outsourcing&action=dealAfterAuditPayapply&spid='
	),
	'其他合同审批' => array(
		'url' => '?model=contract_other_other&action=viewAlong&act=auditView&viewOn=audit&showBtn=0&id=',
		'viewUrl' => '?model=contract_other_other&action=viewAlong&viewOn=audit&id=',
		'isSkey' => '1',
		'keyObj' => 'contract_other_other',
		'rtUrl' => '?model=contract_other_other&action=dealAfterAudit&spid='
	),
	'其他合同变更审批' => array(
		'url' => '?model=contract_other_other&action=changeTab&id=',
		'viewUrl' => '?model=contract_other_other&action=changeTab&id=',
		'isSkey' => '1',
		'keyObj' => 'contract_other_other',
		'rtUrl' => '?model=contract_other_other&action=dealAfterAuditChange&spid='
	),
	'其他合同立项付款申请' => array(
		'url' => '?model=contract_other_other&action=viewAlong&act=auditView&viewOn=audit&hideBtn=1&id=',
		'viewUrl' => '?model=contract_other_other&action=viewAlong&viewOn=audit&id=',
		'isSkey' => '1',
		'keyObj' => 'contract_other_other',
		'rtUrl' => '?model=contract_other_other&action=dealAfterAuditPayapply&spid='
	),
	//供应商管理
	'评估方案审批' => array(
		'url' => '?model=supplierManage_scheme_scheme&action=init&perm=view&viewBtn=1&id=',
		'viewUrl' => '?model=supplierManage_scheme_scheme&action=init&perm=view&viewBtn=1&id=',
		'isSkey' => '1',
		'keyObj' => 'supplierManage_scheme_scheme',
		'rtUrl' => '?model=supplierManage_scheme_scheme&action=auditSendEmail&urlType=1&spid='
	),
	'新供应商评估审批' => array(
		'url' => '?model=supplierManage_assessment_supasses&action=toView&viewBtn=1&viewType=aduit&id=',
		'viewUrl' => '?model=supplierManage_assessment_supasses&action=toView&id=',
		'isSkey' => '1',
		'keyObj' => 'supplierManage_assessment_supasses',
		'rtUrl' => '?model=supplierManage_assessment_supasses&action=dealSuppass&urlType=1&spid='
	),
	'供应商季度考核审批' => array(
		'url' => '?model=supplierManage_assessment_supasses&action=toView&viewBtn=1&viewType=aduit&id=',
		'viewUrl' => '?model=supplierManage_assessment_supasses&action=toView&id=',
		'isSkey' => '1',
		'keyObj' => 'supplierManage_assessment_supasses',
		'rtUrl' => '?model=supplierManage_assessment_supasses&action=dealSuppass&urlType=1&spid='
	),
	'供应商年度考核审批' => array(
		'url' => '?model=supplierManage_assessment_supasses&action=toView&viewBtn=1&viewType=aduit&id=',
		'viewUrl' => '?model=supplierManage_assessment_supasses&action=toView&id=',
		'isSkey' => '1',
		'keyObj' => 'supplierManage_assessment_supasses',
		'rtUrl' => '?model=supplierManage_assessment_supasses&action=dealSuppass&urlType=1&spid='
	),
	'试用项目申请' => array(
		'url' => '?model=projectmanagent_trialproject_trialproject&action=init&perm=view&id=', //审批时页面查看路径
		'viewUrl' => '?model=projectmanagent_trialproject_trialproject&action=init&perm=view&id=', //列表页面查看路径
		'rtUrl' => '?model=projectmanagent_trialproject_trialproject&action=confirmExa&spid='
	),
	'试用项目延期申请' => array(
		'url' => '?model=projectmanagent_trialproject_extension&action=viewTab&id=', //审批时页面查看路径
		'viewUrl' => '?model=projectmanagent_trialproject_extension&action=init&perm=view&id=', //列表页面查看路径
		'rtUrl' => '?model=projectmanagent_trialproject_extension&action=confirmExa&spid='
	),
	'销售备货申请' => array(
		'url' => '?model=projectmanagent_stockup_stockup&action=toView&viewType=aduit&id=', //审批时页面查看路径
		'viewUrl' => '?model=projectmanagent_stockup_stockup&action=toView&id=', //列表页面查看路径
		//		'rtUrl' => '?model=projectmanagent_trialproject_extension&action=confirmExa&spid='
	),
	'借试用赔偿单确认' => array(
		'url' => '?model=projectmanagent_borrowreturn_borrowreturnDis&action=moneyView&viewType=aduit&id=', //审批时页面查看路径
		'viewUrl' => '?model=projectmanagent_borrowreturn_borrowreturnDis&action=moneyView&id=', //列表页面查看路径
		'rtUrl' => '?model=projectmanagent_borrowreturn_borrowreturnDis&action=confirmExa&spid=',
		'allStep' => '1'
	),
	'质检报告审批' => array(
		'url' => '?model=produce_quality_qualityereport&action=toAudit&id=', //审批时页面查看路径
		'viewUrl' => '?model=produce_quality_qualityereport&action=toView&id=', //列表页面查看路径
		'rtUrl' => '?model=produce_quality_qualityereport&action=dealAfterAudit&spid='
	),
    '呆料报废质检审批' => array(
        'url' => '?model=produce_quality_qualityereport&action=toAudit&id=', //审批时页面查看路径
        'viewUrl' => '?model=produce_quality_qualityereport&action=toView&id=', //列表页面查看路径
        'rtUrl' => '?model=produce_quality_qualityereport&action=dealAfterAudit&spid='
    ),
	'公告审批' => array(
		'url' => '?model=info_notice&action=detailaudit&id=', //审批时页面查看路径
		'viewUrl' => '?model=info_notice&action=detailaudit&id=', //列表页面查看路径
		'rtUrl' => '?model=info_notice&action=applered&spid='
	),
	'需求申请审批' => array(
		'url' => '?model=reqm_apply_list&action=toAudit&keyId=',
		'viewUrl' => '?model=reqm_apply_list&action=toAudit&keyId='
	),
	'报销补贴审批' => array(
		'url' => '?model=cost_stat_import&action=detail&id=',
		'viewUrl' => '?model=cost_stat_import&action=detail&id='
		//'rtUrl' => '?model=cost_stat_import&action=billDocument&spid='
	),

	/********************************* 原OA系统中的审批流接入部分 ********************************/

	'招聘申请' => array(
		'url' => 'general/doc_manage/hrms_recruit/info.php?billId=', //单据审批时查看路径
		'viewUrl' => 'general/doc_manage/hrms_recruit/info.php?billId=' //单据列表查看路径
	),
	'档案户口调动' => array(
		'url' => 'general/doc_manage/hrms_remove/info.php?billId=',
		'viewUrl' => 'general/doc_manage/hrms_remove/info.php?billId='
	),
	'请休假' => array(
		'url' => 'general/doc_manage/hols/info.php?billId=',
		'viewUrl' => 'general/doc_manage/hols/info.php?billId='
	),
	'请休假A' => array(
		'url' => 'general/doc_manage/hols/info.php?billId=',
		'viewUrl' => 'general/doc_manage/hols/info.php?billId='
	),
	'新闻审批' => array(
		'url' => 'general/news/show/show_news.php?NEWS_ID=',
		'viewUrl' => 'general/news/show/show_news.php?NEWS_ID=',
		'rtUrl' => 'general/news/manage/sendmail.php?NEWS_ID&spid='
	),
    '借款单' => array(
//        'url' => 'general/costmanage/loan/loan_detail.php?ID=',
//        'viewUrl' => 'general/costmanage/loan/loan_detail.php?ID='
        'url' => '?model=loan_loan_loan&action=toAudit&id=',
        'viewUrl' => '?model=loan_loan_loan&action=toView&id=',
        'rtUrl' => '?model=loan_loan_loan&action=dealAfterAudit&spid='
    ),
    '借款变更单' => array(
        'viewUrl' => '?model=loan_loan_loan&action=toViewTab&isTemp=1&id=',
        'url' => '?model=loan_loan_loan&action=changeAudit&actType=audit&id=',
        'rtUrl' => '?model=loan_loan_loan&action=dealAfterAuditChange&spid='
    ),
	'服务项目借款单' => array(
//        'url' => 'general/costmanage/loan/loan_detail.php?ID=',
//        'viewUrl' => 'general/costmanage/loan/loan_detail.php?ID='
        'url' => '?model=loan_loan_loan&action=toAudit&id=',
        'viewUrl' => '?model=loan_loan_loan&action=toView&id=',
        'rtUrl' => '?model=loan_loan_loan&action=dealAfterAudit&spid='
    ),
    '服务项目借款变更单' => array(
        'viewUrl' => '?model=loan_loan_loan&action=toViewTab&isTemp=1&id=',
        'url' => '?model=loan_loan_loan&action=changeAudit&actType=audit&id=',
        'rtUrl' => '?model=loan_loan_loan&action=dealAfterAuditChange&spid='
    ),
    '借款单部门变更审批' => array(
        'viewUrl' => '?model=loan_loan_loan&action=toViewTab&isTemp=1&id=',
        'url' => '?model=loan_loan_loan&action=changeAudit&actType=audit&id=',
        'rtUrl' => '?model=loan_loan_loan&action=dealAfterAuditChange&spid='
    ),
	//	'报销单' => array(
	//		'url' => 'general/costmanage/reim/summary_detail.php?doAct=审批&BillNo=',
	//		'viewUrl' => 'general/costmanage/reim/summary_detail.php?doAct=审批&BillNo='
	//	),
	'报销审批' => array(
		'url' => '?model=finance_expense_exsummary&action=toAudit&id=',
		'viewUrl' => '?model=finance_expense_exsummary&action=toView&id=',
		'allStep' => 1,
		'rtUrl' => '?model=finance_expense_exsummary&action=dealAfterAudit&spid='
	),
	'延期报销单审批' => array(
		'url' => '?model=finance_expense_exsummary&action=toAudit&id=',
		'viewUrl' => '?model=finance_expense_exsummary&action=toView&id=',
		'allStep' => 1,
		'rtUrl' => '?model=finance_expense_exsummary&action=dealAfterAudit&spid='
	),
	'特别事项审批' => array(
		'url' => '?model=general_special_specialapply&action=toAudit&id=',
		'viewUrl' => '?model=general_special_specialapply&action=toView&id=',
		'rtUrl' => '?model=general_special_specialapply&action=dealAfterAudit&spid='
	),
	/********************************* 原OA系统中的审批流接入部分 ********************************/
	/**人事管理部分**/
	'职位说明书' => array(
		'viewUrl' => '?model=hr_position_positiondescript&action=toView&id=',
		'url' => '?model=hr_position_positiondescript&action=toView&id=',
		'isSkey' => '1'
	),
	'增员申请审批' => array(
		'viewUrl' => '?model=hr_recruitment_apply&action=toView&id=',
		'url' => '?model=hr_recruitment_apply&action=toView&actType=audit&id=',
		'rtUrl' => '?model=hr_recruitment_apply&action=dealAfterAudit&spid='
		//		'isSkey' => '1',
		//		'keyObj' => 'oa_hr_recruitment_apply'
	),
	'增员申请审批(计划外)' => array(
		'viewUrl' => '?model=hr_recruitment_apply&action=toView&id=',
		'url' => '?model=hr_recruitment_apply&action=toView&actType=audit&id=',
		'rtUrl' => '?model=hr_recruitment_apply&action=dealAfterAudit&spid='
		//		'isSkey' => '1',
		//		'keyObj' => 'oa_hr_recruitment_apply'
	),
	'增员申请修改审批' => array(
		'viewUrl' => '?model=hr_recruitment_apply&action=toAuditEditView&id=',
		'url' => '?model=hr_recruitment_apply&action=toAuditEditView&actType=audit&id=',
		'rtUrl' => '?model=hr_recruitment_apply&action=dealEditApply&spid='
		//		'isSkey' => '1',
		//		'keyObj' => 'oa_hr_recruitment_apply'
	),
	'内部推荐奖金' => array(
		'viewUrl' => '?model=hr_recruitment_recomBonus&action=toRead&id=',
		'url' => '?model=hr_recruitment_recomBonus&action=toRead&id=',
		'rtUrl' => '?model=hr_recruitment_recomBonus&action=postMail&spid='
		//		'isSkey' => '1',
		//		'keyObj' => 'oa_hr_recommend_bonus'
	),

	'内部推荐奖金(服务线)' => array(
		'viewUrl' => '?model=hr_recruitment_recomBonus&action=toRead&id=',
		'url' => '?model=hr_recruitment_recomBonus&action=toRead&id=',
		'rtUrl' => '?model=hr_recruitment_recomBonus&action=postMail&spid='
		//		'isSkey' => '1',
		//		'keyObj' => 'oa_hr_recommend_bonus'
	),
	'内部推荐奖金(非服务线)' => array(
		'viewUrl' => '?model=hr_recruitment_recomBonus&action=toRead&id=',
		'url' => '?model=hr_recruitment_recomBonus&action=toRead&id=',
		'rtUrl' => '?model=hr_recruitment_recomBonus&action=postMail&spid='
		//		'isSkey' => '1',
		//		'keyObj' => 'oa_hr_recommend_bonus'
	),


	'面试评估审批' => array(
		'viewUrl' => '?model=hr_recruitment_interview&action=toView&id=',
		'url' => '?model=hr_recruitment_interview&action=toRead&id=',
		'rtUrl' => '?model=hr_recruitment_interview&action=dealAfterAuditPass&spid='
	),
	'面试评估审批(管理岗位)' => array(
		'viewUrl' => '?model=hr_recruitment_interview&action=toView&id=',
		'url' => '?model=hr_recruitment_interview&action=toRead&id=',
		'rtUrl' => '?model=hr_recruitment_interview&action=dealAfterAuditPass&spid='
	),
	'面试评估审批(服务线)' => array(
		'viewUrl' => '?model=hr_recruitment_interview&action=toView&id=',
		'url' => '?model=hr_recruitment_interview&action=toRead&id=',
		'rtUrl' => '?model=hr_recruitment_interview&action=dealAfterAuditPass&spid='
	),
	'面试评估审批(本地化员工)' => array(
		'viewUrl' => '?model=hr_recruitment_interview&action=toView&id=',
		'url' => '?model=hr_recruitment_interview&action=toRead&id=',
		'rtUrl' => '?model=hr_recruitment_interview&action=dealAfterAuditPass&spid='
	),
	'面试评估审批(非本地化员工)' => array(
		'viewUrl' => '?model=hr_recruitment_interview&action=toView&id=',
		'url' => '?model=hr_recruitment_interview&action=toRead&id=',
		'rtUrl' => '?model=hr_recruitment_interview&action=dealAfterAuditPass&spid='
	),
	'离职申请审批' => array(
		'viewUrl' => '?model=hr_leave_leave&action=toView&id=',
		'url' => '?model=hr_leave_leave&action=toView&actType=audit&id=',
		'rtUrl' => '?model=hr_leave_leave&action=leaveMail&urlType=1&spid=',
		'allStep' => '1'
	),
	'离职交接清单确认' => array(
		'viewUrl' => '?model=hr_leave_handover&action=toHandoverList&id=',
		'url' => '?model=hr_leave_handover&action=toHandoverList&actType=audit&id=',
		'rtUrl' => '?model=hr_leave_handover&action=dealApproval&urlType=1&spid=',
		'allStep' => '1'
	),
	'调岗申请审批' => array(
		'viewUrl' => '?model=hr_transfer_transfer&action=toView&id=',
		'url' => '?model=hr_transfer_transfer&action=toView&actType=audit&id=',
		'rtUrl' => '?model=hr_transfer_transfer&action=dealTransfer&urlType=1&spid='
	),
	'试用考核评估' => array(
		'viewUrl' => '?model=hr_permanent_examine&action=toView&id=',
		'url' => '?model=hr_permanent_examine&action=toDirectorEdit&actType=audit&urlType=1&id=',
		'rtUrl' => '?model=hr_permanent_examine&action=applyTothem&urlType=1&spid=',
		'allStep' => '1'
	),
	'试用考核评估(高管)' => array(
		'viewUrl' => '?model=hr_permanent_examine&action=toView&id=',
		'url' => '?model=hr_permanent_examine&action=toDirectorEdit&actType=audit&urlType=1&id=',
		'rtUrl' => '?model=hr_permanent_examine&action=applyTothem&urlType=1&spid=',
		'allStep' => '1'
	),
	'试用考核评估(三级部门)' => array(
		'viewUrl' => '?model=hr_permanent_examine&action=toView&id=',
		'url' => '?model=hr_permanent_examine&action=toDirectorEdit&actType=audit&urlType=1&id=',
		'rtUrl' => '?model=hr_permanent_examine&action=applyTothem&urlType=1&spid=',
		'allStep' => '1'
	),
	'试用考核评估(总监)' => array(
		'viewUrl' => '?model=hr_permanent_examine&action=toView&id=',
		'url' => '?model=hr_permanent_examine&action=toDirectorEdit&actType=audit&urlType=1&id=',
		'rtUrl' => '?model=hr_permanent_examine&action=applyTothem&urlType=1&spid=',
		'allStep' => '1'
	),
	'任职资格申请审批' => array(
		'viewUrl' => '?model=hr_personnel_certifyapply&action=toViewApply&id=',
		'url' => '?model=hr_personnel_certifyapply&action=toViewApply&actType=audit&id=',
		'isSkey' => '1',
		'keyObj' => 'hr_personnel_certifyapply',
		'rtUrl' => '?model=hr_personnel_certifyapply&action=dealAfterAudit&spid='

	),
	'任职资格认证审批' => array(
		'viewUrl' => '?model=hr_certifyapply_cassess&action=toView&id=',
		'url' => '?model=hr_certifyapply_cassess&action=toAudit&id=',
		'isSkey' => '1',
		'keyObj' => 'hr_certifyapply_cassess',
		'rtUrl' => '?model=hr_certifyapply_cassess&action=dealAfterAudit&spid='
	),
	'员工辅导计划表' => array(
		'viewUrl' => '?model=hr_tutor_coachplan&action=toSimpleRead&id=',
		'url' => '?model=hr_tutor_coachplan&action=toSimpleRead&actType=audit&id=',
		'rtUrl' => '?model=hr_tutor_coachplan&action=confirmExa&spid='
	),
	'导师奖励方案' => array(
		'viewUrl' => '?model=hr_tutor_reward&action=toRead&id=',
		'url' => '?model=hr_tutor_reward&action=toRead&actType=audit&id=',
		'rtUrl' => '?model=hr_tutor_reward&action=confirmExa&spid='
	),
	'任职资格结果审批' => array(
		'viewUrl' => '?model=hr_certifyapply_certifyresult&action=toView&id=',
		'url' => '?model=hr_certifyapply_certifyresult&action=toAudit&id=',
		'isSkey' => '1',
		'keyObj' => 'hr_certifyapply_certifyresult',
		'rtUrl' => '?model=hr_certifyapply_certifyresult&action=dealAfterAudit&spid='
	),
	'部门建议审批' => array(
		'viewUrl' => '?model=hr_trialplan_trialdeptsuggest&action=toView&id=',
		'url' => '?model=hr_trialplan_trialdeptsuggest&action=toAudit&id=',
		'isSkey' => '1',
		'keyObj' => 'hr_trialplan_trialdeptsuggest'
		//		,
		//		'rtUrl' => '?model=hr_trialplan_trialdeptsuggest&action=dealAfterAudit&spid=',
		//		'allStep' => '1'
	),
	'部门建议审批(变更)' => array(
		'viewUrl' => '?model=hr_trialplan_trialdeptsuggest&action=toView&id=',
		'url' => '?model=hr_trialplan_trialdeptsuggest&action=toAudit&id=',
		'isSkey' => '1',
		'keyObj' => 'hr_trialplan_trialdeptsuggest'
		//		,
		//		'rtUrl' => '?model=hr_trialplan_trialdeptsuggest&action=dealAfterAudit&spid=',
		//		'allStep' => '1'
	),
	'招聘计划审批' => array(
		'viewUrl' => '?model=hr_recruitplan_plan&action=toView&id=',
		'url' => '?model=hr_recruitplan_plan&action=toView&actType=audit&id=',
		'isSkey' => '1',
		'keyObj' => 'hr_recruitplan_plan'
	),
	'招聘计划修改审批' => array(
		'viewUrl' => '?model=hr_recruitplan_plan&action=toView&id=',
		'url' => '?model=hr_recruitplan_plan&action=toView&actType=audit&id=',
		'isSkey' => '1',
		'keyObj' => 'hr_recruitplan_plan'
	)
,
	'计划内增员申请(服务线)' => array(
		'viewUrl' => '?model=hr_recruitment_apply&action=toView&id=',
		'url' => '?model=hr_recruitment_apply&action=toView&actType=audit&id=',
		'rtUrl' => '?model=hr_recruitment_apply&action=dealAfterAudit&spid='
		//'rtUrl' =>'?model=hr_recruitment_apply&action=afterEwf&actType=audit&id='
		/*'isSkey' => '1',
		'keyObj' => 'oa_hr_recruitment_apply'*/
	)
,
	'计划外增员申请(服务线)' => array(
		'viewUrl' => '?model=hr_recruitment_apply&action=toView&id=',
		'url' => '?model=hr_recruitment_apply&action=toView&actType=audit&id=',
		'rtUrl' => '?model=hr_recruitment_apply&action=dealAfterAudit&spid='
		//'rtUrl' =>'?model=hr_recruitment_apply&action=afterEwf&actType=audit&id='
		/*'isSkey' => '1',
		'keyObj' => 'oa_hr_recruitment_apply'*/
	),
	'增员申请实习生(服务线)' => array(
		'viewUrl' => '?model=hr_recruitment_apply&action=toView&id=',
		'url' => '?model=hr_recruitment_apply&action=toView&actType=audit&id=',
		'rtUrl' => '?model=hr_recruitment_apply&action=dealAfterAudit&spid='
		//'rtUrl' =>'?model=hr_recruitment_apply&action=afterEwf&actType=audit&id='
		/*'isSkey' => '1',
		'keyObj' => 'oa_hr_recruitment_apply'*/
	),
	'增员申请实习生(非服务线)' => array(
		'viewUrl' => '?model=hr_recruitment_apply&action=toView&id=',
		'url' => '?model=hr_recruitment_apply&action=toView&actType=audit&id=',
		'rtUrl' => '?model=hr_recruitment_apply&action=dealAfterAudit&spid='
		//'rtUrl' =>'?model=hr_recruitment_apply&action=afterEwf&actType=audit&id='
		/*'isSkey' => '1',
		'keyObj' => 'oa_hr_recruitment_apply'*/
	),
	'离职/换岗补充增员申请(服务线)' => array(
		'viewUrl' => '?model=hr_recruitment_apply&action=toView&id=',
		'url' => '?model=hr_recruitment_apply&action=toView&actType=audit&id=',
		'rtUrl' => '?model=hr_recruitment_apply&action=dealAfterAudit&spid='
		//'rtUrl' =>'?model=hr_recruitment_apply&action=afterEwf&actType=audit&id='
		/*'isSkey' => '1',
		'keyObj' => 'oa_hr_recruitment_apply'*/
	),
	'计划外增员申请(非服务线)' => array(
		'viewUrl' => '?model=hr_recruitment_apply&action=toView&id=',
		'url' => '?model=hr_recruitment_apply&action=toView&actType=audit&id=',
		'rtUrl' => '?model=hr_recruitment_apply&action=dealAfterAudit&spid='
		//'rtUrl' =>'?model=hr_recruitment_apply&action=afterEwf&actType=audit&id='
		/*'isSkey' => '1',
		'keyObj' => 'oa_hr_recruitment_apply'*/
	)
,
	'计划内增员申请(非服务线)' => array(
		'viewUrl' => '?model=hr_recruitment_apply&action=toView&id=',
		'url' => '?model=hr_recruitment_apply&action=toView&actType=audit&id=',
		'rtUrl' => '?model=hr_recruitment_apply&action=dealAfterAudit&spid='
		//'rtUrl' =>'?model=hr_recruitment_apply&action=afterEwf&actType=audit&id='
		/*	'isSkey' => '1',
			'keyObj' => 'oa_hr_recruitment_apply'*/
	)
,
	'离职/换岗补充增员申请(非服务线)' => array(
		'viewUrl' => '?model=hr_recruitment_apply&action=toView&id=',
		'url' => '?model=hr_recruitment_apply&action=toView&actType=audit&id=',
		'rtUrl' => '?model=hr_recruitment_apply&action=dealAfterAudit&spid='
		//'rtUrl' =>'?model=hr_recruitment_apply&action=afterEwf&actType=audit&id='
		/*	'isSkey' => '1',
			'keyObj' => 'oa_hr_recruitment_apply'*/
	),
	'增员申请(服务线其他)' => array(
		'viewUrl' => '?model=hr_recruitment_apply&action=toView&id=',
		'url' => '?model=hr_recruitment_apply&action=toView&actType=audit&id=',
		'rtUrl' => '?model=hr_recruitment_apply&action=dealAfterAudit&spid='
		//'rtUrl' =>'?model=hr_recruitment_apply&action=afterEwf&actType=audit&id='
		/*	'isSkey' => '1',
			'keyObj' => 'oa_hr_recruitment_apply'*/
	),
	'节假日加班申请审批' => array(
		'viewUrl' => '?model=hr_worktime_apply&action=toView&id=',
		'url' => '?model=hr_worktime_apply&action=toView&actType=audit&id='
	),

	'订票需求审批' => array(
		'viewUrl' => '?model=flights_require_require&action=toView&id=',
		'url' => '?model=flights_require_require&action=toView&actType=audit&id=',
		//		'rtUrl' => '?model=flights_require_require&action=dealAfterAudit&spid='
	),
	'项目外包申请审批' => array(
		'viewUrl' => '?model=outsourcing_outsourcing_apply&action=toView&id=',
		'url' => '?model=outsourcing_outsourcing_apply&action=toView&actType=audit&id=',
		'rtUrl' => '?model=outsourcing_outsourcing_apply&action=dealAfterAudit&spid='
	),
	'外包立项审批' => array(
		'viewUrl' => '?model=outsourcing_approval_basic&action=toView&id=',
		'url' => '?model=outsourcing_approval_basic&action=toView&actType=audit&id=',
		'rtUrl' => '?model=outsourcing_approval_basic&action=dealAfterAuditPass&spid='
	),
	'外包立项变更审批' => array(
		'viewUrl' => '?model=outsourcing_approval_basic&action=changeTab&id=',
		'url' => '?model=outsourcing_approval_basic&action=changeTab&actType=audit&id=',
		'rtUrl' => '?model=outsourcing_approval_basic&action=dealAfterAuditChange&spid='
	),
	'外包结算审批' => array(
		'viewUrl' => '?model=outsourcing_account_basic&action=toView&id=',
		'url' => '?model=outsourcing_account_basic&action=toView&actType=audit&id='
	),
	'项目外包扣款审批' => array(
		'viewUrl' => '?model=outsourcing_deduct_deduct&action=toView&id=',
		'url' => '?model=outsourcing_deduct_deduct&action=toView&actType=audit&id='
	),
	'外包供应商审批' => array(
		'viewUrl' => '?model=outsourcing_supplier_basicinfo&action=toTabView&id=',
		'url' => '?model=outsourcing_supplier_basicinfo&action=toTabView&actType=audit&id='
	),
	'外包供应商变更审批' => array(
		'viewUrl' => '?model=outsourcing_supplier_basicinfo&action=toTabChangeView&id=',
		'url' => '?model=outsourcing_supplier_basicinfo&action=toTabChangeView&actType=audi&id=',
		'rtUrl' => '?model=outsourcing_supplier_basicinfo&action=dealChange&actType=audit&spid='
	),
	'产品备货销售申请审批' => array(
		'viewUrl' => '?model=stockup_apply_apply&action=toView&id=',
		'url' => '?model=stockup_apply_apply&action=toView&actType=audit&id=',
		'rtUrl' => '?model=stockup_apply_apply&action=appAfterMail&spid='
	),
	'产品备货交付申请审批' => array(
		'viewUrl' => '?model=stockup_application_application&action=toView&id=',
		'url' => '?model=stockup_application_application&action=toView&actType=audit&id='
	),
	'变更审批付款日期' => array(
		'viewUrl' => '?model=finance_payablesapply_payablesapplychange&action=toAuditView&id=',
		'url' => '?model=finance_payablesapply_payablesapplychange&action=toAuditView&id=',
		'rtUrl' => '?model=finance_payablesapply_payablesapplychange&action=dealAfterAudit&spid='
	),
	'生产领料申请审批' => array(
		'viewUrl' => '?model=produce_plan_picking&action=toView&id=',
		'url' => '?model=produce_plan_picking&action=toView&actType=audit&id=',
		'rtUrl' => '?model=produce_plan_picking&action=dealAfterAudit&spid='
	),
	'生产申请审批' => array(
		'viewUrl' => '?model=produce_apply_produceapply&action=toView&id=',
		'url' => '?model=produce_apply_produceapply&action=toView&actType=audit&id=',
		'rtUrl' => '?model=produce_apply_produceapply&action=dealAfterAudit&spid='
	),
	'生产申请变更审批' => array(
		'url' => '?model=produce_apply_produceapply&action=toChangeTab&actType=actType&id=',//审批时页面查看路径
		'viewUrl' => '?model=produce_apply_produceapply&action=toChangeTab&id=',//列表页面查看路径
		'rtUrl' => '?model=produce_apply_produceapply&action=dealAfterAuditChange&spid='
	),
	'生产计划关闭审批' => array(
		'viewUrl' => '?model=produce_plan_produceplan&action=toCloseView&id=',//列表页面查看路径
		'url' => '?model=produce_plan_produceplan&action=toCloseView&actType=audit&id='
	),
    '呆料报废申请审批' => array(
        'viewUrl' => '?model=stock_outstock_stockout&action=toView&docType=CKDLBF&id=',//报废呆料出库单页面查看路径
        'url' => '?model=stock_outstock_stockout&action=toView&docType=CKDLBF&actType=audit&id=',
        'rtUrl' => '?model=stock_outstock_stockout&action=dealAfterAudit&docType=DLBF&spid='
    )
);

/**
 * 存在变更业务对象判断时使用类名和方法名
 */
$changeFunArr = array(
	'采购合同审批' => array( //采购订单
		'className' => 'model_purchase_contract_purchasecontract',
		'funName' => 'isTemp',
		'taskName' => '采购订单变更审批',
		'rtUrl' => '?model=purchase_change_contractchange&action=confirmChangeToApprovalNo&urlType=1&spid=',
		'seachCode' => 'purchasecontractNotChange'
	)
);


/**
 * 不需要处理的审批流类型
 */
$notInWorkflow = array(
	'工资审批'
);


/**
 * 收单工作时调用方法
 */
$receiveActionArr = array(
	'报销审批' => array(
		'className' => 'model_finance_expense_expense',
		'funName' => 'receiveForm'
	),
	'延期报销单审批' => array(
		'className' => 'model_finance_expense_expense',
		'funName' => 'receiveForm'
	),
    '借款单' => array(
        'className' => 'model_loan_loan_loan',
        'funName' => 'receiveForm'
    ),
	'服务项目借款单' => array(
        'className' => 'model_loan_loan_loan',
        'funName' => 'receiveForm'
    )
);

/**
 * 退单工作时调用方法
 */
$backActionArr = array(
	'报销审批' => array(
		'className' => 'model_finance_expense_expense',
		'funName' => 'backForm'
	),
	'延期报销单审批' => array(
		'className' => 'model_finance_expense_expense',
		'funName' => 'backForm'
	),
    '借款单' => array(
        'className' => 'model_loan_loan_loan',
        'funName' => 'backForm'
    )
	,
    '服务项目借款单' => array(
        'className' => 'model_loan_loan_loan',
        'funName' => 'backForm'
    )
);


/**
 * TODO可批量审批业务注册 -- 注意，调用的是Model层方法
 * className - 业务调用类名
 * funName - 业务调用方法名
 */
$batchAuditArr = array(
	'外包合同审批' => array(
		'className' => 'model_contract_outsourcing_outsourcing',
		'funName' => 'dealAfterAudit_d'
	),
	'外包合同变更审批' => array(
		'className' => 'model_contract_outsourcing_outsourcing',
		'funName' => 'dealAfterAuditChange_d'
	),
	'外包合同立项付款申请' => array(
		'className' => 'model_contract_outsourcing_outsourcing',
		'funName' => 'dealAfterAuditPayapply_d'
	),
	'外包合同关闭审批' => array(),
	'其他合同审批' => array(
		'className' => 'model_contract_other_other',
		'funName' => 'dealAfterAudit_d'
	),
	'其他合同变更审批' => array(
		'className' => 'model_contract_other_other',
		'funName' => 'dealAfterAuditChange_d'
	),
	'其他合同立项付款申请' => array(
		'className' => 'model_contract_other_other',
		'funName' => 'dealAfterAuditPayapply_d'
	),
	'其他合同关闭审批' => array(),
	'报销审批' => array(
		'className' => 'model_finance_expense_exsummary',
		'funName' => 'dealAfterAudit_d'
	),
	'延期报销单审批' => array(
		'className' => 'model_finance_expense_exsummary',
		'funName' => 'dealAfterAudit_d'
	),
	'开票申请' => array(
		'className' => 'model_finance_invoiceapply_invoiceapply',
		'funName' => 'dealAfterAudit_d'
	),
	'异地开票申请' => array(),
	'采购付款申请' => array(
		'className' => 'model_finance_payablesapply_payablesapply',
		'funName' => 'workflowCallBack'
	),
	'采购退款申请' => array(
		'className' => 'model_finance_payablesapply_payablesapply',
		'funName' => 'workflowCallBack'
	),
	'付款申请' => array(
		'className' => 'model_finance_payablesapply_payablesapply',
		'funName' => 'workflowCallBack'
	),
	'工程项目审批' => array(
		'className' => 'model_engineering_project_esmproject',
		'funName' => 'dealAfterAudit_d'
	),
    '工程项目完工审批' => array(
        'className' => 'model_engineering_project_esmproject',
        'funName' => 'dealAfterCompleteAudit_d'
    ),
	'工程项目变更' => array(
		'className' => 'model_engineering_change_esmchange',
		'funName' => 'dealAfterAudit_d'
	),
	'工程项目关闭审批' => array(
		'className' => 'model_engineering_close_esmclose',
		'funName' => 'dealAfterAudit_d'
	),
	'工程项目周报审批' => array(
		'className' => 'model_engineering_project_statusreport',
		'funName' => 'dealAfterAudit_d'
	),
	'质检报告审批' => array(
		'className' => 'model_produce_quality_qualityereport',
		'funName' => 'workflowCallBack'
	),
    '呆料报废质检审批' => array(
        'className' => 'model_produce_quality_qualityereport',
        'funName' => 'workflowCallBack'
    ),
	'赔偿单审批' => array(
		'className' => 'model_finance_compensate_compensate',
		'funName' => 'workflowCallBack'
	),
	'特别事项审批' => array(
		'className' => 'model_general_special_specialapply',
		'funName' => 'dealAfterAudit_d'
	),
	'项目设备申请' => array(),
	'借试用归还审批' => array(
		'className' => 'model_projectmanagent_borrowreturn_borrowreturn',
		'funName' => 'workflowCallBack'
	),
	'离职申请审批' => array(
		'className' => 'model_hr_leave_leave',
		'funName' => 'dealAfterAudit_d'
	),
	'外包立项审批' => array(
		'className' => 'model_outsourcing_approval_basic',
		'funName' => 'workflowCallBack'
	),
	'试用项目申请' => array(
		'className' => 'model_projectmanagent_trialproject_trialproject',
		'funName' => 'workflowCallBack'
	),
	'试用项目延期申请' => array(
		'className' => 'model_projectmanagent_trialproject_extension',
		'funName' => 'workflowCallBack'
	),
	'合同审批A' => array(
		'className' => 'model_contract_contract_contract',
		'funName' => 'workflowCallBack'
	),
	'合同变更审批A' => array(
		'className' => 'model_contract_contract_contract',
		'funName' => 'workflowCallBack_change'
	),
	'合同审批B' => array(
		'className' => 'model_contract_contract_contract',
		'funName' => 'workflowCallBack'
	),
	'合同变更审批B' => array(
		'className' => 'model_contract_contract_contract',
		'funName' => 'workflowCallBack_change'
	),
	'合同审批C' => array(
		'className' => 'model_contract_contract_contract',
		'funName' => 'workflowCallBack'
	),
	'合同变更审批C' => array(
		'className' => 'model_contract_contract_contract',
		'funName' => 'workflowCallBack_change'
	),
	'换货申请审批' => array(
		'className' => 'model_projectmanagent_exchange_exchange',
		'funName' => 'workflowCallBack'
	),
	'销售退货申请' => array(
		'className' => 'model_projectmanagent_return_return',
		'funName' => 'workflowCallBack'
	),
	'盖章申请审批' => array(
		'className' => 'model_contract_stamp_stampapply',
		'funName' => 'workflowCallBack'
	),
	'合同盖章申请审批' => array(
		'className' => 'model_contract_stamp_stampapply',
		'funName' => 'workflowCallBack'
	),
	'扣款申请审批' => array(
		'className' => 'model_contract_deduct_deduct',
		'funName' => 'workflowCallBack'
	),
	'补库审批' => array(
		'className' => 'model_stock_fillup_fillup',
		'funName' => 'workflowCallBack'
	),
	'生产采购申请审批' => array(
		'className' => 'model_purchase_plan_basic',
		'funName' => 'workflowCallBack'
	),
	'采购订单审批' => array(
		'className' => 'model_purchase_contract_purchasecontract',
		'funName' => 'workflowCallBack'
	),
	'采购订单中止审批' => array(
		'className' => 'model_purchase_contract_purchasecontract',
		'funName' => 'workflowCallBack_close'
	),
	'费用减免申请单审批' => array(
		'className' => 'model_service_reduce_reduceapply',
		'funName' => 'workflowCallBack'
	),
	'供应商季度考核审批' => array(
		'className' => 'model_supplierManage_assessment_supasses',
		'funName' => 'workflowCallBack'
	),
	'新供应商评估' => array(
		'className' => 'model_supplierManage_assessment_supasses',
		'funName' => 'workflowCallBack'
	),
	'租车申请审批' => array(
		'className' => 'model_outsourcing_vehicle_rentalcar',
		'funName' => 'workflowCallBack'
	),
	'租车合同审批' => array(
		'className' => 'model_outsourcing_contract_rentcar',
		'funName' => 'workflowCallBack'
	),
	'租车合同变更审批' => array(
		'className' => 'model_outsourcing_contract_rentcar',
		'funName' => 'workflowCallBack_change'
	),
	'租车登记审批' => array(
		'className' => 'model_outsourcing_vehicle_allregister',
		'funName' => 'workflowCallBack'
	),
    '呆料报废申请审批' => array(
        'className' => 'model_stock_outstock_stockout',
        'funName' => 'workflowCallBack_idleScrap'
    ),
	'借试用变更审批' => array(
		'className' => 'model_projectmanagent_borrow_borrow',
		'funName' => 'workflowCallBack_change'
	),
    '设备赠送' => array(
        'className' => 'model_projectmanagent_present_present',
        'funName' => 'workflowCallBack_equConfirm'
    ),
	'赠送变更审批' => array(
		'className' => 'model_projectmanagent_present_present',
		'funName' => 'workflowCallBack'
	),
	'采购订单审批(含付款申请)' => array(
		'className' => 'model_purchase_contract_purchasecontract',
		'funName' => 'workflowCallBack'
	),
	'项目外包申请审批' => array(
		'className' => 'model_outsourcing_outsourcing_apply',
		'funName' => 'dealAfterAudit_d'
	),
    '借款单' => array(
        'className' => 'model_loan_loan_loan',
        'funName' => 'dealAfterAudit_d'
    ),
    '借款变更单' => array(
        'className' => 'model_loan_loan_loan',
        'funName' => 'dealAfterAuditChange_d'
    ),
	'服务项目借款单' => array(
        'className' => 'model_loan_loan_loan',
        'funName' => 'dealAfterAudit_d'
    ),
    '服务项目借款变更单' => array(
        'className' => 'model_loan_loan_loan',
        'funName' => 'dealAfterAuditChange_d'
    ),
    '借试用发货物料变更' => array(
        'className' => 'model_projectmanagent_borrow_borrowequ',
        'funName' => 'dealAfterChangeAudit_d'
    )
);