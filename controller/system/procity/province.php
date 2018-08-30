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
	 * 获取分页数据转成Json
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
		//$service->getParam ( $_POST ); //设置前台获取的参数信息

		$service->asc = false;
		$rows = $service->page_d();
		//数据加入安全码
		$rows = $this->sconfig->md5Rows($rows);
		$arr = array ();
		$arr['collection'] = $rows;
		//count()如果 参数 不是数组类型，将返回 1，这里需要判断rows,如果返回false，直接返回0
		$arr['totalSize'] = $service->count ? $service->count : ($rows ? count($rows) : 0);
		$arr['page'] = $service->page;
		$arr['advSql'] = $service->advSql;
		$arr['listSql'] = $service->listSql;
		echo util_jsonUtil :: encode($arr);
	}

	/**
	* 重写修改
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
	 * 省市级联
	 */
	function c_show() {
		$this->show->display($this->objPath . '_' . $this->objName . '-show');
	}

	/**
	 * 根据根节点id获取信息
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

		//把是否叶子值0转成false，1转成true
		function toBoolean($row) {
			$row['leaf'] = $row['leaf'] == 0 ? false : true;
			return $row;
		}
		echo util_jsonUtil :: encode(array_map("toBoolean", $rows));

	}
	/**
	 * 根据id获取编码
	 */
	function c_getProTypeCodeById() {
		$province = $this->service->getProTypeCodeById($_POST['id']);
		$row = array (
			"provincecode" => $province
		);
		echo util_jsonUtil :: encode($row);
	}
	/**
	 * 根据类型编码获取上级
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

		//把是否叶子值0转成false，1转成true
		function toBoolean($row) {
			$row['leaf'] = $row['leaf'] == 0 ? false : true;
			return $row;
		}
		echo util_jsonUtil :: encode(array_map("toBoolean", $rows));
	}
	/**
	 * 新增是如果有父类型id及名称默认设置
	 */
	function c_toAdd() {
		$parentId = $_GET['parentId'];
		$parentName = $_GET['parentName'];
		$this->show->assign("parentId", $parentId);
		$this->show->assign("parentName", $parentName);
		$this->view("add");
	}

	/**
	 * 省份编码重复性校验
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
	 * 获取省份列表 名称 - 名称
	 */
	function c_getProvinceNameArr() {
		$rows = $this->service->list_d('select_forSelectName');
		echo util_jsonUtil :: encode($rows);
	}

	/**
	 * 获取省份列表 名称 - 名称
	 */
	function c_getProvinceForEditGrid() {
		$this->service->getParam($_REQUEST);
		$this->service->asc = false;
		$rows = $this->service->list_d('select_editgrid');
		echo util_jsonUtil :: encode($rows);
	}

	/**
	 * 同步获取省份与城市
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
	 * 异步获取省份与城市
	 */
	function c_listProOrCity() {
		$proId = $_POST['id'];
		if (empty ($proId)) { //获取省份
			$service = $this->service;
			$rows = $service->list_d();
			function fn($row) {
				$row['isParent'] = true;
				$row['name'] = $row['provinceName'];
				return $row;
			}
			$rows = array_map("fn", $rows);
			$rows = $this->addRoot($rows, 'name');
		} else { //获取城市
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
	 * 获取所有数据返回json
	 */
	function c_listJsonAndRoot() {
		$service = $this->service;
		$service->getParam($_REQUEST);
		$rows = $service->list_d();
		echo util_jsonUtil :: encode($this->addRoot($rows, 'provinceName'));
	}

	/**
	 * 工程省份设置
	 */
	function c_esmList() {
		$this->view('listesm');
	}

	/**
	 * 工程省份修改
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
	 * 修改对象
	 */
	function c_editEsm($isEditInfo = false) {
		//		$this->permCheck (); //安全校验
		$object = $_POST[$this->objName];
		if ($this->service->editEsm_d($object, $isEditInfo)) {
			msg('编辑成功！');
		}
	}

	/**
	 * 获取所有数据返回json
	 */
	function c_listJsonSort() {
		$service = $this->service;
		$service->getParam($_REQUEST);
		$service->asc = false;
		$rows = $service->list_d();
		echo util_jsonUtil :: encode($rows);
	}

	/**
	 * 获取下级省份
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
	 * 获取省份和负责人
	 */
	function c_getProvinceAndManager(){
		$service = $this->service;
		$service->getParam($_POST);
		$service->asc = false;
		$rows = $service->listBySqlId('select_provinceandmanager');
		//获取服务经理的数据
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