<?php
/*
 * Created on 2011-3-13
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

include( WEB_TOR . 'model/contract/uninvoice/iuninvoice.php');
/**
 * 销售开票申请策略
 */
class model_contract_uninvoice_strategy_uother extends model_base implements iuninvoice{

	/************************页面渲染**********************/
	private $thisClass = 'model_contract_other_other';

	/**
	 * 获取数据信息
	 */
	function getObjInfo_d($obj){
		$innerObjDao = new $this->thisClass();
		$innerObj = $innerObjDao->find(array('id' => $obj['objId']),null,'id,orderCode,orderName,orderMoney,uninvoiceMoney');

		// 欠票金额
		$needInvotherMoney = $innerObjDao->getNeedInvotherMoney_d($innerObj['orderCode']);

		$innerObj['contractMoney'] = $innerObj['orderMoney'];

//		//获取对象已开发票金额
//		$invoiceDao = new model_finance_invoice_invoice();
//		$innerObj['invoiceMoney'] = $invoiceDao->getInvoicedMoney_d($obj);
//
//		//获取对象开到款金额
//		$incomeDao = new model_finance_income_incomeAllot();
//		$innerObj['incomeMoney'] = $incomeDao->getIncomeMoney_d($obj);

		//获取合同付款数据
		$objData = $innerObjDao->getInfoAndPay_d($innerObj['id']);
		$innerObj['incomeMoney'] = $objData['payedMoney'];// 原到款金额改为列表里的已付款金额
		$innerObj['invoiceMoney'] = $objData['invotherMoney'];// 原开发票金额改为列表里的已收发票金额

//		$innerObj['canUninvoiceMoney'] = bcsub(bcsub($innerObj['incomeMoney'],$innerObj['invoiceMoney'],2),$innerObj['uninvoiceMoney'],2);
		// 可不开票金额大于0，小于等于欠票金额 change by huanghaojin pms2139 2016-10-26
		$innerObj['canUninvoiceMoney'] = ($needInvotherMoney <= 0 )? 0.00 : $needInvotherMoney;
		return $innerObj;
	}

	/**
	 * 更新数据
	 */
	function updateUninvoiceMoney_d($objId,$uninvoiceMoney,$remark = '',$extRecord = array()){
		$innerObjDao = new $this->thisClass();

		try{
			$result = $innerObjDao->update(array('id' => $objId),array('uninvoiceMoney' => $uninvoiceMoney));
			if($result){
				$recordArr['objId'] = $objId;
				$recordArr['costType'] = "uninvoiceMoney";
				$recordArr['costAmount'] = isset($extRecord['costAmount'])? $extRecord['costAmount'] : $uninvoiceMoney;
				$recordArr['remarks'] = $remark;
				unset($extRecord['costAmount']);
				$innerObjDao->addCostChangeRecord($recordArr,$extRecord);
			}

		}catch(exception $e){
			throw $e;
			echo $e->getMessage();
			return false;
		}
	}
}

?>
