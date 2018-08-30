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
class model_engineering_project_strategy_sTrialproject extends model_base implements iesmproject{

	//��Ӧҵ����
	private $thisClass = 'model_projectmanagent_trialproject_trialproject';

	/**
	 * ��ȡҵ�������Ϣ
	 */
	function getObjInfo_i($obj){
		$innerObj = $this->getRawInfo_i($obj['contractId']);

		$innerObj['contractCode'] = $innerObj['projectCode'];

		$innerObj['budgetAll'] = $innerObj['budgetMoney'];
		unset($innerObj['budgetMoney']);

		$innerObj['planBeginDate'] = $innerObj['beginDate'];
		unset($innerObj['beginDate']);

		$innerObj['planEndDate'] = $innerObj['closeDate'];
		unset($innerObj['closeDate']);

		$innerObj['expectedDuration'] = (strtotime($innerObj['planEndDate']) - strtotime($innerObj['planBeginDate']))/86400 + 1;

		//����Դ��ʡ���Զ���ѯ��������
		$managerDao = new model_engineering_officeinfo_manager();
		$provinceObj = $managerDao->getManager_d($innerObj['province']);
		$innerObj['areaManagerId'] = $provinceObj['areaManagerId'];
		$innerObj['areaManager'] = $provinceObj['areaManager'];

		return $innerObj;
	}

	/**
	 * ����ҵ����
	 */
	function businessAdd_i($obj){
		//����Դ��״̬Ϊ ִ����
		$innerObjDao = new $this->thisClass();

		try{
			$innerObjDao->update(array('id' => $obj['contractId']),array('status'=>'3'));

			return true;
		}catch(Exception $e){
			throw $e;
		}
	}

	/**
	 * ȷ��ҵ����
	 */
	function businessConfirm_i($obj){
		//����Դ��״̬Ϊ ִ����
		$innerObjDao = new $this->thisClass();

		try{
			$triProject = array('projectProcess'=>$obj['projectProcess']);

			// ������Ŀ״̬����PK��Ŀ״̬
			if ($obj['status'] == "GCXMZT00") {
				$triProject['status'] = '3';
			} else if (in_array($obj['status'], array('GCXMZT00', 'GCXMZT03', 'GCXMZT04'))) {
				$triProject['status'] = '4';
			}

			$innerObjDao->update(array('id' => $obj['contractId']), $triProject);

			return true;
		}catch(Exception $e){
			throw $e;
		}
	}

	/**
	 * ɾ��ҵ����
	 */
	function businessDelete_i($obj){
		//����Դ��״̬Ϊ ִ����
		$innerObjDao = new $this->thisClass();

		try{
			$innerObjDao->update(array('id' => $obj['contractId']),array('status'=>'2'));

			return true;
		}catch(Exception $e){
			throw $e;
		}
	}

	/**
	 * ɾ��ҵ����
	 */
	function businessClose_i($obj){
		//����Դ��״̬Ϊ ִ����
		$innerObjDao = new $this->thisClass();

		try{
			$innerObjDao->update(array('id' => $obj['contractId']),array('status'=>'4'));

			return true;
		}catch(Exception $e){
			throw $e;
		}
	}

	/**
	 * ������Ŀҵ�����
	 */
	function businessConnect_i($obj,$mainDao){
		return true;
	}

	/**
	 * ��ȡ������Ŀ�³̵���Ϣ
	 */
	function businessForCharter_i($obj,$mainDao){
		return true;
	}
	
	/**
	 * ��ȡҵ�����ԭʼ��Ϣ
	 */
	function getRawInfo_i($id){
		$innerObjDao = new $this->thisClass();
		return $innerObjDao->get_d($id);
	}
}