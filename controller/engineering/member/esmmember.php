<?php

/**
 * @author Show
 * @Date 2011��12��20�� ���ڶ� 15:23:55
 * @version 1.0
 * @description:��Ŀ��Ա(oa_esm_project_member)���Ʋ� ��Ա����
 * 0 �ڲ�
 * 1 �ⲿ
 *
 * ��Ա״̬
 * 0 ��Ŀ��
 * 1 ���뿪
 */
class controller_engineering_member_esmmember extends controller_base_action
{

	function __construct() {
		$this->objName = "esmmember";
		$this->objPath = "engineering_member";
		parent::__construct();
	}

	/******************* �б��� ***************************/

	/**
	 * ��ת����Ŀ��Ա(oa_esm_project_member)�б�
	 */
	function c_page() {
		$projectId = isset($_GET['projectId']) ? $_GET['projectId'] : "";
		$this->assign('projectId', $projectId);
		$this->view('list');
	}

	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_pageJsonOrg() {
		$service = $this->service;
		$service->getParam($_REQUEST);

		//$service->asc = false;
		$rows = $service->page_d('select_listcount');
		if (is_array($rows)) {
			//���ݼ��밲ȫ��
			$rows = $this->sconfig->md5Rows($rows);
		}
		$arr = array();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode($arr);
	}

	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_pageJson() {
		$service = $this->service;
		$service->getParam($_REQUEST);

		//$service->asc = false;
		$rows = $service->page_d('select_listcount');
		if (is_array($rows)) {
			//���ݼ��밲ȫ��
			$rows = $this->sconfig->md5Rows($rows);

			//����Ȩ��
			$rows = $this->gridDateFilter($rows);

			//������Ŀ�ϼ�
			$service->sort = "";
			$service->searchArr = array('projectId' => $_POST['projectId']);
			$objArr = $service->listBySqlId('count_all');
			if (is_array($objArr)) {
				$rsArr = $objArr[0];
				$rsArr['memberName'] = '��Ŀ�ϼ�';
				$rsArr['id'] = 'noId';
			}
			$rows[] = $rsArr;
		}
		$arr = array();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode($arr);
	}

	/**
	 * ��Ŀ����б�
	 */
	function c_pageForMember() {
		$this->assign('projectId', $_GET['projectId']);
		$this->view('list');
	}

	/**
	 * ��Ŀ��Ա��״
	 */
	function c_pageForMemberCurrent() {
		$projectId = $_GET['projectId'] ? $_GET['projectId'] : die();
		//����������
		if ($_POST['beginDate']) {
			$beginDate = $_POST['beginDate'];
			$endDate = $_POST['endDate'];
		} else {
            $weekDao = new model_engineering_baseinfo_week();
            $weekBEArr = $weekDao->getWeekRange();
			$beginDate = $weekBEArr['beginDate'];
			$endDate = $weekBEArr['endDate'];
		}
		$this->assign('beginDate', $beginDate);
		$this->assign('endDate', $endDate);
		$this->assign('projectId', $projectId);

		$this->view('list-current');
	}

	/**
	 * ĳ��ʱ����Ŀ��Ա״��
	 */
	function c_ajaxManageCurrent() {
		$projectId = $_POST['projectId'] ? $_POST['projectId'] : die();
		//����������
		if ($_POST['beginDate']) {
			$beginDate = $_POST['beginDate'];
			$endDate = $_POST['endDate'];
		} else {
            $weekDao = new model_engineering_baseinfo_week();
            $weekBEArr = $weekDao->getWeekRange();
			$beginDate = $weekBEArr['beginDate'];
			$endDate = $weekBEArr['endDate'];
		}
		$condition = $_POST['condition'];
		$data = array(
			array('����' => '����')
		);
		//�б�������Ⱦ
		$pageInfo = $this->service->memberCurrent_d($projectId, $beginDate, $endDate, $condition);
		if (!empty($pageInfo)) {
			$amount = 0;
			foreach ($pageInfo as $key => $val) {
				if (empty($val['personLevel'])) {
					$data[0]['��'] = $val['count(*)'];
					$amount += $val['count(*)'];
				} else {
					$data[0][$val['personLevel']] = $val['count(*)'];
					$amount += $val['count(*)'];
				}
			}
			$data[0]['�ϼ�'] = $amount;
		}
		echo util_jsonUtil::encode($data);
	}

