<?php
/**
 * @author Michael
 * @Date 2015��3��23�� 16:29:21
 * @version 1.0
 * @description:���������������ݿ��Ʋ�
 */
class controller_manufacture_basic_productconfigitem extends controller_base_action {

	function __construct() {
		$this->objName = "productconfigitem";
		$this->objPath = "manufacture_basic";
		parent::__construct ();
	 }

	/**
	 * ��ת�������������������б�
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * ��ת����������������������ҳ��
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}

	/**
	 * ��ת���༭����������������ҳ��
	 */
	function c_toEdit() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'edit');
	}

	/**
	 * ��ת���鿴����������������ҳ��
	 */
	function c_toView() {
		$this->permCheck (); //��ȫУ��
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'view' );
	}

	/**
	 * ��ȡ����������ݵ�Json
	 */
	function c_tableJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->list_d ('select_table');

		if (is_array($rows)) {
			$rowData = array();
			foreach ($rows as $key => $val) {
				if (!is_array($rowData[$val['rowNum']])) {
					$rowData[$val['rowNum']] = array();
				}
				$rowData[$val['rowNum']][$val['colCode']] = $val['colContent'];
			}
			$rows = $rowData;
		}

		//���ݼ��밲ȫ��
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}
 }
?>