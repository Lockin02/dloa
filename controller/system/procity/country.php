<?php
/**
 *
 * 国家控制层类
 * @author chris
 *
 */
class controller_system_procity_country extends controller_base_action {

	function __construct() {
		$this->objName = "country";
		$this->objPath = "system_procity";
		parent::__construct ();
	}
	/*
	 * 跳转到国家
	 */
    function c_page() {
      $this->show->display($this->objPath . '_' . $this->objName . '-list');
    }

	/**
	 * 跳转到新增页面
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}


	/**
	 * 修改对象
	 */
	function c_toEdit() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view('edit');
	}

    /**构造国家树
	*author can
	*2011-8-25
	*/
    function c_getChildren(){
		$service = $this->service;
		$parentId = isset($_POST['id'])? $_POST['id'] : -1;
		$service->searchArr['parentId'] = $parentId;
		$service->asc = false;
		$rows=$service->listBySqlId('select_country');

		echo util_jsonUtil :: encode ($rows);
	}

	/**
	 * 国家编码重复性校验
	 */
	function c_checkCountryCode() {
		$countryCode = isset ( $_GET ['countryCode'] ) ? $_GET ['countryCode'] : false;
		$id = isset ( $_GET ['id'] ) ? $_GET ['id'] : null;
		$searchArr = array ("countryCode" => $countryCode );
		$isRepeat = $this->service->isRepeat ( $searchArr, $id );
		if ($isRepeat) {
			echo "0";
		} else {
			echo "1";
		}
	}

}

?>
