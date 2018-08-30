<?php
/**
 * 供应商控制层类
 */
class controller_supplierManage_register_register extends controller_base_action {


	function __construct() {
		$this->objName = "register";
		$this->objPath = "supplierManage_register";
		parent::__construct ();
	}

/**
 * @desription 跳转到供应商列表页面
 * @param tags
 * @date 2010-11-8 下午02:18:04
 */
	function c_toSupplierlist () {
		$this->show->display ( $this->objPath . '_' . $this->objName . '-list' );
	}
	/**
	 * 跳转到新增页面
	 */
	function c_toAdd() {
		$this->show->display ( $this->objPath . '_' . $this->objName . '-add' );
	}
	/**
	 * @desription 跳转到修改页面
	 * @param tags
	 * @date 2010-11-8 下午03:55:02
	 */
	function c_toEdit () {
		$rows = $this->service->get_d ( $_GET ['id'] );
		foreach ( $rows as $key => $val ) {
			$this->show->assign ( $key, $val );
		}
		$this->show->display ( $this->objPath . '_' . $this->objName . '-edit' );
	}
	/*
     * 注册供应商信息保存
	 * @date 2010-9-20 下午02:06:22
	 */
	function c_addsupp() {
		$supp = $_POST [$this->objName];

		if ($_GET ['id']) {
			$supp['ExaStatus'] = 'WQD';
		}
		//echo("<pre>");
		//print_r($supp);
		$id = $this->service->add_d ( $supp, true );

		if ($id) {
			msgGo ( '新增任务成功！' ,"?model=supplierManage_register_supplinkman&action=tolinkmanlist&id=$id");
			}

		}

}
?>
