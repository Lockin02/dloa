<?php
/*
 * Created on 2011-3-13
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
include( WEB_TOR . 'model/finance/invoiceapply/iinvoiceapply.php');
/**
 * ���ۿ�Ʊ�������
 */
class model_finance_invoiceapply_strategy_contract extends model_base implements iinvoiceapply{

	/************************ҳ����Ⱦ**********************/
	private $thisClass = 'model_contract_contract_contract';

	/****************************ҵ��ӿ�************************/

	/**
	 * ��ȡ������Ϣ
	 */
	function getObjInfo_d($obj){
		$innerObjDao = new $this->thisClass();
		$innerObj = $innerObjDao->get_d($obj['objId']);

		//��Ʊ������
		$innerObj['money']  = $innerObj['currency'] == '�����' ? $innerObj['contractMoney'] : $innerObj['contractMoneyCur']; // ������ֽ��
        $innerObj['contLocal'] = $innerObj['contractMoney']; // ����ҽ��

		//ʣ��ɿ�����ͬ����ɿ���
		$innerObj['conCanApply'] = $innerObj['currency'] == '�����' ?
            bcsub(bcsub($innerObj['money'],$innerObj['deductMoney'],2),$innerObj['uninvoiceMoney'],2) :
            $innerObj['contractMoney']
        ;

		//ҵ����
		$innerObj['rObjCode']  = $innerObj['objCode']; 
		unset($innerObj['objCode']);

        // ������˾�ֶ��л� signSubjectΪ��ͬ������˾,��Ʊȡ��ͬ��ǩԼ��˾formBelong
        // $innerObj['formBelong']  = $innerObj['signSubject'];
        // $innerObj['formBelongName']  = $innerObj['signSubjectName'];
        // $innerObj['businessBelong']  = $innerObj['signSubject'];
        // $innerObj['businessBelongName']  = $innerObj['signSubjectName'];

		//����
		$innerObj['managerName'] = $innerObj['areaPrincipal'];
		$innerObj['managerId'] = $innerObj['areaPrincipalId'];
        $innerObj['customerProvince'] = $innerObj['contractProvince'];
        $innerObj['linkPhone'] = '';
        $innerObj['linkMan'] = '';
        $innerObj['address'] = '';
		return $innerObj;
	}

	/**
	 * ������Ʊ����ʱ��Ⱦ��Ʊ��ϸ��Ϣ
	 */
	function initAdd_d($obj){
		$obj['invoicePlans'] = $this->initPlanEdit($obj['invoice']);
		unset($obj['invoice']);
		return $obj;
	}

	/**
	 * ��ȡ������Ϣ
	 */
	public function getObjInfoInit_d($obj){
		$invoiceDao = new model_projectmanagent_order_invoice();
		return $invoiceDao->getDetail_d($obj['objId']);
	}

	/**
	 * �༭��Ʊ����ʱ��Ⱦ��Ʊ��ϸ��Ϣ
	 */
	function initEdit_d($rows){
		return $this->initPlanEdit($rows);
	}

	/**
	 * �鿴��Ʊ����ʱ��Ⱦ��Ʊ��ϸ��Ϣ
	 */
	function initView_d($rows){
		return $this->initPlanView($rows);
	}

	/**
	 * ��ȡ��Ʊ��ϸ����
	 */
	function getDetail($obj){}

	/**
	 *  ����ҵ����
	 */
	function businessDeal_i($obj,$mainObj){
		//��ȡ��������
		$applyedMoney = $mainObj->getApplyedMoneyNew_d($obj['objId'],$obj['objType']);

		if(!empty($obj['objId'] )){
			//��ʼ����ͬ����
			$innerObjDao = new $this->thisClass();

			//������������
			$innerObjDao->update(array('id' => $obj['objId']),array('invoiceApplyMoney' => $applyedMoney));
		}
	}
}