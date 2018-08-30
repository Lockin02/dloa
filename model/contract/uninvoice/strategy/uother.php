<?php
/*
 * Created on 2011-3-13
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

include( WEB_TOR . 'model/contract/uninvoice/iuninvoice.php');
/**
 * ���ۿ�Ʊ�������
 */
class model_contract_uninvoice_strategy_uother extends model_base implements iuninvoice{

	/************************ҳ����Ⱦ**********************/
	private $thisClass = 'model_contract_other_other';

	/**
	 * ��ȡ������Ϣ
	 */
	function getObjInfo_d($obj){
		$innerObjDao = new $this->thisClass();
		$innerObj = $innerObjDao->find(array('id' => $obj['objId']),null,'id,orderCode,orderName,orderMoney,uninvoiceMoney');

		// ǷƱ���
		$needInvotherMoney = $innerObjDao->getNeedInvotherMoney_d($innerObj['orderCode']);

		$innerObj['contractMoney'] = $innerObj['orderMoney'];

//		//��ȡ�����ѿ���Ʊ���
//		$invoiceDao = new model_finance_invoice_invoice();
//		$innerObj['invoiceMoney'] = $invoiceDao->getInvoicedMoney_d($obj);
//
//		//��ȡ���󿪵�����
//		$incomeDao = new model_finance_income_incomeAllot();
//		$innerObj['incomeMoney'] = $incomeDao->getIncomeMoney_d($obj);

		//��ȡ��ͬ��������
		$objData = $innerObjDao->getInfoAndPay_d($innerObj['id']);
		$innerObj['incomeMoney'] = $objData['payedMoney'];// ԭ�������Ϊ�б�����Ѹ�����
		$innerObj['invoiceMoney'] = $objData['invotherMoney'];// ԭ����Ʊ����Ϊ�б�������շ�Ʊ���

//		$innerObj['canUninvoiceMoney'] = bcsub(bcsub($innerObj['incomeMoney'],$innerObj['invoiceMoney'],2),$innerObj['uninvoiceMoney'],2);
		// �ɲ���Ʊ������0��С�ڵ���ǷƱ��� change by huanghaojin pms2139 2016-10-26
		$innerObj['canUninvoiceMoney'] = ($needInvotherMoney <= 0 )? 0.00 : $needInvotherMoney;
		return $innerObj;
	}

	/**
	 * ��������
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
