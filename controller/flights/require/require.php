<?php

/**
 * @author Administrator
 * @Date 2013��7��11�� 20:30:47
 * @version 1.0
 * @description:��Ʊ������Ʋ�
 */
class controller_flights_require_require extends controller_base_action
{
	function __construct() {
		$this->objName = "require";
		$this->objPath = "flights_require";
		parent:: __construct();
	}

	/**
	 * ��ת����Ʊ�����б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * ��ת���ҵĶ�Ʊ�����б�
	 */
	function c_myList() {
		$this->view('mylist');
	}

	/**
	 * ��дpageJson
	 */
	function c_myPageJson() {
		$service = $this->service;
		$_REQUEST['requireId'] = $_SESSION["USER_ID"];
		$service->getParam($_REQUEST);
		$rows = $service->page_d();
		$arr = array();
		$arr['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
		$arr['page'] = $service->page;
		$arr['advSql'] = $service->advSql;
		$arr['listSql'] = $service->listSql;
		echo util_jsonUtil:: encode($arr);
	}

	/**
	 * ��ת��������Ʊ����ҳ��
	 */
	function c_toAdd() {

		$this->assign('requireTime', day_date);
		$this->assign('requireName', $_SESSION['USERNAME']);
		$this->assign('requireId', $_SESSION['USER_ID']);

		$this->assign('companyId', $_SESSION['COM_BRN_PT']);
		$this->assign('companyName', $_SESSION['COM_BRN_CN']);
		$this->assign('deptName', $_SESSION['DEPT_NAME']);
		$this->assign('deptId', $_SESSION['DEPT_ID']);

		//��ȡͨѶ¼�еĵ绰����
		$otherDataDao = new model_common_otherdatas();
		$userConnectInfo = $otherDataDao->getPersonnelInfo_d($_SESSION['USER_ID']);
		$this->assign('cardNo', $userConnectInfo['identityCard']);
		$this->assign('requirePhone', $userConnectInfo['mobile']);

		$this->showDatadicts(array('tourAgency' => 'CLKJG'), null, true);//���ÿ�����
		$this->showDatadicts(array('cardType' => 'JPZJLX'));//֤������
		$this->showDatadicts(array('districtType' => 'QULX'));//��������

		//�жϵ�ǰ�����Ƿ���Ҫ��������
		$this->assignFunc($this->service->deptNeedInfo_d($_SESSION['DEPT_ID']));

		$this->view('add');
	}

	/**
	 * ��������
	 */
	function c_add() {
		$object = $_POST[$this->objName];
		if (!isset($object['detailType']) || empty($object['detailType'])) {
			msgRf("����ʧ�ܣ�����û��ѡ���������");
		} else {
			//���ⲿԱ��������ֵ������Ա���
			foreach ($object[items] as $key => $val) {
				if ($val['employeeType'] == 'YGLX-02') {
					$object[items][$key]['airId'] = $object[items][$key]['airName'];
				}
			}

			//�ж��Ƿ�Ҫ����������
			$isSetExaStatus = true;
			$isUserNeedAudit = $this->service->isUserNeedAudit_d($_SESSION['USER_ID'], $_SESSION['DEPT_ID']);
			if (!$isUserNeedAudit) {
				$object['ExaStatus'] = AUDITED;
				$object['ExaDT'] = day_date;
				$isSetExaStatus = false;
			}

			$id = $this->service->add_d($object, $isSetExaStatus);
			if ($id) {
				if ($object['auditType'] == 'audit') {
					if (!$isUserNeedAudit) {
						msgRf("�ύ�ɹ�");
					} else {
						$requireTime = strtotime($object['requireTime']);
						$startDate = strtotime($object['startDate']);
						$days = round(($startDate - $requireTime) / 3600 / 24);
						if ($days > 3) {
							succ_show('controller/flights/require/ewf_index.php?actTo=ewfSelect&billId=' . $id . '&flowMoney=6&billDept=' . $object['costBelongDeptId']);
						} else {
							succ_show('controller/flights/require/ewf_index.php?actTo=ewfSelect&billId=' . $id . '&flowMoney=3&billDept=' . $object['costBelongDeptId']);
						}
					}
				} else {
					msgRf("����ɹ�");
				}
			} else {
				msgRf("����ʧ��");
			}
		}
	}

	/**
	 * ��ת���༭��Ʊ����ҳ��
	 */
	function c_toEdit() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}

		//�жϵ�ǰ�����Ƿ���Ҫʡ��
		$this->assignFunc($this->service->deptNeedInfo_d($obj['costBelongDeptId']));

