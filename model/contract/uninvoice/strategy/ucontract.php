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
class model_contract_uninvoice_strategy_ucontract extends model_base implements iuninvoice{

	/************************页面渲染**********************/
	private $thisClass = 'model_contract_contract_contract';

	/**
	 * 获取数据信息
	 */
	function getObjInfo_d($obj){
		$innerObjDao = new $this->thisClass();
		$innerObj = $innerObjDao->find(array('id' => $obj['objId']),null,'id,contractCode,contractName,contractMoney,invoiceMoney,incomeMoney,uninvoiceMoney');

		$innerObj['canUninvoiceMoney'] = bcsub(bcsub($innerObj['incomeMoney'],$innerObj['invoiceMoney'],2),$innerObj['uninvoiceMoney'],2);
		return $innerObj;
	}

	/**
	 * 更新数据
	 */
	function updateUninvoiceMoney_d($objId,$uninvoiceMoney,$remark = ''){
		$innerObjDao = new $this->thisClass();

		try{
			$innerObjDao->update(array('id' => $objId),array('uninvoiceMoney' => $uninvoiceMoney));
		}catch(exception $e){
			throw $e;
			echo $e->getMessage();
			return false;
		}
	}
}

?>
