<?php
/**
 * @author Administrator
 * @Date 2012-08-09 15:38:12
 * @version 1.0
 * @description:离职交接清单明细控制层
 */
class controller_hr_leave_handoverlist extends controller_base_action {

	function __construct() {
		$this->objName = "handoverlist";
		$this->objPath = "hr_leave";
		parent::__construct ();
	 }

	/**
	 * 跳转到离职交接清单明细列表
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * 跳转到新增离职交接清单明细页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }

   /**
	 * 跳转到编辑离职交接清单明细页面
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
	 * 跳转到查看离职交接清单明细页面
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
	function c_addItemJson() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$service->asc=false;
		$rows = $service->list_d ("select_default");

		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}

	/**
	 * 获取所有数据返回json
	 */
	function c_addItemJsonList() {
		$service = $this->service;
		$service->getParam ( $_REQUEST );
		$service->asc=false;
		$rows = $service->list_d ("select_default");

		//数据加入安全码
		$rows = $this->sconfig->md5Rows ( $rows );
		echo util_jsonUtil::encode ( $rows );
	}

	/*
	 * 离职交接清单重启
	 */
	function c_restart() {
		$this->permCheck ();
		if ($this->service->restart_d ( $_POST ['handover'] )) {
			 msg ( '重启成功！' );
		}
	}

	/*
	 * 离职交接清单修改
	 */
	function c_alterHand() {
		$this->permCheck ();
		//var_dump($_POST ['handover']);exit();
		if ($this->service->alterHand_d ( $_POST ['handover'] )) {
			 msg ( '修改成功！' );
		}
	}
}
?>