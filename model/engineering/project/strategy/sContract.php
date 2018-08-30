<?php
/**
 * Created on 2012-5-18
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
include_once(WEB_TOR . 'model/engineering/project/iesmproject.php');

/**
 * ������Ŀ����
 */
class model_engineering_project_strategy_sContract extends model_base implements iesmproject
{

	// ��Ӧҵ����
	private $thisClass = 'model_contract_contract_contract';

	// ��Ӧ��ͬ��Ŀ��ҵ����
	private $contractProjectClass = 'model_contract_conproject_conproject';

	/**
	 * ��ȡҵ�������Ϣ
	 * @param $obj
	 * @return mixed
	 */
	function getObjInfo_i($obj) {
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
	 * @param $obj
	 * @param null $mainDao
	 * @return bool
	 * @throws Exception
	 */
	function businessAdd_i($obj, $mainDao = null) {
		// ��ͬ��Ŀʵ����
		$contractProjectDao = new $this->contractProjectClass();

		// ʵ������ͬ��
		$innerObjDao = new $this->thisClass();

		try {
			// ��߻�ȡ��ͬ���
			$contractMoneyInfo = $contractProjectDao->getMoneyByProWorkRate($obj['contractId'], $obj['newProLine'], $obj['workRate']);
			$mainDao->update(
				array('id' => $obj['id']),
				array('contractMoney' => $contractMoneyInfo['contractMoney'],
					'estimates' => $contractMoneyInfo['estimates'])
			);

			// ���º�ͬ��Ŀ
			$contractProject = array(
				"projectCode" => $obj['projectCode'], "projectName" => $obj['projectName'],
				"state" => "GCXMZT01", "contractId" => $obj['contractId'], "esmProjectId" => $obj['id'],
				"proLineCode" => $obj['newProLine'], "proLineName" => $obj['newProLineName'],
				"proportion" => $obj['workRate'], "estimates" => $contractMoneyInfo['estimates'],
				"planBeginDate" => $obj['planBeginDate'], "planEndDate" => $obj['planEndDate']
			);

			$contractProjectDao->createProjectBySer_d($contractProject);

			//���º�ͬ������״̬
			$innerObjDao->updateProjectStatus_d($obj['contractId']);

			// ��ѯ������pk��Ŀ��Ȼ����и���
			$sql = "SELECT c.pkProjectId, p.contractId, p.contractType
			FROM
				oa_esm_project_mapping c
				INNER JOIN oa_esm_project p ON c.pkProjectId = p.id
			WHERE c.projectId = " . $obj['id'];
			$pkList = $mainDao->_db->getArray($sql);

			if ($pkList) {
				// ������Ŀ��
				$trialprojectDao = new model_projectmanagent_trialproject_trialproject();

				foreach ($pkList as $v) {
					$tp = $trialprojectDao->get_d($v['contractId']);

					if ($tp['turnProject']) {
						$turnProjectArr = explode(',', $tp['turnProject']);
						if (!in_array($obj['turnProject'], $turnProjectArr)) {
							$updateRow = array(
								'turnProject' => $obj['turnProject'] . ',' . $obj['projectCode']
							);
							$trialprojectDao->update(array(
								'id' => $v['contractId']
							), $updateRow);
						}
					} else {
						$updateRow = array(
							'turnProject' => $obj['projectCode'],
							'turnDate' => day_date,
							'turnStatus' => '��ת��'
						);
						$trialprojectDao->update(array(
							'id' => $v['contractId']
						), $updateRow);
					}
				}
			}

			return true;
		} catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * ȷ��ҵ����
	 * @param $obj
	 * @param $mainDao
	 * @return bool
	 * @throws Exception
	 */
	function businessConfirm_i($obj, $mainDao) {
		// ����������ݲ���������ģ�����Ϊ��Ϣ����������
		if (!isset($obj['estimates'])) {
			$obj = $mainDao->get_d($obj['id']);
			$obj = $mainDao->feeDeal($obj);
		}
		// ��ͬ��Ŀʵ����
		$contractProjectDao = new $this->contractProjectClass();
		// ��ͬ�ڵ�ʵ����
		$conNodeDao = new model_contract_connode_connode();
		try {
			$contractProjectInfo = array(
				"esmProjectId" => $obj['id'],
				"contractId" => $obj['contractId'],
				"proportion" => $obj['workRate'],
				"schedule" => $obj['projectProcess'],
				"estimates" => $obj['estimates'],
				"budget" => $obj['budgetAll'],
				"cost" => $obj['feeAll'],
				"actBeginDate" => $obj['actBeginDate'],
				"actEndDate" => $obj['actEndDate']
			);
			$contractProjectDao->updateConProScheduleByEsmId($contractProjectInfo);

            // ���ú�ͬ�Զ��رշ���
            $innerObjDao = new $this->thisClass();
            $innerObjDao->updateOutStatus_d($obj['contractId']);

			// ��¼��Ŀ���Ƚڵ�
			$conNodeDao->autoNode_d($obj['contractId'], 'esm', $mainDao->getContractProjectProcess_d($obj['contractId']));

			return true;
		} catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * ɾ��ҵ����
	 * @param $obj
	 * @param null $mainDao
	 * @return bool
	 */
	function businessDelete_i($obj, $mainDao = null) {
		return true;
	}

	/**
	 * �ر�
	 * @param $obj
	 * @param $mainDao
	 * @return bool
	 * @throws Exception
	 */
	function businessClose_i($obj, $mainDao) {
		// ����������ݲ���������ģ�����Ŀ��Ϣ����������
		if (!isset($obj['estimates'])) {
			$obj = $mainDao->get_d($obj['id']);
			$obj = $mainDao->feeDeal($obj);
		}
		// ��ͬ��Ŀʵ����
		$contractProjectDao = new $this->contractProjectClass();
		// ��ͬ�ڵ�ʵ����
		$conNodeDao = new model_contract_connode_connode();
		try {
			$contractProjectInfo = array(
				"esmProjectId" => $obj['id'],
				"contractId" => $obj['contractId'],
				"proportion" => $obj['workRate'],
				"schedule" => $obj['projectProcess'],
				"estimates" => $obj['estimates'],
				"budget" => $obj['budgetAll'],
				"cost" => $obj['feeAll'],
				"actBeginDate" => $obj['actBeginDate'],
				"actEndDate" => $obj['actEndDate']
			);
			$contractProjectDao->updateConProScheduleByEsmId($contractProjectInfo);

            // ���ú�ͬ�Զ��رշ���
            $innerObjDao = new $this->thisClass();
            $innerObjDao->updateOutStatus_d($obj['contractId']);

			// ���ú�ͬT�ո��·���
			$innerObjDao->resetTdayByProject($obj['contractId']);

			// ��¼��Ŀ���Ƚڵ�
			$conNodeDao->autoNode_d($obj['contractId'], 'esm', $mainDao->getContractProjectProcess_d($obj['contractId']));

			return true;
		} catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * ��ȡ������Ŀ�³̵���Ϣ
	 * @param $obj
	 * @param $mainDao
	 * @return mixed
	 */
	function businessForCharter_i($obj, $mainDao) {
		$contractDao = new $this->thisClass();
		$contractArr = $contractDao->getContractInfo($obj['contractId'], array(
			'product'
		));
		$contractArr['contractType'] = 'GCXMYD-01';
		// ִ�в��Ź���, ���û�в�Ʒ��Ϣ��ֱ�ӹ���
		if ($contractArr['product']) {
			$exeDeptArr = array();
			$proLineArr = array(); //2015-12-19 ������Ʒ�� by Liub
			foreach ($contractArr['product'] as $v) {
				if ($v['proTypeId'] != isSell && !in_array($v['exeDeptId'], $exeDeptArr)) {
					$exeDeptArr[] = $v['exeDeptId'];
				}
				if($v['proTypeId'] != isSell && !in_array($v['newProLineCode'], $proLineArr) && $v['newProLineCode'] != ''){
                    $proLineArr[] = $v['newProLineCode'];
				}
			}
			$contractArr['exeDeptStr'] = implode(',', $exeDeptArr);
			$contractArr['newProLineStr'] = implode(',', $proLineArr);
			unset($contractArr['product']);
		} else {
			$contractArr['exeDeptStr'] = '';
		}

		return $contractArr;
	}

	/**
	 * ��ȡ��ͬ�Ľ����Ϣ
	 * @param $contractId
	 * @return array('contractMoney' => ..., 'estimates' => ...)
	 */
	function getContractMoney_i($contractId) {
		$contractDao = new $this->contractProjectClass();
		return $contractDao->getProMoneyByCid($contractId);
	}
}