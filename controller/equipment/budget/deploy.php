<?php
/**
 * @author Administrator
 * @Date 2012-10-29 14:46:46
 * @version 1.0
 * @description:设备配置控制层
 */
class controller_equipment_budget_deploy extends controller_base_action {

	function __construct() {
		$this->objName = "deploy";
		$this->objPath = "equipment_budget";
		parent::__construct ();
	 }

	/*
	 * 跳转到设备配置列表
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * 跳转到新增设备配置页面
	 */
	function c_toAdd() {
	  $this->assign('equId' , $_GET['equId']);
      $this->view ( 'add' );
   }

   /**
	 * 跳转到编辑设备配置页面
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
	 * 跳转到查看设备配置页面
	 */
	function c_toView() {
      $this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'view' );
   }

	/**
	 * 新增对象操作
	 */
	function c_add($isAddInfo = false) {
		$id = $this->service->add_d ( $_POST [$this->objName], $isAddInfo );
		$msg = $_POST ["msg"] ? $_POST ["msg"] : '添加成功！';
		if ($id) {
			msg ( $msg );
		}
	}

	/**
	 * 初始化对象
	 */
	function c_init() {
		$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
		if (isset ( $_GET ['perm'] ) && $_GET ['perm'] == 'view') {
			$this->display ( 'view' );
		} else {
			$this->display ( 'edit' );
		}
	}
	/**
	 * 修改对象
	 */
	function c_edit($isEditInfo = false) {
//		$this->permCheck (); //安全校验
		$object = $_POST [$this->objName];
		if ($this->service->edit_d ( $object, $isEditInfo )) {
			msg ( '编辑成功！' );
		}
	}

   /**
    * 编辑配置
    */
   	function c_toEditConfig() {
		$this->assign ( "equId", $_GET ['equId'] );
		$this->view ( "config-edit" );
	}
	 /**
    * 编辑配置toViewConfig
    */
   	function c_toViewConfig() {
		$equId = $_GET ['equId'];
		$baseDao = new model_equipment_budget_budgetbaseinfo();
        $baseinfo = $baseDao->get_d($equId);
        foreach ( $baseinfo as $key => $val ) {
			$this->assign ( $key, $val );
		}

		$list = $this->service->deployList($equId);
		$this->assign("list",$list);
		$this->view ( "config-view" );
	}
 }
?>