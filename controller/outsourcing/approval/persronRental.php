<?php
/**
 * @author Administrator
 * @Date 2013年11月2日 星期六 14:03:48
 * @version 1.0
 * @description:外包立项人员租赁控制层 
 */
class controller_outsourcing_approval_persronRental extends controller_base_action {

	function __construct() {
		$this->objName = "persronRental";
		$this->objPath = "outsourcing_approval";
		parent::__construct ();
	 }
    
	/**
	 * 跳转到外包立项人员租赁列表
	 */
    function c_page() {
      $this->view('list');
    }
    
   /**
	 * 跳转到新增外包立项人员租赁页面
	 */
	function c_toAdd() {
     $this->view ( 'add' );
   }
   
   /**
	 * 跳转到编辑外包立项人员租赁页面
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
	 * 跳转到查看外包立项人员租赁页面
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