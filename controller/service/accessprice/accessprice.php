<?php
/**
 * @author huangzf
 * @Date 2011年12月1日 9:52:49
 * @version 1.0
 * @description:零配件价格表控制层 
 */
class controller_service_accessprice_accessprice extends controller_base_action {

	function __construct() {
		$this->objName = "accessprice";
		$this->objPath = "service_accessprice";
		parent::__construct ();
	 }
    
	/**
	 * 跳转到零配件价格表列表
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * 跳转到新增零配件价格表页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * 跳转到编辑零配件价格表页面
	 */
	function c_toEdit() {
  	 	//$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'edit');
   }
   
   /**
	 * 跳转到查看零配件价格表页面
	 */
	function c_toView() {
      //$this->permCheck (); //安全校验
		$obj = $this->service->get_d ( $_GET['id'] );
		foreach ( $obj as $key => $val ) {
			$this->assign ( $key, $val );
		}         $this->view ( 'view' );
   }
	/**
	 * 新增对象操作
	 */
	function c_add() {
		$id = $this->service->add_d ( $_POST [$this->objName], true );
		$msg = $_POST ["msg"] ? $_POST ["msg"] : '添加成功！';
		if ($id) {
			msg ( $msg );
		}
	}
	/**
	 * 修改对象
	 */
	function c_edit() {
//		$this->permCheck (); //安全校验
		$object = $_POST [$this->objName];
		if ($this->service->edit_d ( $object, true )) {
			msg ( '编辑成功！' );
		}
	}
 }
?>