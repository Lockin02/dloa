<?php

/**
 * ������Ʋ���
 */
class controller_finance_income_income extends controller_base_action
{

	function __construct() {
		$this->objName = "income";
		$this->objPath = "finance_income";
		parent::__construct();
	}

	/**
	 * ��дpage
	 */
	function c_page() {
		//���Ե�������ҳ��
		$thisObjCode = $this->service->getBusinessCode($_GET['formType']);
		$this->display($thisObjCode . '-list');
	}

	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_pageJsonList() {
		$service = $this->service;
		$service->getParam($_REQUEST);
		$rows = $service->page_d();

		if (!empty($rows)) {
			//���ݼ��밲ȫ��
			$rows = $this->sconfig->md5Rows($rows);

			//��ҳС�Ƽ���
			$rows = $service->pageCount_d($rows);

			//�ܼ�������
			$objArr = $service->listBySqlId('count_all');
			if (is_array($objArr)) {
				$rsArr = $objArr[0];
				$rsArr['incomeNo'] = '�ϼ�';
				$rsArr['id'] = 'noId';
				$rows[] = $rsArr;
			}
		}
		$arr = array();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode($arr);
	}

	/**
	 * ��������ҳ��
	 */
	function c_toAdd() {
		//���������ֵ�
		$this->showDatadicts(array('incomeTypeList' => 'DKFS'));
		$this->showDatadicts(array('sectionTypeList' => 'DKLX'));
		$this->assign('incomeDate', day_date);

		//��ȡĬ�Ϸ�����
		$mailUser = $this->service->getMailUser_d('incomeMail');
		$this->assign('sendName', $mailUser['ccUserName']);
		$this->assign('sendUserId', $mailUser['ccUserId']);

		//��ȡ������˾����
		$this->assign('formBelong', $_SESSION['USER_COM']);
		$this->assign('formBelongName', $_SESSION['USER_COM_NAME']);
		$this->assign('businessBelong', $_SESSION['USER_COM']);
		$this->assign('businessBelongName', $_SESSION['USER_COM_NAME']);

		// ��ȡĬ�ϱұ�
		$this->assign('currency', '�����');
		$this->assign('rate', 1);

		//���Ե�������ҳ��
		$this->assign('formType', $_GET['formType']);
		$thisObjCode = $this->service->getBusinessCode($_GET['formType']);
		$this->display($thisObjCode . '-add');
	}

	/**
	 * �����������
	 */
	function c_add() {
		$object = $_POST[$this->objName];
		//���Ե�������ҳ��
		$thisClass = $this->service->getClass($object['formType']);
		if ($this->service->add_d($object, new $thisClass())) {
			msgRf('��ӳɹ���', '?model=finance_income_income&action=toAdd&formType=' . $object['formType']);
		}
	}

	/**
	 * ����������ͬ����ҳ��
	 */
	function c_toAddOther() {
		//���������ֵ�
		$this->showDatadicts(array('incomeTypeList' => 'DKFS'));
		$this->showDatadicts(array('sectionTypeList' => 'DKLX'));
		$this->assign('incomeDate', day_date);

		//��ȡĬ�Ϸ�����
		$mailUser = $this->service->getMailUser_d('incomeMail');
		$this->assign('sendName', $mailUser['ccUserName']);
		$this->assign('sendUserId', $mailUser['ccUserId']);

		//��ȡ������˾����
		$this->assign('formBelong', $_SESSION['USER_COM']);
		$this->assign('formBelongName', $_SESSION['USER_COM_NAME']);
		$this->assign('businessBelong', $_SESSION['USER_COM']);
		$this->assign('businessBelongName', $_SESSION['USER_COM_NAME']);

		$this->display('income-other-add');
	}

	/**
	 * ����������ͬ����
	 */
	function c_addOther() {
		if ($this->service->addOther_d($_POST[$this->objName])) {
			msgRf('��ӳɹ���', '?model=finance_income_income&action=toAddOther');
		} else {
			msgRf('���ʧ�ܣ�');
		}
	}

	/**
	 * �������ɵ���
	 */
	function c_addByPush() {
		//URLȨ�޿���
		$this->permCheck();

		//��ȡ���ӱ�����
		$income = $this->service->getInfoAndDetail_d($_GET ['id']);
		$this->assignFunc($income);

		//���Ե�������ҳ��
		$thisObjCode = $this->service->getBusinessCode($_GET['formType']);

		$this->assign('thisFormType', $_GET['formType']);
		$this->showDatadicts(array('incomeTypeList' => 'DKFS'), $income['incomeType']);
		$this->showDatadicts(array('sectionTypeList' => 'DKLX'), $income['sectionType']);
		$this->display($thisObjCode . '-addbypush');
	}

	/**
	 * ��ʼ����ҳ��
	 */
	function c_init() {
		//URLȨ�޿���
		$this->permCheck();
		$income = $this->service->get_d($_GET ['id']);
		$this->assignFunc($income);

		//���Ե�������ҳ��
		$thisObjCode = $this->service->getBusinessCode($income['formType']);
		$this->showDatadicts(array('incomeTypeList' => 'DKFS'), $income['incomeType']);
		$this->showDatadicts(array('sectionTypeList' => 'DKLX'), $income['sectionType']);
		$this->display($thisObjCode . '-edit');
	}

