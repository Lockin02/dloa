<?php
/**
 * Created on 2012-5-18
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
include_once(WEB_TOR . 'model/engineering/project/iesmproject.php');

/**
 * 试用项目策略
 */
class model_engineering_project_strategy_sContract extends model_base implements iesmproject
{

	// 对应业务类
	private $thisClass = 'model_contract_contract_contract';

	// 对应合同项目的业务类
	private $contractProjectClass = 'model_contract_conproject_conproject';

	/**
	 * 获取业务对象信息
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
	 * 新增业务处理
	 * @param $obj
	 * @param null $mainDao
	 * @return bool
	 * @throws Exception
	 */
	function businessAdd_i($obj, $mainDao = null) {
		// 合同项目实例化
		$contractProjectDao = new $this->contractProjectClass();

		// 实例化合同类
		$innerObjDao = new $this->thisClass();

		try {
			// 这边获取合同金额
			$contractMoneyInfo = $contractProjectDao->getMoneyByProWorkRate($obj['contractId'], $obj['newProLine'], $obj['workRate']);
			$mainDao->update(
				array('id' => $obj['id']),
				array('contractMoney' => $contractMoneyInfo['contractMoney'],
					'estimates' => $contractMoneyInfo['estimates'])
			);

			// 更新合同项目
			$contractProject = array(
				"projectCode" => $obj['projectCode'], "projectName" => $obj['projectName'],
				"state" => "GCXMZT01", "contractId" => $obj['contractId'], "esmProjectId" => $obj['id'],
				"proLineCode" => $obj['newProLine'], "proLineName" => $obj['newProLineName'],
				"proportion" => $obj['workRate'], "estimates" => $contractMoneyInfo['estimates'],
				"planBeginDate" => $obj['planBeginDate'], "planEndDate" => $obj['planEndDate']
			);

			$contractProjectDao->createProjectBySer_d($contractProject);

			//更新合同的立项状态
			$innerObjDao->updateProjectStatus_d($obj['contractId']);

			// 查询关联的pk项目，然后进行更新
			$sql = "SELECT c.pkProjectId, p.contractId, p.contractType
			FROM
				oa_esm_project_mapping c
				INNER JOIN oa_esm_project p ON c.pkProjectId = p.id
			WHERE c.projectId = " . $obj['id'];
			$pkList = $mainDao->_db->getArray($sql);

			if ($pkList) {
				// 试用项目类
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
							'turnStatus' => '已转正'
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
	 * 确认业务处理
	 * @param $obj
	 * @param $mainDao
	 * @return bool
	 * @throws Exception
	 */
	function businessConfirm_i($obj, $mainDao) {
		// 如果传入内容不包含概算的，则认为信息不满足条件
		if (!isset($obj['estimates'])) {
			$obj = $mainDao->get_d($obj['id']);
			$obj = $mainDao->feeDeal($obj);
		}
		// 合同项目实例化
		$contractProjectDao = new $this->contractProjectClass();
		// 合同节点实例化
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

            // 调用合同自动关闭方法
            $innerObjDao = new $this->thisClass();
            $innerObjDao->updateOutStatus_d($obj['contractId']);

			// 记录项目进度节点
			$conNodeDao->autoNode_d($obj['contractId'], 'esm', $mainDao->getContractProjectProcess_d($obj['contractId']));

			return true;
		} catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * 删除业务处理
	 * @param $obj
	 * @param null $mainDao
	 * @return bool
	 */
	function businessDelete_i($obj, $mainDao = null) {
		return true;
	}

	/**
	 * 关闭
	 * @param $obj
	 * @param $mainDao
	 * @return bool
	 * @throws Exception
	 */
	function businessClose_i($obj, $mainDao) {
		// 如果传入内容不包含概算的，则项目信息不满足条件
		if (!isset($obj['estimates'])) {
			$obj = $mainDao->get_d($obj['id']);
			$obj = $mainDao->feeDeal($obj);
		}
		// 合同项目实例化
		$contractProjectDao = new $this->contractProjectClass();
		// 合同节点实例化
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

            // 调用合同自动关闭方法
            $innerObjDao = new $this->thisClass();
            $innerObjDao->updateOutStatus_d($obj['contractId']);

			// 调用合同T日更新方法
			$innerObjDao->resetTdayByProject($obj['contractId']);

			// 记录项目进度节点
			$conNodeDao->autoNode_d($obj['contractId'], 'esm', $mainDao->getContractProjectProcess_d($obj['contractId']));

			return true;
		} catch (Exception $e) {
			throw $e;
		}
	}

	/**
	 * 获取用于项目章程的信息
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
		// 执行部门过滤, 如果没有产品信息，直接过滤
		if ($contractArr['product']) {
			$exeDeptArr = array();
			$proLineArr = array(); //2015-12-19 新增产品线 by Liub
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
	 * 获取合同的金额信息
	 * @param $contractId
	 * @return array('contractMoney' => ..., 'estimates' => ...)
	 */
	function getContractMoney_i($contractId) {
		$contractDao = new $this->contractProjectClass();
		return $contractDao->getProMoneyByCid($contractId);
	}
}