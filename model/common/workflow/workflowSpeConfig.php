<?php
/*
 * Created on 2012-10-16
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 * 审批特殊配置信息，被配置的人员可以进入特定的审批页面
 */

$speSetArr = array(
	//报销审批 - 默认设置 admin和合同管理员
	'报销审批' => array(
		'admin' => '?model=finance_expense_exsummary&action=toAuditEdit&id=', //审批可编辑页
		'xiufang.tang' => '?model=finance_expense_exsummary&action=toAuditEdit&id=',
		'huiru.xing' => '?model=finance_expense_exsummary&action=toAuditEdit&id=',
		'gaowen.liang' => '?model=finance_expense_exsummary&action=toAuditEdit&id=',
		'qianyou.lin' => '?model=finance_expense_exsummary&action=toAuditEdit&id=',
		'lilan.he' => '?model=finance_expense_exsummary&action=toAuditEdit&id=',
		'limin.chen' => '?model=finance_expense_exsummary&action=toAuditEdit&id=',
		'weiying.li' => '?model=finance_expense_exsummary&action=toAuditEdit&id=',
        'cuiting.cai' => '?model=finance_expense_exsummary&action=toAuditEdit&id=',
		'yongjun.zhuo' => '?model=finance_expense_exsummary&action=toAuditEdit&id=',
		'zezhi.cao' => '?model=finance_expense_exsummary&action=toAuditEdit&id='
	)
);
/**
 * 默认审核加载
 * 内部数组 type top(顶部)/bottom(底部)
 * 内部数组 item 流程名称
 * 内部数组 userid 审批人
 */
$defaultAppendArr = array(
	'特别事项申请审批' => array(
//		array(
//			'type' => 'top',
//			'item' => '前置审批',
//			'userid' => 'hao.yuan'
//		),
		array(
			'type' => 'bottom',
			'item' => '后置审批',
			'userid' => 'xiufang.tang,weiying.li,yongjun.zhuo,cuiting.cai,yingying.zhang,minxian.zhong'
		)
	)
);
