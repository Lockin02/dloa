<?php

/**
 * @author Administrator
 * @Date 2011��7��22�� 9:41:21
 * @version 1.0
 * @description:oa_system_region���Ʋ� ����---�����˱�
 */
class controller_system_region_region extends controller_base_action
{

	function __construct() {
		$this->objName = "region";
		$this->objPath = "system_region";
		parent::__construct();
	}

	/**
	 * ��ת��oa_system_region
	 */
	function c_page() {
		$this->view('list');
	}

	function c_toAdd() {
		$this->assign('businessBelong', $_SESSION['Company']);
		//��ȡ��˾����
		$branchDao = new model_deptuser_branch_branch();
		$companyInfo = $branchDao->getByCode($_SESSION['Company']);
		$this->assign('businessBelongName', $companyInfo['NameCN']);

		$this->assign('formBelong', $_SESSION['COM_BRN_PT']);
		$this->assign('formBelongName', $_SESSION['COM_BRN_CN']);

		$this->view('add');
	}


	/**
	 * ��ʼ������
	 */
	function c_init() {
		$this->permCheck(); //��ȫУ��
		$obj = $this->service->get_d($_GET ['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		if (isset ($_GET ['perm']) && $_GET ['perm'] == 'view') {
			if ($obj['isStart'] == '0') {
				$this->assign('isStart', '����');
			} else {
				$this->assign('isStart', '�ر�');
			}
			$this->assign('module', $this->getDataNameByCode($obj['module']));
			$this->display('view');
		} else {
			if ($obj['isStart'] == '0') {
				$this->assign('isStartY', 'checked');
			} else {
				$this->assign('isStartN', 'checked');
			}
			if ($obj['expand'] == '0') {
				$this->assign('isExpandN', 'checked');
			} else {
				$this->assign('isExpandY', 'checked');
			}
//			$this->assign('module', $this->getDataNameByCode($obj['module']));
			$this->showDatadicts(array('module' => 'HTBK'), $obj['module']);
			$this->display('edit');
		}
	}

	/**
	 * ��������
	 */
	function c_listAreaPrincipalJson() {
		$service = $this->service;
		$service->getParam($_REQUEST);
		$service->groupBy = "areaPrincipalId,areaPrincipal";
		$rows = $service->list_d("select_principal");
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows($rows);
		echo util_jsonUtil::encode($this->addRoot($rows, 'areaPrincipal'));
	}

	/**
	 * ajax��ȡ��ͬ��������
	 */
	function c_ajaxConRegion() {
		//��ȡ���۸�������Ϣ
        if(isset($_POST['getAll']) && $_POST['getAll']){
            $rs = $this->service->conRegion_d($_POST['customerType'],$_POST['province'],$_POST['module'],
                isset($_POST['businessBelong']) ? $_POST['businessBelong'] : '',$_POST['getAll']);
        }else{
            $rs = $this->service->conRegion_d($_POST['customerType'],$_POST['province'],$_POST['module'],
                isset($_POST['businessBelong']) ? $_POST['businessBelong'] : '');
        }
		echo util_jsonUtil::encode($rs);
	}

	/**
	 * ajax��ȡ��ͬ���������ִ������
	 * ���һ�θ��£�2016-12-15 PMS 2313
	 */
	function c_ajaxChkExeDept(){
		$areaCode = (isset($_POST['areaCode']))? $_POST['areaCode'] : '';
		$rsArr = $this->service->chkExeDeptByAreaId_d($areaCode);
		echo util_jsonUtil::encode($rsArr);
	}

	//ajax��ȡ��Ӧ�������� �����Ĳ�����
	function c_ajaxConRegionByName() {
        $businessBelong = '';
	    $needAll = isset($_REQUEST['needAll'])? $_REQUEST['needAll'] : 0;// �ж��Ƿ���Ҫ�������е����� 2017-01-12 ����PMS2383
        $getCompanyByUid = isset($_REQUEST['getCompanyByUid'])? $_REQUEST['getCompanyByUid'] : 0;// �ж��Ƿ���Ҫ����UID��ȡ������˾ 2017-01-12 ����PMS2383
        if($getCompanyByUid){
            $userId = isset($_REQUEST['userId'])? $_REQUEST['userId'] : '';
            $sql = "SELECT b.NameCN AS businessBelong FROM user u LEFT JOIN branch_info b ON u.Company = b.NamePT WHERE u.USER_ID = '{$userId}';";
            $arr = $this->service->_db->getArray($sql);
            if($arr){
                $businessBelong = $arr[0]['businessBelong'];
            }
        }

		//��ȡ���۸�������Ϣ
		$rs = $this->service->conRegionByName_d(util_jsonUtil::iconvUTF2GB($_POST['customerType']),
				util_jsonUtil::iconvUTF2GB($_POST['province']),
				isset($_POST['module']) ? util_jsonUtil::iconvUTF2GB($_POST['module']) : '',
				isset($_POST['businessBelong']) ? util_jsonUtil::iconvUTF2GB($_POST['businessBelong']) : $businessBelong,$needAll);
		echo util_jsonUtil::encode($rs);
	}
	
	/**
	 * ajax��ȡ��ͬ�������
	 */
	function c_ajaxConModule() {
		$province = util_jsonUtil::iconvUTF2GB($_POST['province']);
		$city = util_jsonUtil::iconvUTF2GB($_POST['city']);
		$customerTypeName = util_jsonUtil::iconvUTF2GB($_POST['customerTypeName']);
		$personId = $_POST['personId'];
		
		//��ȡ���۸�������Ϣ
		$rs = $this->service->conModule_d($province,$city,$customerTypeName,$personId);
		if($rs){
			echo util_jsonUtil::encode($rs);
		}else{
			echo "";
		}
	}
}