		$this->showDatadicts(array('tourAgency' => 'CLKJG'), $obj['tourAgency'], true);//���ÿ�����
		$this->showDatadicts(array('cardType' => 'JPZJLX'), $obj['cardType']);//֤������
		$this->showDatadicts(array('districtType' => 'QULX'), $obj['districtType']);//��������
		$this->view('edit');
	}

	//��д�༭
	function c_edit() {
		$object = $_POST[$this->objName];

		//�ж��Ƿ�Ҫ����������
		$isUserNeedAudit = $this->service->isUserNeedAudit_d($_SESSION['USER_ID'], $_SESSION['DEPT_ID']);
		if (!$isUserNeedAudit) {
			$object['ExaStatus'] = AUDITED;
			$object['ExaDT'] = day_date;
		}

		if ($this->service->edit_d($object)) {
			if ($object['auditType'] == 'audit') {
				if (!$isUserNeedAudit) {
					msgRf("�ύ�ɹ�");
				} else {
					$requireTime = strtotime($object['requireTime']);
					$startDate = strtotime($object['startDate']);
					$days = round(($startDate - $requireTime) / 3600 / 24);
					if ($days > 3) {
						succ_show('controller/flights/require/ewf_index.php?actTo=ewfSelect&billId=' . $object['id'] . '&flowMoney=6&billDept=' . $object['costBelongDeptId']);
					} else {
						succ_show('controller/flights/require/ewf_index.php?actTo=ewfSelect&billId=' . $object['id'] . '&flowMoney=3&billDept=' . $object['costBelongDeptId']);
					}
				}
			} else {
				msg("����ɹ�");
			}
		} else {
			msg('����ʧ��');
		}
	}

	/**
	 * ��ת���鿴��Ʊ����ҳ��
	 */
	function c_toView() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}

		$this->assign('ticketType', $this->service->rtStatus_d($obj['ticketType']));
		$this->assign('ticketTypeHidden', $obj['ticketType']);

		//������ʾ����
		$actType = isset($_GET['actType']) ? $_GET['actType'] : '';
		$this->assign('actType', $actType);

		$cardNoLimit = isset($_GET['cardNoLimit']) ? $_GET['cardNoLimit'] : '';
		$this->assign('cardNoLimit', $cardNoLimit);
		//Ȩ�޹���
		if ($obj['cardType'] == 'JPZJLX-01' && ($cardNoLimit == '1' || $_SESSION['USER_ID'] == $obj['airId'])) {
			$this->assign('cardNo', $obj['cardNoHidden']);
		}
		$this->view('view');
	}

	/**
	 * ��ת��Tab
	 */
	function c_viewTab() {
		$this->assign('id', $_GET['id']);
		$cardNoLimit = isset($_GET['cardNoLimit']) ? $_GET['cardNoLimit'] : '';
		$this->assign('cardNoLimit', $cardNoLimit);
		$this->view('viewTab');
	}

	/************************ �����б���Ϣ ***************************/

	//��Ʊ��Ϣ��ѯ��Ʊ������Ϣ
	function c_requireQuerylistJson() {
		$id = $_POST['id'];
		$rows = array();

		//����ȡ��
		$obj = $this->service->get_d($id);
		$obj['auditDate'] = day_date;
		//ʱ�䡢�ص㴦��
		$obj['flightTime'] = $obj['startDate'] . ' 10:00:00';
		$obj['departPlace'] = $obj['startPlace'];
		$obj['arrivalPlace'] = $obj['endPlace'];

		array_push($rows, $obj);

		//��ѯ���ӱ���Ϣ
		$requiresuitArr = $this->service->getRequiresuite_d($id);
		if ($requiresuitArr) {
			foreach ($requiresuitArr as $key => $val) {
				$requiresuitArr[$key]['auditDate'] = day_date;
				$requiresuitArr[$key]['flightTime'] = $obj['flightTime'];
				$requiresuitArr[$key]['departPlace'] = $obj['departPlace'];
				$requiresuitArr[$key]['arrivalPlace'] = $obj['arrivalPlace'];
				array_push($rows, $requiresuitArr[$key]);
			}
		}
		//ȥ����һ������
		$rows = array_splice($rows, 1);
		echo util_jsonUtil:: encode($rows);
	}

	//ajax��ȡ������Ϣ
	function c_ajaxGet() {
		$obj = $this->service->find(array(
			'id' => $_POST['id']
		));
		echo util_jsonUtil::encode($obj);
	}

	/**
	 * ������ɺ�������ķ���
	 */
	function c_dealAfterAudit() {
		$otherdatas = new model_common_otherdatas ();
		$folowInfo = $otherdatas->getWorkflowInfo($_GET ['spid']);
		$arr = $this->service->get_d($folowInfo['objId']);
		$requireNo = $arr['requireNo'];
		$requireName = $arr['requireName'];
		$airName = $arr['airName'];
		$ticketType = $this->service->rtStatus_d($arr['ticketType']);
		$startPlace = $arr['startPlace'];
		$startDate = $arr['startDate'];
		$endPlace = $arr['endPlace'];
		$userId = $folowInfo['Enter_user'];
		$this->service->mailDeal_d('flightsNotice', $userId, array(
				'requireNo' => $requireNo, 'requireName' => $requireName,
				'ticketType' => $ticketType, 'startPlace' => $startPlace,
				'endPlace' => $endPlace, 'airName' => $airName, 'startDate' => $startDate
			)
		);
		succ_show('?model=common_workflow_workflow&action=auditingList');
	}

	/**
	 * �жϲ����Ƿ���Ҫʡ����Ϣ
	 */
	function c_deptNeedInfo() {
		echo util_jsonUtil::encode($this->service->deptNeedInfo_d($_POST['deptId']));
	}

	/**
	 * �ж��Ƿ���Ҫ������
	 */
	function c_isUserNeedAudit() {
		echo $this->service->isUserNeedAudit_d($_SESSION['USER_ID'], $_SESSION['DEPT_ID']) ? 1 : 0;
	}

	/**
	 * �첽�ύ����
	 */
	function c_ajaxSubmit() {
		echo $this->service->ajaxSubmit_d($_POST['id']) ? 1 : 0;
	}
}