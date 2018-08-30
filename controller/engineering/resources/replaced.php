<?php
/**
 * @author Administrator
 * @Date 2012-11-19 14:52:42
 * @version 1.0
 * @description:设备管理-可替换设备管理控制层
 */
class controller_engineering_resources_replaced extends controller_base_action {

	function __construct() {
		$this->objName = "replaced";
		$this->objPath = "engineering_resources";
		parent::__construct ();
	 }

	/**
	 * 跳转到设备管理-可替换设备管理列表
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * 跳转到新增设备管理-可替换设备管理页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }

   /**
	 * 跳转到编辑设备管理-可替换设备管理页面
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
	 * 跳转到查看设备管理-可替换设备管理页面
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
	function c_add($isAddInfo = false) {
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
	function c_edit($isEditInfo = false) {
//		$this->permCheck (); //安全校验
		$object = $_POST [$this->objName];
		if ($this->service->edit_d ( $object, $isEditInfo )) {
			msg ( '编辑成功！' );
		}
	}

	/**
	 * 判断设备是否存在
	 */
	function c_checkIsRepeat(){
		$deviceId = $_POST['deviceId'];
		$rs = $this->service->find(array('deviceId' => $deviceId),null,'id');
		if($rs){
			echo 1;
		}else{
			echo 0;
		}
		exit();
	}

 }
?>