<?php
/**
 * @author Administrator
 * @Date 2012年3月8日 14:15:29
 * @version 1.0
 * @description:合同收开计划表控制层
 */
class controller_contract_contract_financialplan extends controller_base_action {

	function __construct() {
		$this->objName = "financialplan";
		$this->objPath = "contract_contract";
		parent::__construct ();
	}

	/*
	 * 跳转到合同联系人信息表列表
	 */
    function c_page() {
    	$this->view('list');
    }

   /**
	 * 跳转到新增合同联系人信息表页面
	 */
	function c_toAdd() {
    	$this->view ( 'add' );
	}

   /**
	 * 跳转到编辑合同联系人信息表页面
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
	 * 跳转到查看合同联系人信息表页面
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
	 * 获取所有数据返回json
	 */
	function c_listJsonLimit() {
		$service = $this->service;


		$service->getParam ( $_REQUEST );
		$rows = $service->list_d ();

		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}
}
?>