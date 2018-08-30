<?php


/**
 * @author Show
 * @Date 2012��5��25�� ������ 14:55:28
 * @version 1.0
 * @description:���͹�����Ʋ�
 */
class controller_hr_incentive_incentive extends controller_base_action {

	function __construct() {
		$this->objName = "incentive";
		$this->objPath = "hr_incentive";
		parent :: __construct();
	}

	/**
	 * ��ת�����͹����б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * ��ת�����͹����б�--����
	 */
	function c_pageByPerson() {
		$this->assign('userAccount', $_GET['userAccount']);
		$this->assign('userNo', $_GET['userNo']);
		$this->view('listbyperson');
	}

	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_pageJson() {
		$service = $this->service;
		$rows = array ();

		$service->getParam($_REQUEST);
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ

		$rows = $service->page_d();

		$arr = array ();
		$arr ['listSql'] = $service->listSql;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
		$arr['page'] = $service->page;
		//������Ϣ����
		if (!empty ($rows)) {
			$rows = $this->sconfig->md5Rows($rows);

			//�ϼ�������
			$countArr = $service->listBySqlId('count_all');
			$countArr[0]['userNo'] = '�ϼ�';
			$countArr[0]['id'] = 'noId';
			$rows[] = $countArr[0];
		}

		$arr['collection'] = $rows;
		echo util_jsonUtil :: encode($arr);
	}

	/**
	 * �鿴ҳ�� - ����Ȩ��
	 */
	function c_pageForRead() {
		$this->view('listforread');
	}

	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_pageJsonForRead() {
		$service = $this->service;
		$rows = array ();

		$service->getParam($_REQUEST);
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ

		$otherdatasDao = new model_common_otherdatas();
		$personLimit = $otherdatasDao->getUserPriv('hr_personnel_personnel', $_SESSION['USER_ID'], $_SESSION['DEPT_ID'], $_SESSION['JOB_ID']);
		//		print_r($personLimit);
		//ϵͳȨ��
		$sysLimit = $personLimit['����Ȩ��'];

		//���´� �� ȫ�� ����
		if (strstr($sysLimit, ';;')) {

			$service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
			$rows = $service->page_d();

		} else
			if (!empty ($sysLimit)) { //���û��ѡ��ȫ���������Ȩ�޲�ѯ����ֵ
				$_POST['deptIds'] = $sysLimit;
				$service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
				$rows = $service->page_d();
			}

		//������Ϣ����
		if (!empty ($rows)) {
			$rows = $this->sconfig->md5Rows($rows);

			//�ϼ�������
			$countArr = $service->listBySqlId('count_all');
			$countArr[0]['userNo'] = '�ϼ�';
			$countArr[0]['id'] = 'noId';
			$rows[] = $countArr[0];
		}

		$arr = array ();
		$arr['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
		$arr['page'] = $service->page;
		echo util_jsonUtil :: encode($arr);
	}

	/**
	 * ��ת���������͹���ҳ��
	*/
	function c_toAdd() {
		$this->showDatadicts(array (
			'incentiveType' => 'HRJLSS'
		), null, true);
		$this->assign('thisUserId', $_SESSION['USER_ID']);
		$this->assign('thisUser', $_SESSION['USERNAME']);
		$this->assign('thisDate', day_date);

		$this->view('add',true);
	}

	/**
	 * ��ת���༭���͹���ҳ��
	 */
	function c_toEdit() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->assign("file", $this->service->getFilesByObjId($_GET['id'], true));
		$this->showDatadicts(array (
			'incentiveType' => 'HRJLSS'
		), $obj['incentiveType']);
		$this->view('edit',true);
	}

	/**
	 * ��ת���鿴���͹���ҳ��
	 */
	function c_toView() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->assign("file", $this->service->getFilesByObjId($_GET['id'], false));
		$this->view('view');
	}

	/**
	 * ��ȡȨ��
	 */
	function c_getLimits() {
		$limitName = util_jsonUtil :: iconvUTF2GB($_POST['limitName']);
		echo $this->service->this_limit[$limitName];
	}

	/******************* S ���뵼��ϵ�� ************************/
	/**
	 * ����excel
	 */
	function c_toExcelIn() {
		$this->display('excelin');
	}

	/**
	 * ����excel
	 */
	function c_excelIn() {
		$resultArr = $this->service->addExecelData_d();

		$title = '������Ϣ�������б�';
		$thead = array (
			'������Ϣ',
			'������'
		);
		echo util_excelUtil :: showResult($resultArr, $title, $thead);
	}
	/******************* E ���뵼��ϵ�� ************************/

	/******************  ���� ***********************/
	/**
	* ��������
	*/
	function c_excelOutSelect() {
		$this->assign('listSql', str_replace("&nbsp;", " ", stripslashes(stripslashes($_POST['incentive']['listSql']))));
		$this->view('excelout-select');
	}

	/**
	 * ��������
	 */
	function c_selectExcelOut() {
		//			set_time_limit(600);
		$rows = array (); //���ݼ�
		//		echo "<pre>";
		//		print_R(stripslashes(stripslashes(stripslashes($_POST['listSql']))));
		$listSql = str_replace("&nbsp;", " ", stripslashes(stripslashes(stripslashes($_POST['listSql']))));
		if (!empty ($listSql)) {
			$rows = $this->service->_db->getArray($listSql);
		}
		//		echo "<pre>";
		//		print_r($rows);
		$colNameArr = array (); //��������
		include_once ("model/hr/incentive/incentiveFieldArr.php");
		if (is_array($_POST['incentive'])) {
			foreach ($_POST['incentive'] as $key => $val) {
				foreach ($incentiveFieldArr as $fKey => $fVal) {
					if ($val == $fKey) {
						$colNameArr[$key] = $fVal;
					}
				}
			}
		}
//		print_r($_POST['contract']);
//		print_r($colNameArr);
		$newColArr = array_combine($_POST['incentive'], $colNameArr); //�ϲ�����
		//ƥ�䵼����
		$dataArr = array ();
		$colIdArr = array_flip($_POST['incentive']);
		if (is_array($rows)) {
			foreach ($rows as $key => $row) {
				foreach ($colIdArr as $index => $val) {
					$colIdArr[$index] = $row[$index];
				}
				array_push($dataArr, $colIdArr);
			}
		}
//				echo "<pre>";
//				print_R($dataArr);
		return model_hr_personnel_personnelExcelUtil :: excelOutIncentive($newColArr, $dataArr);
	}

}
?>