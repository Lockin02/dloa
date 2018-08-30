<?php

/*
 * @author huangzhifan
 * @Date 2010-9-23
 * @copyright (c) YXKJ Company.
 * @description:项目任务节点Action
 *
 */
class controller_rdproject_task_tknode extends controller_base_action {

	function __construct() {
		$this->objName = "tknode";
		$this->objPath = "rdproject_task";
		parent :: __construct();
	}

	/*
	 * 跳转到新增节点页面,设置计划、项目默认信息
	 */
	function c_toAdd() {
		$this->show->assign("planId", $_GET['planId']);
		$this->show->assign("planName", $_GET['planName']);
		$this->show->assign("projectId", $_GET['projectId']);
		$this->show->assign("projectName", $_GET['projectName']);
		parent :: c_toAdd();
	}
	/*
	 * 跳转到查看任务节点详细信息页面
	 */
	function c_toTkNodeView() {
		$tknode = $this->service->get_d($_GET['id']);
		foreach ($tknode as $key => $val) {
			$this->show->assign($key, $val);
		}
		$this->show->display($this->objPath . '_' . $this->objName . '-view');

	}
	/*
	 * 通过parentId获取节点Json信息
	 */
	function c_getTkNodeByParentId() {
		$service = $this->service;
		$searchArr = $service->getParam ( $_REQUEST ); //设置前台获取的参数信息
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
				$row['oid'] = "t_" . $row['id']; //以t-开头为任务
				$row['oParentId'] = "n_" . $row['belongNodeId'];
				$row['warnIcon'] = '<img src="images/ico4.gif">';
			} else {
				$row['oid'] = "n_" . $row['id']; //以n-为前缀表明为节点
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
	 * 通过节点id获取要展开的节点、任务信息
	 */
	function c_spreadTkNodeByNode() {
		//echo $_GET ['parentId'];

		function createOIdFn($row) {
			if (isset ($row['status'])) {
				$row['oid'] = "t_" . $row['id']; //以t-开头为任务
				$row['oParentId'] = "n_" . $row['belongNodeId'];
				$row['warnIcon'] = '<img src="images/ico4.gif">';
			} else {
				$row['oid'] = "n_" . $row['id']; //以n-为前缀表明为节点
				$row['oParentId'] = "n_" . $row['parentId'];
				$row['warnIcon'] = '<img src="images/ico4-1.gif">';
			}
			return $row;
		}

		$service = $this->service;
		$service->getParam($_POST); //设置前台获取的参数信息
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
	 *根据父节点id获取节点树信息
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
	* @desription ajax判断节点名称是否重复
	* @date 2010-9-13 下午02:22:04
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
	 * ajax删除任务节点
	 */
	function c_ajaxDeleteByPk() {
		//	 	echo parent::deleteByPk($_GET['id']);
		$result = $this->service->deletes($_GET['id']);
		echo util_jsonUtil :: iconvGB2UTF($result);
	}
	/*
	 * 获取节点责任人信息
	 */
	function c_getChargeInfo() {
		$result=$this->service->get_d($_GET['id']);
		echo util_jsonUtil::encode($result);

	}
}
?>
