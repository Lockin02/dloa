<?php
/**
 * @author Michael
 * @Date 2015年3月23日 16:29:21
 * @version 1.0
 * @description:基础物料配置数据控制层
 */
class controller_manufacture_basic_productconfigitem extends controller_base_action {

	function __construct() {
		$this->objName = "productconfigitem";
		$this->objPath = "manufacture_basic";
		parent::__construct ();
	 }

	/**
	 * 跳转到基础物料配置数据列表
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 跳转到新增基础物料配置数据页面
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}

	/**
	 * 跳转到编辑基础物料配置数据页面
	 */
	function c_toEdit() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'edit');
	}

	/**
	 * 跳转到查看基础物料配置数据页面
	 */
	function c_toView() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'view' );
	}

	/**
	 * 获取表格所有数据的Json
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

		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}
 }
?>