	/**
	 * �޸Ķ���
	 */
	function c_edit() {
		if ($this->service->edit_d($_POST [$this->objName])) {
			msgRf('�༭�ɹ���');
		}
	}

	/**
	 * ��ʾ���䵽��
	 */
	function c_toAllot() {
		//URLȨ�޿���
		$this->permCheck();
		$perm = isset($_GET['perm']) ? $_GET['perm'] : null;

		//��ȡ����Լ�������Ϣ
		$income = $this->service->getInfoAndDetail_d($_GET ['id']);

		// �Ƿ�ת��
		$income['isAdjust'] = $income['isAdjust'] ? '��' : '��';

		//���ض������
		$thisObjCode = $this->service->getBusinessCode($income['formType']);
		$this->assignFunc($income);
		if ($perm == 'view') {
			$this->assign('incomeType', $this->getDataNameByCode($income['incomeType']));
			$this->assign('sectionType', $this->getDataNameByCode($income['sectionType']));
			$this->display($thisObjCode . '-viewallot');
		} else {
			$this->assign('incomeTypeCN', $this->getDataNameByCode($income['incomeType']));
			$this->assign('sectionTypeCN', $this->getDataNameByCode($income['sectionType']));
			$this->showDatadicts(array('incomeTypeList' => 'DKFS'), $income['incomeType']);
			$this->showDatadicts(array('sectionTypeList' => 'DKLX'), $income['sectionType']);
			//��������
			$this->assign('thisDate', day_date);
			$this->display($thisObjCode . '-editallot');
		}
	}

	/**
	 * �������
	 */
	function c_allot() {
		if ($this->service->allot_d($_POST[$this->objName])) {
			msgRf('����ɹ�');
		} else {
			msgRf('����ʧ��');
		}
	}

	/**
	 * ��������б�
	 */
	function c_allotList() {
		$this->display('allotlist');
	}

	/**
	 * ��ȡ��ҳ����ת��Json--�������ҳ��
	 */
	function c_allotPageJson() {
		$service = $this->service;
		$service->getParam($_POST);
		$service->asc = true;
		$rows = $service->pageBySqlId('select_incomeAllot');
		//URL����
		$rows = $this->sconfig->md5Rows($rows);
		$arr = array();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode($arr);
	}

	/**
	 * ��������б�
	 */
	function c_manageList() {
		$this->display('managelist');
	}

	/**
	 * ��ȡ��ҳ����ת��Json--�������ҳ��
	 */
	function c_manageJson() {
		$service = $this->service;
		$service->getParam($_POST);

		$service->asc = true;
		$rows = $service->pageBySqlId('select_income');
		//URL����
		$rows = $this->sconfig->md5Rows($rows);
		$arr = array();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode($arr);
	}

	/**
	 * �߼�����
	 */
	function c_toSearch() {
		$year = date("Y");
		$yearStr = "";
		for ($i = $year; $i >= 2005; $i--) {
			$yearStr .= "<option value=$i>" . $i . "��</option>";
		}
		$this->assign('yearStr', $yearStr);
		$this->view('search');
	}

	/**
	 * �޸ı�עҳ��
	 */
	function c_toEditRemark() {
		//URLȨ�޿���
		$this->permCheck();
		$income = $this->service->get_d($_GET ['id']);
		$this->assignFunc($income);
		$this->display('editremark');
	}

	/**
	 * �޸Ķ���
	 */
	function c_editMsg() {
		if ($this->service->editEasy_d($_POST [$this->objName])) {
			msg('�༭�ɹ���');
		}
	}

	/**
	 * ѡ���б� - ����ѡ�񵽿
	 */
	function c_selectPage() {
		$this->assign('objId', $_GET['objId']);
		$this->assign('objType', $_GET['objType']);
		$this->display('selectlist');
	}

	/**
	 * ѡ���б� - ����Դ
	 */
	function c_selectPageJson() {
		$service = $this->service;
		$service->getParam($_POST);

		$service->asc = true;
		$rows = $service->pageBySqlId('select_detail');
		//URL����
		$rows = $this->sconfig->md5Rows($rows);
		$arr = array();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count;
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode($arr);
	}

	/************************ excel���벿��*****************************/

	/**
	 *��ת��excel�ϴ�ҳ��
	 */
	function c_toExcel() {
		$this->display('excel');
	}

	/**
	 * �ϴ�EXCEL
	 */
	function c_upExcel() {
		$resultArr = $this->service->addExecelData_d($_POST['isCheck']);
		$title = '������Ϣ�������б�';
		$thead = array('������Ϣ', '������');
		echo util_excelUtil::showResult($resultArr, $title, $thead);
	}

	/**
	 * excel����
	 */
	function c_toExcOut() {
		$service = $this->service;
		$service->getParam($_GET); //����ǰ̨��ȡ�Ĳ�����Ϣ
		$service->sort = 'c.incomeDate';
		$service->asc = false;
		$rows = $service->list_d('select_excelout');

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
		return model_finance_common_financeExcelUtil::exportIncome($rows);
	}
}