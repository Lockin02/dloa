<?php
/*
 * Created on 2011-3-13
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
include( WEB_TOR . 'model/finance/invoiceapply/iinvoiceapply.php');
/**
 * 销售开票申请策略
 */
class model_finance_invoiceapply_strategy_contract extends model_base implements iinvoiceapply{

	/************************页面渲染**********************/
	private $thisClass = 'model_contract_contract_contract';

	/****************************业务接口************************/

	/**
	 * 获取数据信息
	 */
	function getObjInfo_d($obj){
		$innerObjDao = new $this->thisClass();
		$innerObj = $innerObjDao->get_d($obj['objId']);

		//开票申请金额
		$innerObj['money']  = $innerObj['currency'] == '人民币' ? $innerObj['contractMoney'] : $innerObj['contractMoneyCur']; // 申请币种金额
        $innerObj['contLocal'] = $innerObj['contractMoney']; // 人民币金额

		//剩余可开金额（合同本身可开）
		$innerObj['conCanApply'] = $innerObj['currency'] == '人民币' ?
            bcsub(bcsub($innerObj['money'],$innerObj['deductMoney'],2),$innerObj['uninvoiceMoney'],2) :
            $innerObj['contractMoney']
        ;

		//业务编号
		$innerObj['rObjCode']  = $innerObj['objCode']; 
		unset($innerObj['objCode']);

        // 归属公司字段切换 signSubject为合同归属公司,开票取合同的签约公司formBelong
        // $innerObj['formBelong']  = $innerObj['signSubject'];
        // $innerObj['formBelongName']  = $innerObj['signSubjectName'];
        // $innerObj['businessBelong']  = $innerObj['signSubject'];
        // $innerObj['businessBelongName']  = $innerObj['signSubjectName'];

		//主管
		$innerObj['managerName'] = $innerObj['areaPrincipal'];
		$innerObj['managerId'] = $innerObj['areaPrincipalId'];
        $innerObj['customerProvince'] = $innerObj['contractProvince'];
        $innerObj['linkPhone'] = '';
        $innerObj['linkMan'] = '';
        $innerObj['address'] = '';
		return $innerObj;
	}

	/**
	 * 新增开票申请时渲染开票详细信息
	 */
	function initAdd_d($obj){
		$obj['invoicePlans'] = $this->initPlanEdit($obj['invoice']);
		unset($obj['invoice']);
		return $obj;
	}

	/**
	 * 获取订单信息
	 */
	public function getObjInfoInit_d($obj){
		$invoiceDao = new model_projectmanagent_order_invoice();
		return $invoiceDao->getDetail_d($obj['objId']);
	}

	/**
	 * 编辑开票申请时渲染开票详细信息
	 */
	function initEdit_d($rows){
		return $this->initPlanEdit($rows);
	}

	/**
	 * 查看开票申请时渲染开票详细信息
	 */
	function initView_d($rows){
		return $this->initPlanView($rows);
	}

	/**
	 * 获取开票详细内容
	 */
	function getDetail($obj){}

	/**
	 *  操作业务处理
	 */
	function businessDeal_i($obj,$mainObj){
		//获取已申请金额
		$applyedMoney = $mainObj->getApplyedMoneyNew_d($obj['objId'],$obj['objType']);

		if(!empty($obj['objId'] )){
			//初始化合同对象
			$innerObjDao = new $this->thisClass();

			//更新已申请金额
			$innerObjDao->update(array('id' => $obj['objId']),array('invoiceApplyMoney' => $applyedMoney));
		}
	}
}