<?php
/**
 * @description: 供应商联系人控制层类
 * @date 2010-11-9 下午02:51:01
 */
class controller_supplierManage_register_supplinkman extends controller_base_action{
  function __construct() {
	$this->objName = "supplinkman";
	$this->objPath = "supplierManage_register";
	parent::__construct();
  }
 /**
 * @desription 跳转到联系人列表页面
 * @param tags
 * @date 2010-11-8 下午02:18:04
 */
	function c_tolinkmanlist () {
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
	/**
     * 注册供应商信息保存
	 * @date 2010-9-20 下午02:06:22
	 */
	function c_addlinkman() {
		$linkman = $_POST [$this->objName];

		//echo("<pre>");
		//print_r($linkman);
		$id = $this->service->add_d ( $linkman, true );

		if ($id) {
			msg( '新增联系人成功！' );
			}

		}
}
?>