	/**
	 * ĳ��ʱ����Ŀ��Ա�б�
	 */
	function c_memberListJson() {
		$projectId = $_POST['projectId'] ? $_POST['projectId'] : die();
		//����������
		if ($_POST['beginDate']) {
			$beginDate = $_POST['beginDate'];
			$endDate = $_POST['endDate'];
		} else {
            $weekDao = new model_engineering_baseinfo_week();
            $weekBEArr = $weekDao->getWeekRange();
			$beginDate = $weekBEArr['beginDate'];
			$endDate = $weekBEArr['endDate'];
		}
		$condition = $_POST['condition'];
		//�б�������Ⱦ
		$rows = $this->service->memberListJson_d($projectId, $beginDate, $endDate, $condition);
		foreach ($rows as $key => $val) {
			if (empty($val['personLevel'])) {
				$rows[$key]['personLevel'] = '��';
			}
		}
		echo util_jsonUtil::encode($rows);
	}

	/**
	 * ĳ��ʱ����Ŀ��Ա�б���Excel
	 */
	function c_memberListExport() {
		$projectId = $_GET['projectId'] ? $_GET['projectId'] : die();
		//����������
		if ($_GET['beginDate']) {
			$beginDate = $_GET['beginDate'];
			$endDate = $_GET['endDate'];
		} else {
            $weekDao = new model_engineering_baseinfo_week();
            $weekBEArr = $weekDao->getWeekRange();
			$beginDate = $weekBEArr['beginDate'];
			$endDate = $weekBEArr['endDate'];
		}
		$condition = $_GET['condition'];
		//�б�������Ⱦ
		$rows = $this->service->memberListJson_d($projectId, $beginDate, $endDate, $condition);
		foreach ($rows as $key => $val) {
			if (empty($val['personLevel'])) {
				$rows[$key]['personLevel'] = '��';
			} elseif (!empty($val['thisProjectProcess'])) {
				$rows[$key]['thisProjectProcess'] = $val['thisProjectProcess'] . '%';
			}
		}
		return model_engineering_util_esmexcelutil::exportMemberList($rows);
	}

	/**
	 * ��Ŀ����б�
	 */
	function c_pageForMemberView() {
		$this->assign('projectId', $_GET['projectId']);
		$this->view('listview');
	}

	/**
	 * �ر���Ŀ
	 */
	function c_closeList() {
		$this->assign('projectId', $_GET['projectId']);
		$this->assign('read', isset($_GET['read']) ? $_GET['read'] : "0");
		$this->view('listclose');
	}

	/**
	 * �б�����Ȩ�޹���
	 */
	function gridDateFilter($rows) {
		//����Ԥ�㵥��Ȩ�޵� 2013-07-08
		$otherDataDao = new model_common_otherdatas();
		$esmLimitArr = $otherDataDao->getUserPriv('engineering_project_esmproject', $_SESSION['USER_ID'], $_SESSION['DEPT_ID'], $_SESSION['USER_JOBSID']);
		if (!$esmLimitArr['����Ԥ�㵥��']) {
			foreach ($rows as $key => $val) {
				$rows[$key]['feePerson'] = '******';
			}
		}
		return $rows;
	}

	/******************** ���˷�����Ϣ�б� *******************/
	/**
	 * �ҵķ���
	 */
	function c_myCostMoney() {
		$this->view('listcostmoney');
	}

	/**
	 * �ҵķ��� - json
	 */
	function c_pageJsonCostMoney() {
		$service = $this->service;
		$_POST['memberId'] = $_SESSION['USER_ID'];
		$service->getParam($_POST);

		//$service->asc = false;
		$rows = $service->page_d('select_costMoney');
		if (is_array($rows)) {
			//���ݼ��밲ȫ��
			$rows = $this->sconfig->md5Rows($rows);
		}
		$arr = array();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode($arr);
	}

