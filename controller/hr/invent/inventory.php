<?php

/**
 * @author Administrator
 * @Date 2012��5��30�� 14:38:15
 * @version 1.0
 * @description:Ա���̵����Ʋ�
 */
class controller_hr_invent_inventory extends controller_base_action {

	function __construct() {
		$this->objName = "inventory";
		$this->objPath = "hr_invent";
		parent :: __construct();
	}

	function c_list() {
		$parentId = isset ($_GET['parentId']) ? $_GET['parentId'] : '';
		$parentName = isset ($_GET['parentName']) ? $_GET['parentName'] : '';
		$this->assign('parentId', $parentId);
		$this->assign('parentName', $parentName);
		$this->view('list');
	}

	/*
	 * ��ת��Ա���̵���б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * �鿴ҳ�� - ����Ȩ��
	 */
	function c_pageForRead(){
		$this->view('listforread');
	}

	/**
	 * ��ת����Ա�̵�����б�
	 */
    function c_toPersonnelList() {
    	if(!$this->service->this_limit['�̵���Ϣ�鿴']){
			echo "<script>alert('û��Ȩ��!');</script>";
			exit();
		}
      	$userNo=isset($_GET ['userNo'])?$_GET ['userNo']:'';//Ա�����
      	$userAccount=isset($_GET ['userAccount'])?$_GET ['userAccount']:'';//Ա���˺�
      	$this->assign ( 'userNo', $userNo );
      	$this->assign ( 'userAccount', $userAccount );
      	$this->view('personnel-list');
    }

	/**
	 * ��ȡ��ҳ����ת��Json -- ����Ȩ��
	 */
	function c_pageJsonForRead() {
		$service = $this->service;
		$rows = array();

		$service->getParam ( $_REQUEST );
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ

		$otherdatasDao = new model_common_otherdatas();
		$personLimit = $otherdatasDao->getUserPriv('hr_personnel_personnel',$_SESSION['USER_ID'],$_SESSION['DEPT_ID'],$_SESSION['JOB_ID']);
		//ϵͳȨ��
		$sysLimit = $personLimit['����Ȩ��'];

		//���´� �� ȫ�� ����
		if(strstr($sysLimit,';;')){

			$service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
			$rows = $service->page_d();

		}else if(!empty($sysLimit)){//���û��ѡ��ȫ���������Ȩ�޲�ѯ����ֵ
			$_POST['deptIdArr'] = $sysLimit;
			$service->getParam($_POST); //����ǰ̨��ȡ�Ĳ�����Ϣ
			$rows = $service->page_d();
		}

		$arr = array ();
		$arr ['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr ['totalSize'] = $service->count ? $service->count : ($rows ? count ( $rows ) : 0);
		$arr ['page'] = $service->page;
		echo util_jsonUtil::encode ( $arr );
	}

	/**
	 * ��ת������Ա���̵��ҳ��
	 */
	function c_toAdd() {
		$this->view('add');
	}

	/**
	 * ��ת���༭Ա���̵��ҳ��
	 */
	function c_toEdit() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('edit');
	}

