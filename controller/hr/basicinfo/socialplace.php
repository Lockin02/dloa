<?php
/**
 * @author Administrator
 * @Date 2012年8月11日 星期六 10:27:17
 * @version 1.0
 * @description:社保购买地控制层
 */
class controller_hr_basicinfo_socialplace extends controller_base_action {

	function __construct() {
		$this->objName = "socialplace";
		$this->objPath = "hr_basicinfo";
		parent::__construct ();
	 }

	/**
	 * 跳转到社保购买地列表
	 */
    function c_page() {
      $this->view('list');
    }

   /**
	 * 跳转到新增社保购买地页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }

   /**
	 * 跳转到编辑社保购买地页面
	 */
	function c_toEdit() {
   	$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
      $this->view ( 'edit');
   }

	/*
	 * 跳转到社保购买地列表
	 */
	function c_toList(){
		$this->view('list');
	}

	/*
	 * 重写新增方法
	 */
	function c_add_d(){
		$id = $this->service->add_d ( $_POST [$this->objName], true );
		$msg = $_POST ["msg"] ? $_POST ["msg"] : '添加成功！';
		if ($id) {
			msg ( $msg );
		}
	}

   /**
	 * 跳转到查看社保购买地页面
	 */
	function c_toView() {
      $this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET ['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}
      $this->view ( 'view' );
   }
 }
?>