	/************************* ��ɾ�Ĳ� ***********************/
	/**
	 * ��ת��������Ŀ��Ա
	 */
	function c_toAdd() {
		$rs = $this->service->getObjInfo_d($_GET['id']);
		$this->assignFunc($rs);
		$this->assign('projectId', $_GET['id']);
		$this->view('add');
	}

	/**
	 * �����������
	 */
	function c_add() {
		if ($this->service->add_d($_POST [$this->objName], true)) {
			msg('�����ɹ�!');
		} else {
			msg('����ʧ��!');
		}
	}

	/**
	 * ��ת���༭��Ŀ��Աҳ��
	 */
	function c_toEdit() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET ['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('edit');
	}

	/**
	 * ��ת���鿴��Ŀ��Աҳ��
	 */
	function c_toView() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET ['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('view');
	}

	/********************** ҵ���߼����� *********************/
	/**
	 * �ж���Ŀ�г�Ա�Ƿ��Ѿ���ȫ¼���뿪����
	 */
	function c_checkMemberAllLeave() {
		echo $this->service->checkMemberAllLeave_d($_POST['projectId']) ? 1 : 0;
	}

	/**
	 * ��Ŀ��ԱΨһ��֤
	 */
	function c_checkMemberRepeat() {
		$rs = $this->service->find(array('memberId' => $_POST['memberId'], 'projectId' => $_POST['projectId']));
		if (is_array($rs)) {
			echo 1;
		} else {
			echo 0;
		}
	}

	/**
	 * �ı�����״̬
	 */
	function c_changeStatus() {
		echo $this->service->edit_d($_POST) ? 1 : 0;
	}

	/**
	 * �ж���Ŀ���Ƿ������Ա
	 */
	function c_memberIsExsist() {
		echo util_jsonUtil::iconvGB2UTF($this->service->memberIsExsist_d($_POST['projectId'], $_POST['memberId']));
	}

	/**
	 * �жϳ�Ա�Ƿ������
	 */
	function c_memberCanSet() {
		echo util_jsonUtil::encode($this->service->memberCanSet_d($_POST['projectId'],
			$_POST['roleId'], $_POST['memberId'], $_POST['orgMemberId']));
	}

	/**
	 * ������Դ��״ͼ��
	 */
	function c_getChart() {
		$projectId = $_POST['projectId'] ? $_POST['projectId'] : die();
		//����������
		if ($_POST['beginDate']) {
			$beginDate = $_POST['beginDate'];
			$endDate = $_POST['endDate'];
		} else {
            $weekDao = new model_engineering_baseinfo_week();
            $weekBEArr = $weekDao->getWeekRange();
			$beginDate = $weekBEArr['beginDate'];
			$endDate = $weekBEArr['endDate'];
		}
		$condition = $_POST['condition'];
		//�б�������Ⱦ
		$pageInfo = $this->service->memberCurrent_d($projectId, $beginDate, $endDate, $condition);
		//ͼ��
		$chartDao = new model_common_fusionCharts ();

		$result = array();
		foreach ($pageInfo as $key => $val) {
			$tempVal = array();
			$tempVal [0] = "1";
			if (empty($val['personLevel'])) {
				$tempVal [1] = '��';
			} else {
				$tempVal [1] = $val ['personLevel'];
			}
			$tempVal [2] = $val ['count(*)'];
			array_push($result, $tempVal);
		}
		$chartConf = array('exportFileName' => "������Դ��״", 'caption' => "������Դ��״", 'exportAtClient' => 0, 'exportAction' => 'download');
		echo util_jsonUtil::iconvGB2UTF($chartDao->showCharts($result, "Pie3D.swf", $chartConf, 360, 280));
	}

	/**
	 * �ж��޸ĵĽ�ɫ��Ա�Ƿ������Ŀ����
	 */
	function c_checkHasManager() {
		echo $this->service->checkHasManager_d($_POST);
	}
}