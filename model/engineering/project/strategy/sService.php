<?php
/*
 * Created on 2012-5-18
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

include_once( WEB_TOR . 'model/engineering/project/iesmproject.php');

/**
 * ������Ŀ����
 */
class model_engineering_project_strategy_sService extends model_base implements iesmproject{

	//��Ӧҵ����
	private $thisClass = 'model_engineering_serviceContract_serviceContract';

	/**
	 * ��ȡҵ�������Ϣ
	 */
	function getObjInfo_i($obj){
		$innerObjDao = new $this->thisClass();
		$innerObj = $innerObjDao->get_d($obj['contractId']);

		$innerObj['contractCode'] = $innerObj['projectCode'];
		unset($innerObj['projectCode']);

		$innerObj['budgetAll'] = $innerObj['budgetMoney'];
		unset($innerObj['budgetMoney']);

		$innerObj['proName'] = $innerObj['province'];
		unset($innerObj['province']);

		return $innerObj;
	}

	/**
	 * ����ҵ����
	 */
	function businessAdd_i($obj,$mainDao){
		//����Դ��״̬Ϊ ִ����
		$innerObjDao = new $this->thisClass();

		try{
			$innerObjDao->start_d();

			$thisWorkRate = $mainDao->getAllWorkRateByType_d($obj['contractId'],$obj['contractType']);

			if($thisWorkRate == 100){
				$innerObjDao->update(array('id' => $obj['contractId']),array('dealStatus'=>1));
			}

            $orderDao = new model_projectmanagent_order_order();
            //��ȡ�ⲿ��ͬ����
            $outContractType = $mainDao->rtOutCountType($obj['contractType']);
            //��������
            $contractArr =  array('id'=>$obj['contractId'],'tablename' =>$outContractType);
//            //���ú�ͬҵ����
            $orderDao->updateProjectProcess($contractArr);

			$innerObjDao->commit_d();
			return true;
		}catch(exception $e){
			$innerObjDao->rollBack();
			throw $e;
		}
	}

	/**
	 * ȷ��ҵ����
	 */
	function businessConfirm_i($obj,$mainDao = null){
		return true;
//		if(empty($mainDao)){
//			return true;
//		}else{
//			$innerObjDao = new model_projectmanagent_order_order();
//
//			try{
//				$innerObjDao->start_d();
//				//��ȡ�ⲿ��ͬ����
//				$outContractType = $mainDao->rtOutCountType($obj['contractType']);
//				//��������
//				$contractArr =  array('id'=>$obj['contractId'],'tablename' =>$outContractType);
//				//���ú�ͬҵ����
//				$innerObjDao->updateProjectProcess($contractArr);
//
//				$innerObjDao->commit_d();
//				return true;
//			}catch(exception $e){
//				$innerObjDao->rollBack();
//				throw $e;
//			}
//		}
	}

	/**
	 * ɾ��ҵ����
	 */
	function businessDelete_i($obj){
		//����Դ��״̬Ϊ ִ����
		$innerObjDao = new $this->thisClass();

		try{
			$innerObjDao->start_d();

			$innerObjDao->update(array('id' => $obj['contractId']),array('dealStatus'=>0));

			$innerObjDao->commit_d();
			return true;
		}catch(exception $e){
			$innerObjDao->rollBack();
			throw $e;
		}
	}

	/**
	 * �ر�
	 */
	function businessClose_i($obj){

		return true;
//		//����Դ��״̬Ϊ ִ����
//		$innerObjDao = new $this->thisClass();
//
//		try{
//			$innerObjDao->start_d();
//
//			$innerObjDao->update(array('id' => $obj['contractId']),array('status'=>'4'));
//
//			$innerObjDao->commit_d();
//			return true;
//		}catch(exception $e){
//			$innerObjDao->rollBack();
//			throw $e;
//		}
	}

	/**
	 * ������Ŀҵ�����
	 */
	function businessConnect_i($obj,$mainDao){
		//����Դ��״̬Ϊ ִ����
		$innerObjDao = new $this->thisClass();

		try{
			$innerObjDao->start_d();

			$thisWorkRate = $mainDao->getAllWorkRateByType_d($obj['contractId'],$obj['contractType']);

			if($thisWorkRate == 100){
				$innerObjDao->update(array('id' => $obj['contractId']),array('dealStatus'=>1));
			}

			$innerObjDao->commit_d();
			return true;
		}catch(exception $e){
			$innerObjDao->rollBack();
			throw $e;
		}
	}

	/**
	 * ��ȡ������Ŀ�³̵���Ϣ
	 */
	function businessForCharter_i($obj,$mainDao){
		//����Դ��״̬Ϊ ִ����
		$contractDao = new $this->thisClass();
    	$contractArr = $contractDao->get_d($obj['contractId'],'none');
		$contractArr['tablename'] = $obj['contractType'];
		$contractArr['contractType'] = 'GCXMYD-02';
		$contractArr['contractMoney'] = $contractArr['orderMoney']*1 > 0 ? $contractArr['orderMoney'] : $contractArr['orderTempMoney'];

		return $contractArr;
	}
}
?>
