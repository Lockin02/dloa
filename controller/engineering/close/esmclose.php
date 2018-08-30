<?php

/**
 * @author show
 * @Date 2015年2月6日 10:37:03
 * @version 1.0
 * @description:项目关闭申请控制层
 */
class controller_engineering_close_esmclose extends controller_base_action
{

	function __construct() {
		$this->objName = "esmclose";
		$this->objPath = "engineering_close";
		parent::__construct();
	}

	/**
	 * 跳转到项目关闭申请列表
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 待确认列表
	 */
	function c_toWaitConfirmList() {
		$this->view('waitconfirmlist');
	}

	/**
	 * 待确认列表
	 */
	function c_waitConfirmJson() {
		$service = $this->service;
		$service->getParam($_REQUEST);

		$rows = $service->page_d('select_confirm');
		//数据加入安全码
		$rows = $this->sconfig->md5Rows($rows);
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
	 * 查看页面
	 */
	function c_toViewList() {
		$this->assign('projectId', isset($_GET['projectId']) ? $_GET['projectId'] : exit('error'));

		$closeInfo = $this->service->find(array('projectId' => $_GET['projectId']), null,
			'id,applyName,applyDate,ExaStatus,content');
		if ($closeInfo) {
			$closeInfo['closeId'] = $closeInfo['id'];
			$this->assignFunc($closeInfo);
		} else {
			$this->assignFunc(array(
				'closeId' => '', 'applyName' => '',
				'applyDate' => '', 'ExaStatus' => '',
				'content' => ''
			));
		}

		$this->view('viewlist');
	}

	/**
	 * 查看界面
	 */
	function c_toView() {
		$obj = $this->service->get_d($_GET['id']);
		$this->assignFunc($obj);
		$this->view('view');
	}

	/**
	 * 审批界面
	 */
	function c_toAudit() {
		$obj = $this->service->get_d($_GET['id']);
		$this->assignFunc($obj);
		$this->view('audit');
	}

	/**
	 * 关闭处理 -- 这里统一做到一个页面
	 */
	function c_toClose() {
		$obj = $this->service->find(array('projectId' => $_GET['projectId']));

		// 找不到时做添加处理，否则编辑或者查看
		if ($obj) {
			if ($obj['ExaStatus'] == AUDITING) {
				$this->assignFunc($obj);
				$this->view('view');
			} else {
				$this->assignFunc($obj);
				$this->view('edit', true);
			}
		} else {
			$this->assignFunc($this->service->getProjectInfo_d($_GET['projectId']));
			$this->assign('applyDate', day_date);
			$this->assign('applyId', $_SESSION['USER_ID']);
			$this->assign('applyName', $_SESSION['USERNAME']);
			$this->view('add', true);
		}
	}

	/**
	 * 新增对象操作
	 */
	function c_add() {
		$this->checkSubmit();
		if ($id = $this->service->add_d($_POST[$this->objName])) {
			// 检查流程是否全通过，通过则进入审批流
			if ($this->service->isAllDeal_d($id)) {
				//工程项目
				$esmprojectDao = new model_engineering_project_esmproject();
				$rangeId = $esmprojectDao->getRangeId_d($_POST[$this->objName]['projectId']);
				succ_show('controller/engineering/close/ewf_index.php?actTo=ewfSelect&billId=' . $id .
					"&billArea=" . $rangeId);
			} else {
				msgRf('流程已提交确认，请等待相关人员确认完成。');
			}
		}
	}

	/**
	 * 修改对象
	 */
	function c_edit() {
		$this->checkSubmit();
		$object = $_POST[$this->objName];
		if ($this->service->edit_d($object)) {
			// 检查流程是否全通过，通过则进入审批流
			if ($this->service->isAllDeal_d($object['id'])) {
				//工程项目
				$esmprojectDao = new model_engineering_project_esmproject();
				$rangeId = $esmprojectDao->getRangeId_d($object['projectId']);
				succ_show('controller/engineering/close/ewf_index.php?actTo=ewfSelect&billId=' . $object['id'] .
					"&billArea=" . $rangeId);
			} else {
				msgRf('流程已提交确认，请等待相关人员确认完成。');
			}
		}
	}

	/**
	 * 审批完成后处理盖章的方法
	 */
	function c_dealAfterAudit() {
		$this->service->dealAfterAudit_d($_GET['spid']);
		succ_show('?model=common_workflow_workflow&action=auditingList');
	}
}