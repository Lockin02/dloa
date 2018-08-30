<?php
/*
 * Created on 2010-7-17
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

class controller_system_procity_province extends controller_base_action {

	function __construct() {
		$this->objName = "province";
		$this->objPath = "system_procity";
		parent :: __construct();
	}

	function c_page() {
		$this->view('list');
	}

	/**
	 * ��ȡ��ҳ����ת��Json
	 */
	function c_pageJson() {
		$service = $this->service;

        if(isset($_REQUEST['needFlitCode']) && $_REQUEST['needFlitCode'] == 1){
            unset($_REQUEST['needFlitCode']);
            $otherDataDao = new model_common_otherdatas();
            $provinceCodeFilterArr = $otherDataDao->getConfig('provinceCodeFilter');
            $_REQUEST['provinceCodeFlit'] = rtrim($provinceCodeFilterArr,",");
        }

		$service->getParam($_REQUEST);
		//$service->getParam ( $_POST ); //����ǰ̨��ȡ�Ĳ�����Ϣ

		$service->asc = false;
		$rows = $service->page_d();
		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows($rows);
		$arr = array ();
		$arr['collection'] = $rows;
		//count()��� ���� �����������ͣ������� 1��������Ҫ�ж�rows,�������false��ֱ�ӷ���0
		$arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
		$arr['page'] = $service->page;
		$arr['advSql'] = $service->advSql;
		$arr['listSql'] = $service->listSql;
		echo util_jsonUtil :: encode($arr);
	}

	/**
	* ��д�޸�
	*/
	function c_init() {
		$returnObj = $this->objName;
		$$returnObj = $this->service->get_d($_GET['id']);
		foreach ($$returnObj as $key => $val) {
			$this->show->assign($key, $val);
		}
		$this->view("edit");
	}

	/**
	 * ʡ�м���
	 */
	function c_show() {
		$this->show->display($this->objPath . '_' . $this->objName . '-show');
	}

	/**
	 * ���ݸ��ڵ�id��ȡ��Ϣ
	 */
	function c_listProvinceByParentId() {
		$searchArr = array (
			"parentId" => isset ($_POST['id']
		) ? $_POST['id'] : PARENT_ID);
		$service = $this->service;
		$service->searchArr = $searchArr;
		$service->sort = 'orderNum';
		$service->asc = false;
		$rows = $service->list_d();

		//���Ƿ�Ҷ��ֵ0ת��false��1ת��true
		function toBoolean($row) {
			$row['leaf'] = $row['leaf'] == 0 ? false : true;
			return $row;
		}
		echo util_jsonUtil :: encode(array_map("toBoolean", $rows));

	}
	/**
	 * ����id��ȡ����
	 */
	function c_getProTypeCodeById() {
		$province = $this->service->getProTypeCodeById($_POST['id']);
		$row = array (
			"provincecode" => $province
		);
		echo util_jsonUtil :: encode($row);
	}
	/**
	 * �������ͱ����ȡ�ϼ�
	 */
	function c_listProTypeByTypecode() {
		$searchArr = array (
			"provincecode" => $_GET['provincecode'],
			"parentId" => $_GET['parentId']
		);
		$service = $this->service;
		$service->searchArr = $searchArr;
		$service->sort = 'orderNum';
		$rows = $service->list_d();

		//���Ƿ�Ҷ��ֵ0ת��false��1ת��true
		function toBoolean($row) {
			$row['leaf'] = $row['leaf'] == 0 ? false : true;
			return $row;
		}
		echo util_jsonUtil :: encode(array_map("toBoolean", $rows));
	}
	/**
	 * ����������и�����id������Ĭ������
	 */
	function c_toAdd() {
		$parentId = $_GET['parentId'];
		$parentName = $_GET['parentName'];
		$this->show->assign("parentId", $parentId);
		$this->show->assign("parentName", $parentName);
		$this->view("add");
	}

	/**
	 * ʡ�ݱ����ظ���У��
	 */
	function c_checkProvinceCode() {
		$provinceCode = isset ($_GET['provinceCode']) ? $_GET['provinceCode'] : false;
		$id = isset ($_GET['id']) ? $_GET['id'] : null;
		$searchArr = array (
			"provinceCode" => $provinceCode
		);
		$isRepeat = $this->service->isRepeat($searchArr, $id);
		if ($isRepeat) {
			echo "0";
		} else {
			echo "1";
		}
	}

	/**
	 * ��ȡʡ���б� ���� - ����
	 */
	function c_getProvinceNameArr() {
		$rows = $this->service->list_d('select_forSelectName');
		echo util_jsonUtil :: encode($rows);
	}

	/**
	 * ��ȡʡ���б� ���� - ����
	 */
	function c_getProvinceForEditGrid() {
		$this->service->getParam($_REQUEST);
		$this->service->asc = false;
		$rows = $this->service->list_d('select_editgrid');
		echo util_jsonUtil :: encode($rows);
	}

	/**
	 * ͬ����ȡʡ�������
	 */
	function c_listProAndCity() {
		$service = $this->service;
		$rows = array ();
		if (empty ($_POST['id'])) {
			$rows = $service->getProAndCity();
			$rows = $this->addRoot($rows, 'name');
		}
		echo util_jsonUtil :: encode($rows);
	}

	/**
	 * �첽��ȡʡ�������
	 */
	function c_listProOrCity() {
		$proId = $_POST['id'];
		if (empty ($proId)) { //��ȡʡ��
			$service = $this->service;
			$rows = $service->list_d();
			function fn($row) {
				$row['isParent'] = true;
				$row['name'] = $row['provinceName'];
				return $row;
			}
			$rows = array_map("fn", $rows);
			$rows = $this->addRoot($rows, 'name');
		} else { //��ȡ����
			$cityModel = new model_system_procity_city();
			$rows = $cityModel->getCitysByProviceId($proId);
			function fn($row) {
				$row['isParent'] = false;
				$row['name'] = $row['cityName'];
				return $row;
			}
			$rows = array_map("fn", $rows);
		}

		if (empty ($rows)) {
			$rows = array ();
		}

		echo util_jsonUtil :: encode($rows);
	}

	/**
	 * ��ȡ�������ݷ���json
	 */
	function c_listJsonAndRoot() {
		$service = $this->service;
		$service->getParam($_REQUEST);
		$rows = $service->list_d();
		echo util_jsonUtil :: encode($this->addRoot($rows, 'provinceName'));
	}

	/**
	 * ����ʡ������
	 */
	function c_esmList() {
		$this->view('listesm');
	}

	/**
	 * ����ʡ���޸�
	 */
	function c_toEditEsm() {
		$returnObj = $this->objName;
		$$returnObj = $this->service->get_d($_GET['id']);
		foreach ($$returnObj as $key => $val) {
			$this->show->assign($key, $val);
		}
		$this->view("editesm");
	}

	/**
	 * �޸Ķ���
	 */
	function c_editEsm($isEditInfo = false) {
		//		$this->permCheck (); //��ȫУ��
		$object = $_POST[$this->objName];
		if ($this->service->editEsm_d($object, $isEditInfo)) {
			msg('�༭�ɹ���');
		}
	}

	/**
	 * ��ȡ�������ݷ���json
	 */
	function c_listJsonSort() {
		$service = $this->service;
		$service->getParam($_REQUEST);
		$service->asc = false;
		$rows = $service->list_d();
		echo util_jsonUtil :: encode($rows);
	}

	/**
	 * ��ȡ�¼�ʡ��
	 */
	function c_getChildren() {
		$service = $this->service;
		$parentId = isset ($_POST['id']) ? $_POST['id'] : -1;
		$service->searchArr['parentId'] = $parentId;
		$service->asc = false;
		$rows = $service->listBySqlId('select_mylist2');
		echo util_jsonUtil :: encode($rows);
	}

	/**
	 * ��ȡʡ�ݺ͸�����
	 */
	function c_getProvinceAndManager(){
		$service = $this->service;
		$service->getParam($_POST);
		$service->asc = false;
		$rows = $service->listBySqlId('select_provinceandmanager');
		//��ȡ�����������
		$managerDao = new model_engineering_officeinfo_manager();
		$managerDao->searchArr['businessBelong'] = $_POST['businessBelong'];
		$managerDao->searchArr['productLine'] = $_POST['productLine'];
		$managerArr = $managerDao->list_d();
		if($managerArr){
			$managerNewArr = array();
			foreach($managerArr as $v){
				if(!empty($v['managerId'])){
					$managerNewArr[$v['provinceId']] = $v;
				}
			}

			foreach($rows as &$v){
				if(isset($managerNewArr[$v['id']])){
					$v['name'] = $v['name'].'('.$managerNewArr[$v['id']]['managerName'].')';
					$v['managerName'] = $managerNewArr[$v['id']]['managerName'];
					$v['managerId'] = $managerNewArr[$v['id']]['managerId'];
				}else{
					$v['managerName'] = '';
					$v['managerId'] = '';
				}
			}
		}

		echo util_jsonUtil :: encode($rows);
	}
}