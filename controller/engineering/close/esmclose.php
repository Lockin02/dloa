<?php

/**
 * @author show
 * @Date 2015��2��6�� 10:37:03
 * @version 1.0
 * @description:��Ŀ�ر�������Ʋ�
 */
class controller_engineering_close_esmclose extends controller_base_action
{

	function __construct() {
		$this->objName = "esmclose";
		$this->objPath = "engineering_close";
		parent::__construct();
	}

	/**
	 * ��ת����Ŀ�ر������б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * ��ȷ���б�
	 */
	function c_toWaitConfirmList() {
		$this->view('waitconfirmlist');
	}

	/**
	 * ��ȷ���б�
	 */
	function c_waitConfirmJson() {
		$service = $this->service;
		$service->getParam($_REQUEST);

		$rows = $service->page_d('select_confirm');
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows($rows);
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
	 * �鿴ҳ��
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
	 * �鿴����
	 */
	function c_toView() {
		$obj = $this->service->get_d($_GET['id']);
		$this->assignFunc($obj);
		$this->view('view');
	}

	/**
	 * ��������
	 */
	function c_toAudit() {
		$obj = $this->service->get_d($_GET['id']);
		$this->assignFunc($obj);
		$this->view('audit');
	}

	/**
	 * �رմ��� -- ����ͳһ����һ��ҳ��
	 */
	function c_toClose() {
		$obj = $this->service->find(array('projectId' => $_GET['projectId']));

		// �Ҳ���ʱ����Ӵ�������༭���߲鿴
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
	 * �����������
	 */
	function c_add() {
		$this->checkSubmit();
		if ($id = $this->service->add_d($_POST[$this->objName])) {
			// ��������Ƿ�ȫͨ����ͨ�������������
			if ($this->service->isAllDeal_d($id)) {
				//������Ŀ
				$esmprojectDao = new model_engineering_project_esmproject();
				$rangeId = $esmprojectDao->getRangeId_d($_POST[$this->objName]['projectId']);
				succ_show('controller/engineering/close/ewf_index.php?actTo=ewfSelect&billId=' . $id .
					"&billArea=" . $rangeId);
			} else {
				msgRf('�������ύȷ�ϣ���ȴ������Աȷ����ɡ�');
			}
		}
	}

	/**
	 * �޸Ķ���
	 */
	function c_edit() {
		$this->checkSubmit();
		$object = $_POST[$this->objName];
		if ($this->service->edit_d($object)) {
			// ��������Ƿ�ȫͨ����ͨ�������������
			if ($this->service->isAllDeal_d($object['id'])) {
				//������Ŀ
				$esmprojectDao = new model_engineering_project_esmproject();
				$rangeId = $esmprojectDao->getRangeId_d($object['projectId']);
				succ_show('controller/engineering/close/ewf_index.php?actTo=ewfSelect&billId=' . $object['id'] .
					"&billArea=" . $rangeId);
			} else {
				msgRf('�������ύȷ�ϣ���ȴ������Աȷ����ɡ�');
			}
		}
	}

	/**
	 * ������ɺ�����µķ���
	 */
	function c_dealAfterAudit() {
		$this->service->dealAfterAudit_d($_GET['spid']);
		succ_show('?model=common_workflow_workflow&action=auditingList');
	}
}