	/**
	 * ��ת���鿴Ա���̵��ҳ��
	 */
	function c_toView() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		$this->view('view');
	}

	/**
	 * �б�߼���ѯ
	 */
	function c_toSearch(){
        $this->view('search');
	}

	/*
	 * ��ת������ҳ��
	 */
	function c_toImport() {
		$this->view('import');
	}

	/**
	 * Ա���̵���Ϣ����
	 */
	function c_import() {
		$objKeyArr = array (
			0 => 'userNo',
			1 => 'userName',
			2 => 'deptNameS',
			3 => 'position',
			4 => 'inventoryDate',
			5 => 'entryDate',
			6 => 'alternative',
			7 => 'matching',
			8 => 'critical',
			9 => 'isCore',
			10 => 'recruitment',
			11 => 'performance',
			12 => 'examine',
			13 => 'preEliminated',
			14 => 'remark',
			15 => 'adjust'
		); //�ֶ�����
		$resultArr = $this->service->import_d($objKeyArr);
	}

	function c_excelIn(){
		set_time_limit(0);
		$resultArr = $this->service->addExecelData_d ();

		$title = '��Ա�̵㵼�����б�';
		$thead = array( '������Ϣ','������' );
		echo util_excelUtil::showResult($resultArr ,$title ,$thead);
	}

	/**
	 * ��ת��excel����ҳ��
	 */
	function c_toExcelOut() {
		$this->view('excelout');
	}

	/**
	 * excel����
	 */
	function c_excelOut() {
		set_time_limit(0);
		$formData = $_POST[$this->objName];

		if(!empty($formData['userNo'])) //Ա�����
			$this->service->searchArr['userNoM'] = $formData['userNo'];
		if(!empty($formData['userName'])) //Ա������
			$this->service->searchArr['userNameM'] = $formData['userName'];

		if(!empty($formData['deptName'])) //����
			$this->service->searchArr['deptName'] = $formData['deptName'];
		if(!empty($formData['position'])) //ְλ
			$this->service->searchArr['positionSearch'] = $formData['position'];

		if(!empty($formData['entryDateBegin'])) //��ְ��ʼʱ��
			$this->service->searchArr['entryDateBegin'] = $formData['entryDateBegin'];
		if(!empty($formData['entryDateEnd'])) //��ְ����ʱ��
			$this->service->searchArr['entryDateEnd'] = $formData['entryDateEnd'];

		if(!empty($formData['inventoryDateBegin'])) //�̵㿪ʼʱ��
			$this->service->searchArr['inventoryDateBegin'] = $formData['inventoryDateBegin'];
		if(!empty($formData['inventoryDateEnd'])) //�̵����ʱ��
			$this->service->searchArr['inventoryDateEnd'] = $formData['inventoryDateEnd'];

		$rows = $this->service->listBySqlId('select_default');
		if (!$rows) {
			echo "<meta http-equiv='Content-Type' content='text/html; charset=gbk'>"
				 ."<script type='text/javascript'>"
				 ."alert('û�м�¼!');self.parent.tb_remove();"
				 ."</script>";
			exit();
		}

		$rowData = array();
		foreach($rows as $key => $val) {
			$rowData[$key]['userNo'] = $val['userNo'];
			$rowData[$key]['userName'] = $val['userName'];
			$rowData[$key]['deptNameS'] = $val['deptNameS'];
			$rowData[$key]['position'] = $val['position'];
			$rowData[$key]['inventoryDate'] = $val['inventoryDate'];
			$rowData[$key]['entryDate'] = $val['entryDate'];
			$rowData[$key]['alternative'] = $val['alternative'];
			$rowData[$key]['matching'] = $val['matching'];
			$rowData[$key]['isCritical'] = $val['isCritical'];
			$rowData[$key]['critical'] = $val['critical'];
			$rowData[$key]['isCore'] = $val['isCore'];
			$rowData[$key]['recruitment'] = $val['recruitment'];
			$rowData[$key]['performance'] = $val['performance'];
			$rowData[$key]['examine'] = $val['examine'];
			$rowData[$key]['preEliminated'] = $val['preEliminated'];
			$rowData[$key]['remark'] = $val['remark'];
			$rowData[$key]['adjust'] = $val['adjust'];
			$rowData[$key]['workQuality'] = $val['workQuality'];
			$rowData[$key]['workEfficiency'] = $val['workEfficiency'];
			$rowData[$key]['workZeal'] = $val['workZeal'];
		}

		$colArr  = array();
		$modelName = '����-��Ա�̵���Ϣ';
		return model_hr_recruitment_importHrUtil::exportExcelUtil($colArr ,$rowData ,$modelName);
	}
}
?>