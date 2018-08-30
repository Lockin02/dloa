<?php
/**
 * @author Administrator
 * @Date 2013年5月29日 10:08:38
 * @version 1.0
 * @description:商机设备硬件管理控制层
 */
class controller_projectmanagent_hardware_hardware extends controller_base_action {

	function __construct() {
		$this->objName = "hardware";
		$this->objPath = "projectmanagent_hardware";
		parent::__construct ();
	 }

	/**
	 * 跳转到商机设备硬件管理列表
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * 跳转到新增商机设备硬件管理页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }

   /**
	 * 跳转到编辑商机设备硬件管理页面
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
	 * 跳转到查看商机设备硬件管理页面
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
	 * 新增对象操作
	 */
	function c_add($isAddInfo = true) {
		$this->checkSubmit();
		$id = $this->service->add_d ( $_POST [$this->objName], $isAddInfo );
		$msg = $_POST ["msg"] ? $_POST ["msg"] : '添加成功！';
		if ($id) {
			msg ( $msg );
		}

		//$this->listDataDict();
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
	function c_edit($isEditInfo = true) {
//		$this->permCheck (); //安全校验
		$this->checkSubmit();
		$object = $_POST [$this->objName];
		if ($this->service->edit_d ( $object, $isEditInfo )) {
			msg ( '编辑成功！' );
		}
	}


	/**
	 * 是否启用设备
	 */
		function c_ajaxUseStatus(){
		try{
			$this->service->ajaxUseStatus_d($_POST ['id'],$_POST ['useStatus']);
			echo 1;
		} catch(Exception $e){
			echo 0;
		}
	}


 }
?>