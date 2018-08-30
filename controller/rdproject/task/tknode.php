<?php

/*
 * @author huangzhifan
 * @Date 2010-9-23
 * @copyright (c) YXKJ Company.
 * @description:��Ŀ����ڵ�Action
 *
 */
class controller_rdproject_task_tknode extends controller_base_action {

	function __construct() {
		$this->objName = "tknode";
		$this->objPath = "rdproject_task";
		parent :: __construct();
	}

	/*
	 * ��ת�������ڵ�ҳ��,���üƻ�����ĿĬ����Ϣ
	 */
	function c_toAdd() {
		$this->show->assign("planId", $_GET['planId']);
		$this->show->assign("planName", $_GET['planName']);
		$this->show->assign("projectId", $_GET['projectId']);
		$this->show->assign("projectName", $_GET['projectName']);
		parent :: c_toAdd();
	}
	/*
	 * ��ת���鿴����ڵ���ϸ��Ϣҳ��
	 */
	function c_toTkNodeView() {
		$tknode = $this->service->get_d($_GET['id']);
		foreach ($tknode as $key => $val) {
			$this->show->assign($key, $val);
		}
		$this->show->display($this->objPath . '_' . $this->objName . '-view');

	}
	/*
	 * ͨ��parentId��ȡ�ڵ�Json��Ϣ
	 */
	function c_getTkNodeByParentId() {
		$service = $this->service;
		$searchArr = $service->getParam ( $_REQUEST ); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$tnrows = $service->getAllTkNode_d($_GET['planId']);
		$alltnrows = $service->getAllTkNodeList_d($_GET['planId']);

		$rdTaskDao = new model_rdproject_task_rdtask();
//		$searchTkArr = array (
//			"belongNodeId" => -1,
//			"planId" => $_GET['planId']
//		);
		$searchArr["belongNodeId"] = -1;
		$service->searchArr=$searchArr;
		$service->sort = "updateTime";
		$service->asc = true;
		$taskrows = $service->pageBySqlId("select_planTask");
		$allTaskRows = $service->listBySqlId("select_planTask");

		function createOIdFn($row) {
			if ($row['status']) {
				$row['oid'] = "t_" . $row['id']; //��t-��ͷΪ����
				$row['oParentId'] = "n_" . $row['belongNodeId'];
				$row['warnIcon'] = '<img src="images/ico4.gif">';
			} else {
				$row['oid'] = "n_" . $row['id']; //��n-Ϊǰ׺����Ϊ�ڵ�
				$row['oParentId'] = "n_" . $row['parentId'];
				$row['warnIcon'] = '<img src="images/ico4-1.gif">';
			}
			return $row;
		}

		//$this->showDatadicts ( array ('taskType' => 'XMRWLX' ) );
		if ($tnrows)
			$tnrows = array_map("createOIdFn", $tnrows);
		if ($taskrows)
			$taskrows = array_map("createOIdFn", $taskrows);
		$returnAllRows = model_common_util :: yx_array_merge($alltnrows, $allTaskRows);
		$returnTNRows = model_common_util :: yx_array_merge($tnrows, $taskrows);
		$arr = array ();
		$arr['data'] = $returnTNRows;
		$arr['total'] = count($returnAllRows);
		$arr['page'] = $service->page;

		echo util_jsonUtil :: encode($arr);
	}
	/*
	 * ͨ���ڵ�id��ȡҪչ���Ľڵ㡢������Ϣ
	 */
	function c_spreadTkNodeByNode() {
		//echo $_GET ['parentId'];

		function createOIdFn($row) {
			if (isset ($row['status'])) {
				$row['oid'] = "t_" . $row['id']; //��t-��ͷΪ����
				$row['oParentId'] = "n_" . $row['belongNodeId'];
				$row['warnIcon'] = '<img src="images/ico4.gif">';
			} else {
				$row['oid'] = "n_" . $row['id']; //��n-Ϊǰ׺����Ϊ�ڵ�
				$row['oParentId'] = "n_" . $row['parentId'];
				$row['warnIcon'] = '<img src="images/ico4-1.gif">';
			}
			return $row;
		}

		$service = $this->service;
		$service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$searchTnArr["parentId"] = $_GET['parentId'];
		$searchTnArr["planId"] = $_GET['planId'];
		$service->asc = false;
		$service->searchArr = $searchTnArr;
		$nodeRows = $service->listBySqlId("select_gridinfo");

		$rdTaskDao = new model_rdproject_task_rdtask();
		$searchTkArr = array (
			"belongNodeId" => $_GET['parentId'],
			"planId" => $_GET['planId']
		);
		$rdTaskDao->asc = false;
		$rdTaskDao->searchArr = $searchTkArr;
		$taskRows = $rdTaskDao->listBySqlId("select_gridinfo");

		if ($nodeRows)
			$nodeRows = array_map("createOIdFn", $nodeRows);
		if ($taskRows)
			$taskRows = array_map("createOIdFn", $taskRows);

		$returnTNRows = model_common_util :: yx_array_merge($nodeRows, $taskRows);

		if (is_array($returnTNRows)) {
			echo util_jsonUtil :: encode($returnTNRows);
		} else {
			echo 0;
		}

	}

	/*
	 *���ݸ��ڵ�id��ȡ�ڵ�����Ϣ
	 */
	function c_getTkNodeTreeByParentId() {
		$searchArr = array (
			"parentId" => $_GET['parentId'],
			"planId" => $_REQUEST['planId']
		);
		$service = $this->service;
		$service->searchArr = $searchArr;
		$rows = $service->listBySqlId("select_treeinfo");
//		echo "<pre>";
//		print_R( $rows );
		function toBoolean($row) {
			$row['leaf'] = $row['leaf'] == 1 ? true : false;
			return $row;
		}
		echo util_jsonUtil :: encode(array_map("toBoolean", $rows));
	}

	/**
	* @desription ajax�жϽڵ������Ƿ��ظ�
	* @date 2010-9-13 ����02:22:04
	*/
	function c_ajaxNodeName() {
		$nodeName = isset ($_GET['nodeName']) ? $_GET['nodeName'] : false;
		$searchArr = array (
			"ajaxNodeName" => $nodeName
		);
		$isRepeat = $this->service->isRepeat($searchArr, "");
		if ($isRepeat) {
			echo "0";
		} else {
			echo "1";
		}
	}
	/*
	 * ajaxɾ������ڵ�
	 */
	function c_ajaxDeleteByPk() {
		//	 	echo parent::deleteByPk($_GET['id']);
		$result = $this->service->deletes($_GET['id']);
		echo util_jsonUtil :: iconvGB2UTF($result);
	}
	/*
	 * ��ȡ�ڵ���������Ϣ
	 */
	function c_getChargeInfo() {
		$result=$this->service->get_d($_GET['id']);
		echo util_jsonUtil::encode($result);

	}
}
?>
