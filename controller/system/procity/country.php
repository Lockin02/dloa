<?php
/**
 *
 * ���ҿ��Ʋ���
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
	 * ��ת������
	 */
    function c_page() {
      $this->show->display($this->objPath . '_' . $this->objName . '-list');
    }

	/**
	 * ��ת������ҳ��
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}


	/**
	 * �޸Ķ���
	 */
	function c_toEdit() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view('edit');
	}

    /**���������
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
	 * ���ұ����ظ���У��
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
