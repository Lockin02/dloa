<?php

/**
 * 到款控制层类
 */
class controller_finance_income_incomeAllot extends controller_base_action
{

	function __construct() {
		$this->objName = "incomeAllot";
		$this->objPath = "finance_income";
		parent:: __construct();
	}

	/**
	 * 合同相信信息-获取款记录
	 */
	function c_getIncomeAllot() {
		$this->assign('contNumber', $_GET['contNumber']);
		$this->display('detail-contract');
	}

	/**
	 * 获取分页数据转成Json
	 */
	function c_incomeDetPageJson() {
		$service = $this->service;
		$_POST['objTypes'] = $service->rtPostVla($_POST['objType']);
		unset($_POST['objType']);
		$service->getParam($_POST); //设置前台获取的参数信息
		$rows = $service->pageBySqlId('select_allotinobj');
		$arr = array();
		$arr['collection'] = $rows;
		$arr['totalSize'] = $service->count;
		$arr['page'] = $service->page;
		echo util_jsonUtil:: encode($arr);
	}

	/**
	 * 订单查看到款信息
	 */
	function c_orderIncomeAllot() {
		$obj = $_GET['obj'];
		$this->permCheck($obj['objId'], $this->service->rtTypeClass($obj['objType']));
		$this->assignFunc($obj);
		$this->display('detail-order');
	}

	/**
	 * 获取分页数据转成Json
	 */
	function c_pageJson() {
		$service = $this->service;
		$service->getParam($_REQUEST);
		$rows = $service->page_d();
		// 数据附加
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
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
		$arr ['page'] = $service->page;
		$arr ['advSql'] = $service->advSql;
		$arr ['listSql'] = $service->listSql;
		echo util_jsonUtil::encode($arr);
	}

	/**
	 * 获取所有数据返回json
	 */
	function c_listJson() {
		if (!isset($_REQUEST['incomeId']) || empty($_REQUEST['incomeId'])) exit();
		$this->service->getParam($_REQUEST);
		$this->service->asc = false;
		$rows = $this->service->list_d();
		// 数据附加
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