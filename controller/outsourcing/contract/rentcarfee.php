<?php
/**
 * @author Michael
 * @Date 2014年9月29日 19:22:05
 * @version 1.0
 * @description:租车合同附加费用控制层
 */
class controller_outsourcing_contract_rentcarfee extends controller_base_action {

	function __construct() {
		$this->objName = "rentcarfee";
		$this->objPath = "outsourcing_contract";
		parent::__construct ();
	 }

	/**
	 * 跳转到租车合同附加费用列表
	 */
	function c_page() {
		$this->view('list');
	}

	/**
	 * 跳转到新增租车合同附加费用页面
	 */
	function c_toAdd() {
		$this->view ( 'add' );
	}

	/**
	 * 跳转到编辑租车合同附加费用页面
	 */
	function c_toEdit() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		$this->view ( 'edit' );
	}

	/**
	 * 跳转到查看租车合同附加费用页面
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
	 * 根据车牌获取所有数据返回json
	 */
	function c_listJsonByCar() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$rows = $service->list_d('select_car');
		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}
}
?>