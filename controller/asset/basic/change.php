<?php
/**
 * 变动方式控制层类
 *  @author chenzb
 */
class controller_asset_basic_change extends controller_base_action {

	function __construct() {
		$this->objName = "change";
		$this->objPath = "asset_basic";

		parent :: __construct();
	}

	/**
		 * 跳转到变动方式信息列表
		 */
	function c_page() {
		$this->view('list');
	}
	/**
	 * 跳转到新增页面
	 */
	function c_toAdd() {

		$this->view('add');
	}
	/**
		 * 初始化对象
		 */
	function c_init() {
		$this->permCheck(); //安全校验
		$obj = $this->service->get_d($_GET['id']);
		foreach ($obj as $key => $val) {
			$this->assign($key, $val);
		}
		if (isset ($_GET['perm']) && $_GET['perm'] == 'view') {
			$this->view('view');
		} else {
			$this->view('edit');
		}
	}

	/**
	 * @ ajax判断项
	 *
	 */
	function c_ajaxDataCode() {
		$service = $this->service;
		$projectName = isset ($_GET['subcode']) ? $_GET['subcode'] : false;

		$searchArr = array (
			"subcode" => $projectName
		);

		$isRepeat = $service->isRepeat($searchArr, "");

		if ($isRepeat) {
			echo "0";
		} else {
			echo "1";
		}
	}

	/**
	 * 批量删除对象
	 */
	function c_deletes() {
		//$this->permDelCheck ();
		$message = "";
		try {
			$this->service->deletes_d($_GET['id']);
			echo 1;
		} catch (Exception $e) {
			echo 0;
		}
	}


	/**
	 * 跳转到变动方式导入页面
	 * @create 2012年1月30日 10:08:55
	 * @author zengzx
	 */
    function c_toImport() {
      $this->view( 'import' );
    }

	/**
	 * 变动方式导入
	 * @create 2012年1月30日 10:08:59
	 * @author zengzx
	 */
	function c_import(){
		$objKeyArr = array (
			0 => 'code',
			1 => 'name',
			2 => 'type',
			3 => 'digest'
		); //字段数组
		$resultArr = $this->service->import_d ( $objKeyArr );
	}

}
?>