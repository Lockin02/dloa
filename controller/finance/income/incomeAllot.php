<?php

/**
 * ������Ʋ���
 */
class controller_finance_income_incomeAllot extends controller_base_action
{

	function __construct() {
		$this->objName = "incomeAllot";
		$this->objPath = "finance_income";
		parent:: __construct();
	}

	/**
	 * ��ͬ������Ϣ-��ȡ���¼
	 */
	function c_getIncomeAllot() {
		$this->assign('contNumber', $_GET['contNumber']);
		$this->display('detail-contract');
	}

	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_incomeDetPageJson() {
		$service = $this->service;
		$_POST['objTypes'] = $service->rtPostVla($_POST['objType']);
		unset($_POST['objType']);
		$service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$rows = $service->pageBySqlId('select_allotinobj');
		$arr = array();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service->count;
		$arr['page'] = $service->page;
		echo util_jsonUtil:: encode($arr);
	}

	/**
	 * �����鿴������Ϣ
	 */
	function c_orderIncomeAllot() {
		$obj = $_GET['obj'];
		$this->permCheck($obj['objId'], $this->service->rtTypeClass($obj['objType']));
		$this->assignFunc($obj);
		$this->display('detail-order');
	}

	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_pageJson() {
		$service = $this->service;
		$service->getParam($_REQUEST);
		$rows = $service->page_d();
		// ���ݸ���
		$contractDao = new model_contract_contract_contract();
		$contracts = $contractDao->findAll(null, null, 'id,areaName');
		if ($contracts) {
			$areaMap = array();
			foreach ($contracts as $v) {
				$areaMap[$v['id']] = $v['areaName'];
			}

			foreach ($rows as $k => $v) {
				if ($v['objType'] == 'KPRK-12') {
					$rows[$k]['areaName'] = $areaMap[$v['objId']];
				}
			}
		}
		$arr = array();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		$arr ['listSql'] = $service->listSql;
		echo util_jsonUtil::encode($arr);
	}

	/**
	 * ��ȡ�������ݷ���json
	 */
	function c_listJson() {
		if (!isset($_REQUEST['incomeId']) || empty($_REQUEST['incomeId'])) exit();
		$this->service->getParam($_REQUEST);
		$this->service->asc = false;
		$rows = $this->service->list_d();
		// ���ݸ���
		$contractDao = new model_contract_contract_contract();
		$contracts = $contractDao->findAll(null, null, 'id,areaName');
		if ($contracts) {
			$areaMap = array();
			foreach ($contracts as $v) {
				$areaMap[$v['id']] = $v['areaName'];
			}

			foreach ($rows as $k => $v) {
				if ($v['objType'] == 'KPRK-12') {
					$rows[$k]['areaName'] = $areaMap[$v['objId']];
				}
			}
		}
		echo util_jsonUtil::encode($rows);
	}
}